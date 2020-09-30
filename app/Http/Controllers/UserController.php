<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use UnexpectedValueException;
use Illuminate\Support\Arr;
use App\Http\Controllers\Auth\CreatePasswordController;
use App\Http\Controllers\Auth\RegisterController;
use Auth;
use Log;

class UserController extends Controller
{

    use ResetsPasswords;

    /**
     * The user provider implementation.
     *
     * @var \Illuminate\Contracts\Auth\UserProvider
     */
    protected $users;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        Log::debug($user->name." ".$user->email." Admin? ".$user->is_admin);
        if ($user->is_admin) {
            $data['users'] = \App\User::all();
            return view('users')->with($data);
        } else {
            return $this->edit($user->id);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $new_user = new \App\User();
        return view('new_user_form', [
            "user" => Auth::user(),
            "new_user" => $new_user,
            "message" => "Create a New User:",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $logged_in = Auth::user();
        $rc = new RegisterController();
        $user = $rc->create([
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->is_admin=='on'?1:0,
            'password' => str_random(12),
        ]);
        Log::debug([$user->id, $user->name, $user->email]);
        $fpc = new CreatePasswordController();
        $ret = $fpc->sendCreateLinkEmail($request);
        //$ret is a forward to /user/create
        Auth::login($logged_in); //reset user back to admin, was using new user
        $data['users'] = \App\User::all();
        return view('users')->with($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        if (Auth::user()->is_admin) { //only find user if current user is admin,
            //otherwise show current user info
            Log::debug("Showing user $id since ".Auth::user()->email." is an admin");
            $user = \App\User::find($id);
        }
        $data['user'] = $user;
        return view('home')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        if (Auth::user()->is_admin) { //only find user if current user is admin,
            //otherwise show current user info
            Log::debug("Editing user $id since ".Auth::user()->email." is an admin");
            $user = \App\User::find($id);
        }
        Log::debug("Editing record of user ".$user->email);
        $data['user'] = $user;
        if ($user == Auth::user())
        {
            $data['message'] = 'Edit your profile:';
        }
        else
        {
            $data['message'] = 'Edit profile of '.$user->name.':';
        }
        return view('user_form')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->is_admin) {
            $user = \App\User::find($id);
        } else if ($id != $user->id) {
            return redirect()->back()
            ->withInput($request->only('name'))
            ->withErrors(['name' => ['You are not an Admin, you cannot edit this profile.']]);
        }

        Log::debug("At update in UserController for user ".$user->email);

        if ($request->name) {
            $user->name = $request->name;
        }
        if ($request->email) {
            $user->email = $request->email;
        }
        if ($request->is_admin) {
            $user->is_admin = $request->is_admin=='on'?1:0;
        }
        $user->save();
        Log::debug("User changed");
        Log::debug($user);
        $data['user'] = $user;
        if ($request->password) {
            if ($this->guard()->attempt(
                    ['email' => $request->input('email'), 'password' => $request->input('old_password')],
                    $request->has('remember'))) {
                Log::debug("Old password is correct, Attempting to reset password");
                $this->reset($request);
            } else {
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['old_password' => ['Incorrect Password']]);
            }
        }
        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->is_admin)
        {
            $user = \App\User::find($id);
            if ($user->is_admin) {
                return redirect()->back();
            } else {
                $user->delete();
            }
        }
        $data['users'] = \App\User::all();
        return view('users')->with($data);
    }


    public function reset(Request $request)
    {

        $this->validate($request, $this->rules(), $this->validationErrorMessages());


        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker_reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );

        Log::debug($response);

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == 'password.reset'
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetResponse(Request $request,$response)
    {
        return;
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return mixed
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'password' => 'required|confirmed|min:6',
        ];
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $creds = $request->only(
            'email', 'password', 'password_confirmation'
        );
        $creds['token'] = 'dummy';
        return $creds;
    }

    /**
     * Reset the password for the given token.
     *
     * @param  array  $credentials
     * @param  \Closure  $callback
     * @return mixed
     */
    public function broker_reset(array $credentials, $callback)
    {
        // If the responses from the validate method is not a user instance, we will
        // assume that it is a redirect and simply return it from this method and
        // the user is properly redirected having an error message on the post.
        $user = $this->validateReset($credentials);

        //if (! $user instanceof CanResetPasswordContract) {
        //    return $user;
        //}

        $password = $credentials['password'];

        // Once the reset has been validated, we'll call the given callback with the
        // new password. This gives the user an opportunity to store the password
        // in their persistent storage. Then we'll delete the token and return.
        $callback($user, $password);

        return 'password.reset';
    }

    /**
     * Validate a password reset for the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\CanResetPassword|string
     */
    protected function validateReset(array $credentials)
    {
        if (is_null($user = $this->getUser($credentials))) {
            return static::INVALID_USER;
        }

        if (! $this->validateNewPassword($credentials)) {
            return static::INVALID_PASSWORD;
        }

        return $user;
    }

    /**
     * Determine if the passwords match for the request.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validateNewPassword(array $credentials)
    {
        if (isset($this->passwordValidator)) {
            list($password, $confirm) = [
                $credentials['password'],
                $credentials['password_confirmation'],
            ];

            return call_user_func(
                $this->passwordValidator, $credentials
            ) && $password === $confirm;
        }

        return $this->validatePasswordWithDefaults($credentials);
    }

    /**
     * Determine if the passwords are valid for the request.
     *
     * @param  array  $credentials
     * @return bool
     */
    protected function validatePasswordWithDefaults(array $credentials)
    {
        list($password, $confirm) = [
            $credentials['password'],
            $credentials['password_confirmation'],
        ];

        return $password === $confirm && mb_strlen($password) >= 6;
    }

    /**
     * Get the user for the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\CanResetPassword|null
     *
     * @throws \UnexpectedValueException
     */
    public function getUser(array $credentials)
    {
        $credentials = Arr::except($credentials, ['token']);

        $user = \App\User::where('email', $credentials['email'])->first();

        //if ($user && ! $user instanceof CanResetPasswordContract) {
        //    throw new UnexpectedValueException('User must implement CanResetPassword interface.');
        //}

        return $user;
    }

}
