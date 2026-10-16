<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LaboratoryRecordsController extends Controller
{
    public function index()
    {
        // TEMPORARY placeholder data until this is wired to a real
        // LIMS_API endpoint (one row per RFA, aggregating its samples).
        // $records = [
        //     ['rfaNo' => 'Ref. #0412', 'customer' => 'Dela Cruz, Juan P.', 'division' => 'Fisheries Resource Management', 'sampleType' => 'Fishery Resources / Products', 'labNoRange' => '2026-5982 - 5984', 'labNoMeta' => '3 samples · 12 analyses', 'received' => 'Oct 1, 2026', 'released' => 'Oct 9, 2026', 'turnaround' => '8 days', 'turnaroundNote' => 'On time', 'warning' => false, 'status' => 'released'],
        //     ['rfaNo' => 'Ref. #0411', 'customer' => 'Santos, Maria L.', 'division' => 'Aquaculture Division', 'sampleType' => 'Water', 'labNoRange' => '2026-5977 - 5981', 'labNoMeta' => '5 samples · 15 analyses', 'received' => 'Sep 30, 2026', 'released' => 'Oct 10, 2026', 'turnaround' => '10 days', 'turnaroundNote' => 'Late by 2 days', 'warning' => true, 'status' => 'released'],
        //     ['rfaNo' => 'Ref. #0410', 'customer' => 'Reyes, Ana C.', 'division' => 'Post-Harvest Division', 'sampleType' => 'Feeds', 'labNoRange' => '2026-5975 - 5976', 'labNoMeta' => '2 samples · 4 analyses', 'received' => 'Sep 29, 2026', 'released' => null, 'turnaround' => 'Due Oct 14', 'turnaroundNote' => '1 day left', 'warning' => false, 'status' => 'review'],
        //     ['rfaNo' => 'Ref. #0409', 'customer' => 'Lopez, Mark T.', 'division' => 'Island Fisheries Division', 'sampleType' => 'Sediments', 'labNoRange' => '2026-5971 - 5974', 'labNoMeta' => '4 samples · 8 analyses', 'received' => 'Sep 28, 2026', 'released' => null, 'turnaround' => 'Due Oct 16', 'turnaroundNote' => '5 of 8 encoded', 'warning' => false, 'status' => 'progress'],
        //     ['rfaNo' => 'Ref. #0408', 'customer' => 'Garcia, Liza M.', 'division' => 'Fisheries Resource Management', 'sampleType' => 'Water', 'labNoRange' => '2026-5965 - 5970', 'labNoMeta' => '6 samples · 18 analyses', 'received' => 'Sep 25, 2026', 'released' => 'Oct 3, 2026', 'turnaround' => '8 days', 'turnaroundNote' => 'On time', 'warning' => false, 'status' => 'released'],
        //     ['rfaNo' => 'Ref. #0407', 'customer' => 'Bautista, Carlo R.', 'division' => 'Aquaculture Division', 'sampleType' => 'Fishery Resources / Products', 'labNoRange' => '2026-5964', 'labNoMeta' => '1 sample · 8 analyses', 'received' => 'Sep 24, 2026', 'released' => 'Oct 7, 2026', 'turnaround' => '13 days', 'turnaroundNote' => 'Late by 2 days', 'warning' => true, 'status' => 'released'],
        //     ['rfaNo' => 'Ref. #0406', 'customer' => 'Mendoza, Rica A.', 'division' => 'Post-Harvest Division', 'sampleType' => 'Feeds', 'labNoRange' => '2026-5962 - 5963', 'labNoMeta' => '2 samples · 5 analyses', 'received' => 'Sep 23, 2026', 'released' => 'Sep 30, 2026', 'turnaround' => '7 days', 'turnaroundNote' => 'On time', 'warning' => false, 'status' => 'released'],
        //     ['rfaNo' => 'Ref. #0405', 'customer' => 'Navarro, Paolo S.', 'division' => 'Island Fisheries Division', 'sampleType' => 'Water', 'labNoRange' => '2026-5959 - 5961', 'labNoMeta' => '3 samples · 9 analyses', 'received' => 'Sep 22, 2026', 'released' => 'Sep 30, 2026', 'turnaround' => '8 days', 'turnaroundNote' => 'On time', 'warning' => false, 'status' => 'released'],
        //     ['rfaNo' => 'Ref. #0404', 'customer' => 'Fernandez, Grace T.', 'division' => 'Fisheries Resource Management', 'sampleType' => 'Sediments', 'labNoRange' => '2026-5955 - 5958', 'labNoMeta' => '4 samples · 10 analyses', 'received' => 'Sep 21, 2026', 'released' => 'Sep 29, 2026', 'turnaround' => '8 days', 'turnaroundNote' => 'On time', 'warning' => false, 'status' => 'released'],
        //     ['rfaNo' => 'Ref. #0403', 'customer' => 'Villanueva, Noel D.', 'division' => 'Aquaculture Division', 'sampleType' => 'Fishery Resources / Products', 'labNoRange' => '2026-5952 - 5954', 'labNoMeta' => '3 samples · 11 analyses', 'received' => 'Sep 19, 2026', 'released' => null, 'turnaround' => 'Due Oct 15', 'turnaroundNote' => '3 of 11 encoded', 'warning' => false, 'status' => 'progress'],
        //     ['rfaNo' => 'Ref. #0402', 'customer' => 'Cruz, Beatriz S.', 'division' => 'Post-Harvest Division', 'sampleType' => 'Water', 'labNoRange' => '2026-5949 - 5951', 'labNoMeta' => '3 samples · 9 analyses', 'received' => 'Sep 18, 2026', 'released' => 'Sep 26, 2026', 'turnaround' => '8 days', 'turnaroundNote' => 'On time', 'warning' => false, 'status' => 'released'],
        //     ['rfaNo' => 'Ref. #0401', 'customer' => 'Aquino, Ramon P.', 'division' => 'Island Fisheries Division', 'sampleType' => 'Feeds', 'labNoRange' => '2026-5946 - 5948', 'labNoMeta' => '2 samples · 6 analyses', 'received' => 'Sep 17, 2026', 'released' => null, 'turnaround' => 'Due Oct 15', 'turnaroundNote' => '2 days left', 'warning' => false, 'status' => 'review'],
        //     ['rfaNo' => 'Ref. #0400', 'customer' => 'Torres, Ella M.', 'division' => 'Fisheries Resource Management', 'sampleType' => 'Sediments', 'labNoRange' => '2026-5943 - 5945', 'labNoMeta' => '3 samples · 7 analyses', 'received' => 'Sep 16, 2026', 'released' => 'Sep 25, 2026', 'turnaround' => '9 days', 'turnaroundNote' => 'Late by 1 day', 'warning' => true, 'status' => 'released'],
        //     ['rfaNo' => 'Ref. #0399', 'customer' => 'Domingo, Victor L.', 'division' => 'Aquaculture Division', 'sampleType' => 'Water', 'labNoRange' => '2026-5940 - 5942', 'labNoMeta' => '3 samples · 10 analyses', 'received' => 'Sep 15, 2026', 'released' => null, 'turnaround' => 'Due Oct 17', 'turnaroundNote' => '6 of 10 encoded', 'warning' => false, 'status' => 'progress'],
        //     ['rfaNo' => 'Ref. #0398', 'customer' => 'Ramos, Sofia K.', 'division' => 'Post-Harvest Division', 'sampleType' => 'Fishery Resources / Products', 'labNoRange' => '2026-5936 - 5939', 'labNoMeta' => '4 samples · 13 analyses', 'received' => 'Sep 12, 2026', 'released' => 'Sep 20, 2026', 'turnaround' => '8 days', 'turnaroundNote' => 'On time', 'warning' => false, 'status' => 'released'],
        // ];

        return view('pages.LaboratoryRecords.index');
    }

    public function data(){
        $response = Http::get('http://localhost:5198/api/Results');

        if ($response->failed()) {
            return response()->json([
                'message' => 'Failed to Fetch Laboratory Records.'
            ], $response->status());
        }

        return response()->json($response->json());
    }
}
