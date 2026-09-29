<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RequestFormServices;
use Illuminate\Support\Facades\Log;

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

        return view('RequestforAnalysis.requestform', compact('analysisList'));

        // return view('requestform');
    }

    public function storeRequestForm(Request $request)
    {
        // VALIDATE THE INPUTS
        $validated = $request->validate([
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

        ], [

            // LABORATORY INFORMATION
            'LabAna.required' =>'Please select a laboratory analysis.',
            'DateTimeRelease.required' => 'Please enter the expected release date.',
            'DateTimeRelease.date' => 'Please enter a valid release date.',

            // SAMPLE INFORMATION
            'SamType.required' => 'Please select a sample type.',
            'SampRet.required' => 'Please select a sample retrieval option.',

            // SUBMISSION INFORMATION
            'Subinfo1.required' => 'Please answer Submission Information 1.',
            'Subinfo2.required' => 'Please answer Submission Information 2.',
            'Subinfo3.required' => 'Please answer Submission Information 3.',
            'Subinfo4.required' => 'Please answer Submission Information 4.',

            // SAMPLE TABLE
            'samples.required' => 'Please add at least one sample.',
            'samples.*.sample.required' => 'Please enter the test sample name.',
            'samples.*.customer_sample_code.required' => 'Please enter the customer sample code.',
            'samples.*.date_collected.required' => 'Please enter the date collected.',
            'samples.*.date_collected.date' => 'Please enter a valid collection date.',
            'samples.*.place_collected.required' => 'Please enter the place collected.',
            'samples.*.testarray.required' => 'Please select at least one analysis for each sample.',
        ]);

        // CONVERT YES OR NO TO TRUE OR FALSE TO MATCH BOOLEAN IN BACKEND
        $validated['Subinfo1'] = $validated['Subinfo1'] == '1';
        $validated['Subinfo2'] = $validated['Subinfo2'] == '1';
        $validated['Subinfo3'] = $validated['Subinfo3'] == '1';
        $validated['Subinfo4'] = $validated['Subinfo4'] == '1';

        // CONVERT THE NAMES OF LIST TO MATCH THE NAME IN BACKEND
        $validated['samples'] = collect($validated['samples'])
        ->map(function ($sample) {
            return [
                'sample' => $sample['sample'],
                'customerSampleCode' => $sample['customer_sample_code'],
                'dateCollected' => $sample['date_collected'],
                'placeCollected' => $sample['place_collected'],
                'testarray' => $sample['testarray'],
            ];
        })
        ->values()
        ->all();

        $lastName = trim(session('lname', ''));
        $firstName = trim(session('fname', ''));
        $middleName = trim(session('mname', ''));

        $middleInitial = $middleName !== ''
            ? strtoupper(substr($middleName, 0, 1)) . '.'
            : '';

        $validated['CustomerName'] = trim(
            $lastName . ', ' .
            $firstName . ' ' .
            $middleInitial
        );

        $validated['EmailAddress'] = session('user_email');
        $validated['DivisionSection'] = session('division_id');

        try {
            $result = $this->requestFormServices->createRequest($validated);

            return response()->json([
                'success' => true,
                'message' => 'Laboratory request submitted successfully.',
                'data' => $result
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }
}