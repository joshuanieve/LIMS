<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class AnalysisController extends Controller
{
    public function data()
    {
        $response = Http::get(
            'http://localhost:5198/api/AnalysisList'
        );

        if ($response->failed()) {
            return response()->json([
                'message' => 'Failed to retrieve analysis list.'
            ], $response->status());
        }

        return response()->json($response->json());
    }
}