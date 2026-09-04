<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class LoginController extends Controller
{
    /**
     * Show login page.
     */
    public function welcome()
    {
        return view('welcome');
    }


    /**
     * Login user.
     */
    public function login(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Already Logged In
        |--------------------------------------------------------------------------
        */

        if ($request->session()->get('logged_in') === true) {
            return redirect()->route('dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Login Form
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | TEMPORARY LOCAL DEVELOPMENT LOGIN
        |--------------------------------------------------------------------------
        |
        | This section will only run when:
        |
        | APP_ENV=local
        |
        | This allows frontend development without connecting to:
        | - Login API
        | - Employee API
        | - Database
        |
        */

        if (app()->environment('local')) {

            $localEmail = 'admin@lims.local';
            $localPassword = 'admin123';

            if (
                $request->email === $localEmail &&
                $request->password === $localPassword
            ) {

                /*
                |--------------------------------------------------------------------------
                | Regenerate Session
                |--------------------------------------------------------------------------
                */

                $request->session()->regenerate();


                /*
                |--------------------------------------------------------------------------
                | Temporary Authentication Information
                |--------------------------------------------------------------------------
                */

                $request->session()->put('logged_in', true);

                // No API token because we're working locally
                $request->session()->put('api_token', null);


                /*
                |--------------------------------------------------------------------------
                | Temporary Employee Information
                |--------------------------------------------------------------------------
                */

                $request->session()->put('emp_no', '00001');

                $request->session()->put(
                    'user_email',
                    $localEmail
                );

                $request->session()->put(
                    'user_name',
                    'Local Admin'
                );


                /*
                |--------------------------------------------------------------------------
                | Temporary Role
                |--------------------------------------------------------------------------
                |
                | Change this depending on what role you want to test.
                |
                */

                $request->session()->put('role_id', 1);


                /*
                |--------------------------------------------------------------------------
                | Temporary Employee Details
                |--------------------------------------------------------------------------
                */

                $request->session()->put(
                    'appointmentStatus',
                    'Permanent'
                );

                $request->session()->put(
                    'designation_id',
                    1
                );

                $request->session()->put(
                    'division_id',
                    1
                );

                $request->session()->put(
                    'divisionName',
                    'IMTS'
                );


                /*
                |--------------------------------------------------------------------------
                | User Session Object
                |--------------------------------------------------------------------------
                */

                $request->session()->put('user', [
                    'email' => $localEmail,
                    'name' => 'Local Admin',
                ]);


                /*
                |--------------------------------------------------------------------------
                | Redirect
                |--------------------------------------------------------------------------
                */

                return redirect()->route('dashboard');
            }


            /*
            |--------------------------------------------------------------------------
            | Invalid Local Login
            |--------------------------------------------------------------------------
            */

            return back()
                ->withInput(
                    $request->only('email')
                )
                ->withErrors([
                    'login' => 'Invalid local development account.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | REAL API LOGIN
        |--------------------------------------------------------------------------
        |
        | Everything below this point will only run when the Laravel
        | environment is NOT "local".
        |
        */

        $client = new \GuzzleHttp\Client();

        try {

            /*
            |--------------------------------------------------------------------------
            | Authentication API
            |--------------------------------------------------------------------------
            */

            $url = 'http://10.0.224.35:7152/api/UserCont/login/'
                . rawurlencode($request->email)
                . ','
                . rawurlencode($request->password)
                . ','
                . rawurlencode('FFAST');


            $response = $client->post($url, [
                'verify' => false,

                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);


            /*
            |--------------------------------------------------------------------------
            | Get JWT Token
            |--------------------------------------------------------------------------
            */

            $responseData = json_decode(
                $response->getBody(),
                true
            );

            $token = $responseData['token'] ?? null;


            /*
            |--------------------------------------------------------------------------
            | Token Not Received
            |--------------------------------------------------------------------------
            */

            if (!$token) {

                return back()
                    ->withInput(
                        $request->only('email')
                    )
                    ->withErrors([
                        'login' => 'Login failed. Token not received.',
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Decode JWT
            |--------------------------------------------------------------------------
            */

            try {

                $key = "NpSoHmbjMRpX6SjvQ4wWhnmpvVKdgC0djDMBBBHI4jt2uHghZ5lge3FYtfUlmxke7KXY8LbarZ3zUA7ufqn3P8gGoJbEE0wTqBGXfrdktFJ0DuaO36VKcwYP0x8lDsaI";


                $decoded = JWT::decode(
                    $token,
                    new Key($key, 'HS256')
                );


                $payload = (array) $decoded;


                /*
                |--------------------------------------------------------------------------
                | Employee Number
                |--------------------------------------------------------------------------
                */

                $empNo = preg_replace(
                    '/\D/',
                    '',
                    (string) ($payload['Emp_No'] ?? '')
                );

                $request->session()->put(
                    'emp_no',
                    $empNo
                );


                /*
                |--------------------------------------------------------------------------
                | Get Employee Profile
                |--------------------------------------------------------------------------
                */

                $apiEndpointEmployeeProfile =
                    'http://10.0.224.35:7154/api/employee/getEmployee/'
                    . rawurlencode($empNo);


                $responseEmployeeProfile =
                    Http::withOptions([
                        'verify' => false,
                    ])
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json',
                    ])
                    ->get(
                        $apiEndpointEmployeeProfile
                    );


                $dataProfile =
                    $responseEmployeeProfile->json();


                /*
                |--------------------------------------------------------------------------
                | Create Login Session
                |--------------------------------------------------------------------------
                */

                $request->session()->regenerate();

                $request->session()->put(
                    'logged_in',
                    true
                );

                $request->session()->put(
                    'api_token',
                    $token
                );

                $request->session()->put(
                    'user_email',
                    $payload['email'] ?? $request->email
                );

                $request->session()->put(
                    'user_name',
                    $dataProfile['firstname'] ?? 'Unknown User'
                );

                $request->session()->put(
                    'role_id',
                    $payload['Role_id'] ?? null
                );


                $request->session()->put('user', [

                    'email' =>
                        $payload['email']
                        ?? $request->email,

                    'name' =>
                        $dataProfile['firstname']
                        ?? 'Unknown User',

                ]);


                /*
                |--------------------------------------------------------------------------
                | Get Employee Extended Information
                |--------------------------------------------------------------------------
                */

                $apiEndpoint =
                    'http://10.0.224.35:7154/api/employee/getEmployeeEXP/'
                    . rawurlencode($empNo);


                $response =
                    Http::withOptions([
                        'verify' => false,
                    ])
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json',
                    ])
                    ->get(
                        $apiEndpoint
                    );


                /*
                |--------------------------------------------------------------------------
                | Save Employee Information
                |--------------------------------------------------------------------------
                */

                if ($response->successful()) {

                    $data = $response->json();


                    $request->session()->put(
                        'appointmentStatus',
                        $data['statusType'] ?? null
                    );

                    $request->session()->put(
                        'designation_id',
                        $data['designation_role'] ?? null
                    );

                    $request->session()->put(
                        'division_id',
                        $data['division'] ?? null
                    );

                    $request->session()->put(
                        'divisionName',
                        $data['divisionName'] ?? null
                    );

                } else {

                    $request->session()->put(
                        'appointmentStatus',
                        null
                    );

                    $request->session()->put(
                        'designation_id',
                        null
                    );

                    $request->session()->put(
                        'division_id',
                        null
                    );

                    $request->session()->put(
                        'divisionName',
                        null
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Successful Login
                |--------------------------------------------------------------------------
                */

                return redirect()->route('dashboard');


            } catch (\Exception $e) {

                report($e);

                return back()
                    ->withInput(
                        $request->only('email')
                    )
                    ->withErrors([
                        'login' =>
                            'An unexpected error occurred while decoding token.',
                    ]);
            }


        /*
        |--------------------------------------------------------------------------
        | Invalid Credentials
        |--------------------------------------------------------------------------
        */

        } catch (\GuzzleHttp\Exception\ClientException $e) {

            return back()
                ->withInput(
                    $request->only('email')
                )
                ->withErrors([
                    'login' => 'Invalid credentials.',
                ]);


        /*
        |--------------------------------------------------------------------------
        | API Server Error
        |--------------------------------------------------------------------------
        */

        } catch (\GuzzleHttp\Exception\ServerException $e) {

            return back()
                ->withInput(
                    $request->only('email')
                )
                ->withErrors([
                    'login' =>
                        'Server error. Please try again later.',
                ]);


        /*
        |--------------------------------------------------------------------------
        | Other Errors
        |--------------------------------------------------------------------------
        */

        } catch (\Exception $e) {

            report($e);

            return back()
                ->withInput(
                    $request->only('email')
                )
                ->withErrors([
                    'login' =>
                        'An unexpected error occurred. The API failed to respond.',
                ]);
        }
    }


    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        $request->session()->flush();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}