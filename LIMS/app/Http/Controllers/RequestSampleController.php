<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RequestSampleController extends Controller
{
    public function index(){
        return view('pages.RequestSample.index');
    }

    public function data(){
        $response = Http::get('http://localhost:5198/api/RequestPending');

        if ($response->failed()) {
            return response()->json([
                'message' => 'Failed to retrieve pending requests.'
            ], $response->status());
        }

        return response()->json($response->json());
    }

    public function view($id){
        $response = Http::get(
            "http://localhost:5198/api/RequestPending/{$id}"
        );

        if ($response->failed()) {
            return response()->json([
                'message' => 'Failed to retrieve request details.'
            ], $response->status());
        }

        return response()->json($response->json());
    }

    public function accept($id){
        try {
            $response = Http::timeout(15)->post(                       // ← post, not get
                "http://localhost:5198/api/RequestPending/{$id}/accept",
                [
                    'userId'   => (int) session('emp_no'),
                    'userName' => trim(session('fname') . ' ' . session('lname')),
                ]
            );
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json([
                'message' => 'The laboratory service is not available right now.'
            ], 503);
        }

        if ($response->failed()) {
            return response()->json([
                'message' => $response->json('message') ?? 'Failed to accept request.'
            ], $response->status());
        }

        return response()->json($response->json());
    }
}




