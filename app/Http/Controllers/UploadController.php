<?php

namespace App\Http\Controllers;

use App\Http\Requests\CsvRequest;
use App\Imports\InformationImport;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class UploadController extends Controller
{
    /**
     * @throws Throwable
     */
    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {
        DB::table('information')->truncate();
        //        This db truncate is for testing purposes do not full in the database
        $validator = Validator::make($request->all(), [
           'file' => 'required|file|mimes:csv,txt'
        ]);
        if ($validator->fails()){
            return response()->json([
                'errors' => true,
                'message' => $validator->getMessageBag()
            ]);
        }
        $file = $request->file('file');
        $data = Information::calculate($file);

        if ($request->save_to_db) {
            try {
                Excel::import(new InformationImport, $file);
            } catch (Throwable $e){
                throw $e;
            }
        }

        return response()->json([
           'success' => true,
           'data' => $data,
            'message' => $request->save_to_db ? 'Data was saved to Database' : 'Data was only used to display'
        ]);
    }
}
