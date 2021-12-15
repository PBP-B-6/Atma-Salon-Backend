<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Testimoni;
use Validator;

class TestimoniController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $testis = Testimoni::where('idUser', $user->id)->first();

        if (count($testis) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $testis
            ], 200);
        } 

        return response([
            'message' => 'Empty',
            'data' => null
        ],400); 
    }

    public function show($id)
    {
        $testi = Testimoni::where('idUser', $id)->get(); 

        if(!is_null($testi)) {
            return response([
                'message' => 'Retrieve Testimoni Success',
                'data' => $testi
            ], 200); 
        } 

        return response([
            'message' => 'Testimoni Not Found',
            'data' => null
        ], 404); 
    }

    public function store(Request $request)
    {
        $storeData=$request->all(); 
        
        $validate=Validator::make($storeData, [
            'testimoni' => 'required',
        ]);
        
        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        // $storeData['idUser'] = $request->idUser;

        $testi=Testimoni::create($storeData);
        return response([
            'message' => 'Add Testimoni Success',
            'data' => $testi
        ], 200);
    }

    public function destroy($id)
    {
        $testi = Testimoni::where('idUser', $id)->first();

        if(is_null($testi)) {
            return response([
                'message' => 'Testimoni Not Found',
                'data' => null
            ], 404);
        }

        if($testi->delete()) {
            return response([
                'message' => 'Delete Testimoni Success',
                'data' => $testi
            ], 200); 
        }

        return response([
            'message' => 'Delete Testimoni Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $testi=Testimoni::where('idUser', $id)->first();
        if(is_null($testi)) {
            return response([
                'message' => 'Testimoni Not Found',
                'data' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'testimoni' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $testi->testimoni=$updateData['testimoni'];

        if($testi->save()) {
            return response([
                'message' => 'Update Testimoni Success',
                'data' => $testi
            ], 200);
        }

        return response([
            'message' => 'Update Testimoni Failed',
            'data' => null,
        ], 400); 
    }
}
