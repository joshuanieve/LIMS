<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JobRoutingController extends Controller
{
    public function index(){
        return view('JobRouting.jobrouting');
    }

    public function data(){
        $response = Http::get('http://localhost:5198/api/JobRouting');

        if ($response->failed()) {
            return response()->json([
                'message' => 'Failed to retrieve samples list.'
            ], $response->status());
        }

        return response()->json($response->json());
    }

    // public function view($id){
    //     $response = Http::get(
    //         "http://localhost:5198/api/RequestPending/{$id}"
    //     );

    //     if ($response->failed()) {
    //         return response()->json([
    //             'message' => 'Failed to retrieve request details.'
    //         ], $response->status());
    //     }

    //     return response()->json($response->json());
    // }
}




