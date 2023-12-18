<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Utils\NotificationUtil;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected $notificationUtil;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(NotificationUtil $notificationUtil)
    {
        $this->middleware('guest')->except('logout');
        $this->notificationUtil = $notificationUtil;
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        // Get the user details from database and check if user is exist and active.
        $user = User::where('email', $request->email)->first();
        if ($user && !$user->is_active) {
            throw ValidationException::withMessages([$this->username() => __('User has been desactivated.')]);
        }

        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

        // Get the current date
        $currentDate = Carbon::today();
        // Retrieve the last execution date from the cache or database
        $lastExecutionDate = Cache::get('last_execution_date');
        // Check if the last execution date is not today
        if (!$lastExecutionDate || $lastExecutionDate < $currentDate) {
            // Call the function or perform the desired task
            $this->notificationUtil->checkExpiary();
            $this->notificationUtil->quantityAlert();
            // Store the current date as the last execution date
            Cache::put('last_execution_date', $currentDate, 1440); // 1440 minutes = 1 day
        }

    }
}
