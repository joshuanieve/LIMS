<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RequestFormServices;

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
        $validated = $request->validate([
            // 'LastName' => 'required|string|max:100',
            // 'FirstName' => 'required|string|max:100',
            // 'MiddleInitial' => 'nullable|string|max:10',
            // 'Email' => 'required|email',
            // 'DivisionSection' => 'required|string|max:150',

            'LabAna' => 'required|string',
            'DateTimeRelease' => 'required|date',

            'SamType' => 'required|string',
            'SamTypeSpecify' => 'nullable|string',

            'SampRet' => 'required|string',

            'Subinfo1' => 'required|in:0,1',
            'Subinfo2' => 'required|in:0,1',
            'Subinfo2Specify' => 'nullable|string',

            'Subinfo3' => 'required|in:0,1',
            'Subinfo4' => 'required|in:0,1',
            'Subinfo4Specify' => 'nullable|string',

            'Instruction' => 'nullable|string',

            'samples' => 'required|array|min:1',

            'samples.*.sample' => 'required|string',
            'samples.*.customer_sample_code' => 'required|string',
            'samples.*.date_collected' => 'required|date',
            'samples.*.place_collected' => 'required|string',
            'samples.*.testarray' => 'required|string',
        ]);

        $result = $this->requestFormServices->createRequest($validated);
        return back()->with('success', 'Laboratory request submitted successfully.');
    }
}