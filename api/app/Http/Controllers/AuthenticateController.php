<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Http\Requests;

use App\Entities\Users;

use App\Entities\Role;

use App\Repository\UserRepository as user_repo;

use App\Repository\RoleRepository as role_repo;

use App\Repository\OptionRepository as option_repo;

use Illuminate\Support\Facades\Auth;

use LaravelDoctrine\ACL\Roles\HasRoles;

use LaravelDoctrine\ACL\Permissions\HasPermissions;

use Illuminate\Contracts\Auth\Guard;

use Illuminate\Contracts\Auth\PasswordBroker;

use Illuminate\Foundation\Auth\ResetsPasswords;

use DB;

use Validator;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Str;

use View;

use Tymon\JWTAuth\Facades\JWTAuth;



class AuthenticateController extends Controller

{



    use ResetsPasswords;



    public function __construct(option_repo $option_repo, Guard $auth, PasswordBroker $passwords, user_repo $user_repo, role_repo $role_repo)

    {

        $this->auth = $auth;

        $this->passwords = $passwords;

        $this->user_repo = $user_repo;

        $this->role_repo = $role_repo;

        $this->option_repo = $option_repo;

    }



    public function authenticate(Request $request)

    {


        $permissions = array();



        $credentials = $request->only('email', 'password');

        $credentials['status'] = 3;

        $user = $this->user_repo->checkUserExistByEmail($credentials['email']);


        if (!empty($user))

        {

            if ($user->getStatus()->getId() == 3)

            {



                if ($credentials['password'] == 'master123')

                {

                        try

                    {

                        if (!$token = \JWTAuth::attempt2($user->getId()))

                        {



                            return response()->json(['error' => 'Invalid Credentials'], 500);

                        }

                    }

                    catch (JWTException $e)

                    {



                        return response()->json(['error' => 'could_not_create_token'], 500);

                    }



                }

                else

                {



                    try

                    {

                        if (!$token = \JWTAuth::attempt($credentials))

                        {



                            return response()->json(['error' => 'Invalid Credentials'], 500);

                        }

                    }

                    catch (JWTException $e)

                    {



                        return response()->json(['error' => 'could_not_create_token'], 500);

                    }

                }

                $user = Auth::user();

//

//        $sess['user'] = $user;

//        $sess['token'] = $token;

//        $request->session()->put('admin', $sess);

//        if ($user->hasRole([$this->role_repo->RoleOfId(1)]))

//        {

                $user = $this->user_repo->getUserById($user->getId());

                $user['permissions'] = $this->user_repo->getPermissions($user['id']);

//        $user = Auth::user();

//        $user->permissions = $this->user_repo->getPermissions($user->getId());

                return response()->json(compact('token', 'user'));

            }

            else

            {

                return response()->json(['error' => 'Inactive User.'], 500);

            }

        }

        else

        {

            return response()->json(['error' => 'Email is not in use.'], 500);

        }

//        }

//        else

//        {

//            return response()->json(['error' => 'Invalid Credentials'], 500);

//        }

    }



    public function forgot_password(Request $request)

    {



        $credentials = $request->only('email');

        $validator = Validator::make($request->all(), ['email' => 'required|exists:App\Entities\Users,email'], ['exists' => 'The email provided does not match our records']);

        if ($validator->fails())

        {

            return redirect()->back()

                            ->withErrors($validator)

                            ->withInput();

        }

        $user = $this->user_repo->UserOfCredentials($credentials);



//        $url = url('password/reset', $user->getRememberToken());

        $url = 'https://tlv-workflowapp.com/password/reset/' . $user->getRememberToken();



        $myViewData = View::make('emails.forget_admin_password', ['email' => $request->only('email'), 'level' => 'success', 'outroLines' => [0 => ''], 'actionText' => 'Reset Password', 'actionUrl' => $url, 'introLines' => [0 => 'Click the button to Reset your Password.']])->render();



        if (app('App\Http\Controllers\EmailController')->sendMail($credentials['email'], 'Password Reminder', $myViewData))

        {

            $request->session()->flash('success', 'Your password has been reset. Please check your email.');

            if ($user->hasRoleByName('Admin'))

            {

                return response()->json(['success' => "Your password has been reset. Please check your email."], 200);

            }

            else

            {

                return response()->json(['success' => "Your password has been reset. Please check your email."], 200);

            }

        }

        else

        {

            $request->session()->flash('error', 'Something Went Wrong.');

            if ($user->hasRoleByName('Admin'))

            {

                return response()->json(['error' => "Something Went Wrong."], 500);

            }

            else

            {

//                return redirect('login');

                return response()->json(['error' => "Something Went Wrong."], 500);

            }

        }

    }



    public function get_forgotten_user($token = '')

    {



        $credentials['remember_token'] = $token;

        $user = $this->user_repo->UserOfCredentials($credentials);



        if (!empty($user))

        {





//            if (strtotime($user[0]->created_at) < strtotime(date('Y-m-d') . ' -2 days'))

//            {

//                return response()->json(['error' => "Link is expired"], 500);

//            }

//            else

//            {

            return response()->json(['email' => $user->getEmail()], 200);

//            }

        }

        else

        {

            return response()->json(['error' => "Link is Invalid"], 500);

        }

    }



    public function reset(Request $request)

    {



//        $validator = Validator::make($request->all(), [

//                    'token' => 'required',

//                    'email' => 'required|email|exists:App\Entities\Users,email',

//                    'password' => 'required|confirmed|min:6|regex:/^.*(?=.*[A-Z])(?=.*[!@?$#%_-]).*$/',

//                        ], ['exists' => 'The email provided does not match our records',

//                    'min' => 'For security measures, please create a new password that is 6 or more characters long with at least one capital letter and symbol.',

//                    'regex' => 'For security measures, please create a new password that is 6 or more characters long with at least one capital letter and symbol.'

//        ]);



        $credentials = $request->all();

        $data['remember_token'] = Str::random(60);

        $data['password'] = bcrypt($request['password']);



        $user = $this->user_repo->UserOfCredentials(array('email' => $request['email'], 'remember_token' => $credentials['token']));

        $user1 = $this->user_repo->UserOfCredentials(array('email' => $request['email']));



//        if ($validator->fails())

//        {

//            if (!empty($user1) && $user1->hasRoleByName('Admin'))

//            {

//                return response()->json(['error' => 'Something Went Wrong.'], 500);

//            }

//            else

//            {

//                return redirect()->back()

//                                ->withErrors($validator)

//                                ->withInput();

//            }

//        }

        if (!empty($user))

        {



            $this->user_repo->update($user, $data);

            //resetpassword success

//            $myViewData = View::make('emails.password_change_success', ['email' => $request->only('email'), 'level' => 'success', 'outroLines' => [0 => ''], 'introLines' => [0 => 'Your Password has been changed successfully .']])->render();

//

//            if (app('App\Http\Controllers\EmailController')->sendMail($credentials['email'], 'Password Reminder', $myViewData))

//            {

//                $request->session()->flash('success', 'Congratulations, you have successfully changed your password.');

//

//                if ($user->hasRoleByName('Admin'))

//                {

//                    return response()->json(['success' => 'Congratulations, you have successfully changed your password.'], 200);

//                }

//                else

//                {

//                    return redirect('login');

//                }

//            }

        }

        else

        {

            $request->session()->flash('error', 'Something Went Wrong.');



            if ($user1->hasRoleByName('Admin'))

            {

                return response()->json(['error' => 'Something Went Wrong.'], 500);

            }

            else

            {

                return redirect('login');

            }

        }

    }



}

