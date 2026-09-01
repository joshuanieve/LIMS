<?php

namespace App\Http\Controllers;

use App\Services\RequestFormServices;
use Illuminate\Http\Request;

class RequestFormController extends Controller
{
    protected RequestFormServices $requestFormServices;

    public function __construct(RequestFormServices $requestFormServices)
    {
        $this->requestFormServices = $requestFormServices;
    }

    public function index()
    {
        $analysisList = $this->requestFormServices->getAnalysisList();

        return view('requestform', compact('analysisList'));
    }

    public function storeRequestForm(Request $request)
    {
        dd($request->all());
    }
}