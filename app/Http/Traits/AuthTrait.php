<?php
namespace App\Http\Traits;

use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

trait AuthTrait
{
    use GeoLocationTrait;
    /**
     * Authenticate a user with the provided credentials
     * 
     * @param array $data Contains email and password
     * @return array Associative array with status (bool), message (string), and user (User|null)
     */
    public function loginUser(array $data)
    {
        $validateUser = Validator::make($data, 
        [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validateUser->fails()){
            return [
                'status' => false,
                'message' => $validateUser->errors()->first(),
                'user' => null
            ];
        }

        if(!Auth::attempt([
            'email' => $data['email'], 
            'password' => $data['password']
        ], $data['remember'] ?? false)){
            return [
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
                'user' => null
            ];
        }

        $user = User::where('email', $data['email'])->first();
        if(!$user->status){
            Auth::logout();
            return [
                'status' => false,
                'message' => 'Account Suspended',
                'user' => null
            ];
        }
        
        return [
            'status' => true,
            'message' => 'Login successful',
            'user' => $user
        ];
    }
    

    /**
     * Register a new user with the provided data
     * 
     * @param array $data Contains registration details (first_name, last_name, email, password, etc.)
     * @return array Associative array with status (bool), message (string), and user (User|null)
     */
    public function registerUser(array $data)
    {
        $validator = Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'user' => null
            ];
        }

        try {
            // Get location data if available (assuming GeoLocationTrait functionality)
            $location = $this->getLocation();
            
            // Create the user
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'firstname' => $data['firstname'],
                'surname' => $data['surname'],
                'country_id' => $location ? $location->country_id : null,
                'status' => true
            ]);

            // Optionally trigger registered event
            // event(new \Illuminate\Auth\Events\Registered($user));

            return [
                'status' => true,
                'message' => 'Registered successfully',
                'user' => $user
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
                'user' => null
            ];
        }
    }

    public function logoutUser()
    {
        Auth::logout();
        return redirect()->route('signin');
    }

    public function forgotUserPassword()
    {
        dd($this->email);
    }

    public function resetUserPassword() 
    {
        dd($this->email, $this->password, $this->password_confirmation);
    }

    public function updateUserPassword()    
    {
        dd($this->email, $this->password, $this->password_confirmation);
    }

    public function verifyUserEmail()
    {
        dd($this->email);
    }

    public function resendVerificationEmailToUser()
    {   
        dd($this->email);
    }

    public function sendResetLinkToUser()
    {
        dd($this->email);
    }
    
    
}
