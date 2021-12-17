<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repository\RoleRepository as role_Repo;
use App\Repository\OptionRepository as option_repo;
use App\Repository\PermissionRepository as permission_repo;
use LaravelDoctrine\ACL\Permissions\PermissionManager;

class PermissionController extends Controller
{

    public function __construct(PermissionManager $manager, role_Repo $role_Repo, option_repo $option_repo, permission_repo $permission_repo)
    {
        $this->manager = $manager;
        $this->role_Repo = $role_Repo;
        $this->option_repo = $option_repo;
        $this->permission_repo = $permission_repo;
    }

    public function get_permission_categories(Request $request)
    {
        return $this->option_repo->getOptionsBySelectId(1);
    }

    public function get_all_permissions()
    {
        $permission_array = array();
        $permissions = $this->permission_repo->get_all_permissions();

        foreach ($permissions as $key => $value)
        {
            $permission_array[$value['category']['id']]['data'][] = $value;
            $permission_array[$value['category']['id']]['name'] = $value['category']['value_text'];
        }
        return $permission_array;
    }

    public function set_permissions(Request $request)
    {
        $data = $request->permission;

        $role = $this->role_Repo->RoleOfId($data['role']);
        if ($data['permission_status'])
        {
            $this->role_Repo->add_role_permission($role, $this->permission_repo->PermissionOfId($data['permission']));
        }
        else
        {
            $this->role_Repo->remove_role_permission($role, $this->permission_repo->PermissionOfId($data['permission']));
        }
    }

    public function save_permission(Request $request)
    {
        $data = $request->all();
        if (isset($data['id']) && $data['id'] != '')
        {
            $data_update = $data;
            $data_update['category'] = $this->option_repo->OptionOfId($data['category']);
            $permission = $this->permission_repo->PermissionOfId($data['id']);
            $this->permission_repo->update($permission, $data_update);
        }
        else
        {
            $data['category'] = $this->option_repo->OptionOfId($data['category']);
            $permission = $this->permission_repo->prepareData($data);
            $this->permission_repo->create($permission);
        }
    }

    public function get_permissions_by_role(Request $request)
    {
        return $this->role_Repo->getPermissionsArray($request->id);
    }

    public function get_permission(Request $request)
    {
        return $this->permission_repo->PermissionById($request->id);
    }

    public function get_permissions(Request $request)
    {
        $filter = $request->all();

        $data['draw'] = $filter['draw'];

        $roles_data_total = $this->permission_repo->get_permissions($filter);

        $data['data'] = $roles_data_total['data'];
        $data['recordsTotal'] = $roles_data_total['total'];
        $data['recordsFiltered'] = $this->permission_repo->get_permissions_total($filter);
        return response()->json($data, 200);
        
    }

}
