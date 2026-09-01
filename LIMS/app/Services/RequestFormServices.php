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
}