<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RequestFormServices
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.lims_api.url');
    }

    public function getAnalysisList()
    {
        return Http::get("{$this->baseUrl}/api/AnalysisList")
            ->throw()
            ->json();
    }

    public function createRequest(array $data){
        $response = Http::post(
            "{$this->baseUrl}/api/RequestForm",
            $data
        );

        logger()->info('LIMS RequestForm API Response', [
            'status' => $response->status(),
            'successful' => $response->successful(),
            'body' => $response->body(),
            'json' => $response->json(),
        ]);

        return $response
            ->throw()
            ->json();
        }
}