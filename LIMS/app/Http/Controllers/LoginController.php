<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class LoginController extends Controller
{/*  */
    public function welcome()
    {
        return view('welcome');
    }

    public function login(Request $request)
    {

        if ($request->session()->get('logged_in') === true) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        $client = new \GuzzleHttp\Client();
        // return redirect()->route('home');
        try {            // API call to the external system
            $url = 'http://10.0.224.35:7152/api/UserCont/login/'
            // $url = 'http://localhost:5105/api/UserCont/login/'
                . rawurlencode($request->email) . ',' . rawurlencode($request->password) . ',' . rawurlencode("FFAST");
            $response = $client->post($url, [
                'verify' => false,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);

            // Decode response and extract token
            $responseData = json_decode($response->getBody(), true);
            $token = $responseData['token'] ?? null;
            if ($token) {
                try {
                    $key = "NpSoHmbjMRpX6SjvQ4wWhnmpvVKdgC0djDMBBBHI4jt2uHghZ5lge3FYtfUlmxke7KXY8LbarZ3zUA7ufqn3P8gGoJbEE0wTqBGXfrdktFJ0DuaO36VKcwYP0x8lDsaI";
                    $decoded = JWT::decode($token, new Key($key, 'HS256'));

                    $payload = (array) $decoded;

                    $request->session()->put('emp_no', preg_replace('/\D/', '', (string) ($payload['Emp_No'] ?? '')));
                    $apiEndpointEmployeeProfile = 'http://10.0.224.35:7154/api/employee/getEmployee/' .  rawurlencode(session('emp_no'));
                    $responseEmployeeProfile = Http::withOptions(['verify' => false])
                        ->withHeaders([
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json'
                        ])
                        ->get($apiEndpointEmployeeProfile);
                    $dataProfile = $responseEmployeeProfile->json();

                    $request->session()->regenerate();

                    $request->session()->put('logged_in', true);
                    $request->session()->put('api_token', $token);

                    $request->session()->put('user_email', $payload['email'] ?? $request->email);
                    $request->session()->put('user_name', $dataProfile['firstname'] ?? 'Unknown User');
                    $request->session()->put('role_id', $payload['Role_id'] ?? null);
                    $request->session()->put('user', [
                        'email' => $payload['email'] ?? $request->email,
                        'name' => $dataProfile['firstname'] ?? 'Unknown User',
                    ]);


                    $apiEndpoint = 'http://10.0.224.35:7154/api/employee/getEmployeeEXP/' . rawurlencode((string) session('emp_no'));
                    // $apiEndpoint = 'http://localhost:5111/api/employee/getEmployeeEXP/' . rawurlencode(session('emp_no'));
                    $response = Http::withOptions(['verify' => false])
                        ->withHeaders([
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json'
                        ])
                        ->get($apiEndpoint);
                    // dd($response->json());
                    // $urlDemo = 'http://10.0.224.35:5111/api/fishes';
                    // $responseDemo = Http::withOptions(['verify' => false])
                    //     ->withHeaders([
                    //         'Authorization' => 'Bearer ' . session('api_token'),
                    //         'Content-Type' => 'application/json'
                    //     ])
                    //     ->get($urlDemo);
                    // dd($responseDemo->json());
                    if ($response->successful()) {
                        $data = $response->json();

                        session(['appointmentStatus' => $data['statusType'] ?? null]);

                        session(['designation_id' => $data['designation_role'] ?? null]);
                        session(['division_id' => $data['division'] ?? null]);
                        session(['divisionName' => $data['divisionName'] ?? null]);
                        return redirect()->route('dashboard');

                    } else {
                        session(['appointmentStatus' => null]);
                        session(['designation_id' => null]);
                        session(['division_id' => null]);
                        session(['divisionName' => null]);

                        return redirect()->route('dashboard');
                    }

                    // return redirect()->route('home');

                    // Redirect the user after successful login


                } catch (\Exception $e) {

                    report($e);

                    return back()
                        ->withInput($request->only('email'))
                        ->withErrors([
                            'login' => 'An unexpected error occurred while decoding token.',
                        ]);
                }
            }

            // If token is not received
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'login' => 'Login failed. Token not received.',
                ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'login' => 'Invalid credentials.',
                ]);
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'login' => 'Server error. Please try again later.',
                ]);
        } catch (\Exception $e) {
            report($e);
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'login' => 'An unexpected error occurred. The API failed to respond.',
                ]);
        }
    }

    public function logout(Request $request)
    {
        session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}