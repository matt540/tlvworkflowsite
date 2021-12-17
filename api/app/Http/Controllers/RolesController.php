<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Role;
use App\Repository\RoleRepository as role_repo;

class RolesController extends Controller
{

    public function __construct(role_repo $role_repo)
    {
        $this->role_repo = $role_repo;
    }

    public function getAllRoles()
    {
        $result = $this->role_repo->getAllRoles();
        unset($result[0]);
        return $result;
    }

    public function save_role(Request $request)
    {
        if (isset($request->id))
        {
            $role = $this->role_repo->RoleOfId($request->id);
            $data['name'] = $request->name;
            $this->role_repo->update($role, $data);
        }
        else
        {
            $data = $this->role_repo->prepareData($request);
            $this->role_repo->create($data);
        }
    }

    public function get_role(Request $request)
    {
        return $this->role_repo->RoleById($request->id);
    }

    public function delete_role(Request $request)
    {
        $role = $this->role_repo->RoleOfId($request->id);
        $this->role_repo->delete($role);
    }

    public function get_roles(Request $request)
    {
        $filter = $request->all();

        $data['draw'] = $filter['draw'];

        $roles_data_total = $this->role_repo->get_roles($filter);

        $data['data'] = $roles_data_total['data'];
        $data['recordsTotal'] = $roles_data_total['total'];
        $data['recordsFiltered'] = $this->role_repo->get_roles_total($filter);
        return response()->json($data, 200);
    }
    public function get_all_roles()
    {
        return $this->role_repo->get_all_roles();
    }
}
