<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Validator;

class OrderController extends Controller
{
    public function index($id)
    {
        // $user = Auth::user();

        $orders = Order::where('idUser', $id)->get();

        if (count($orders) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $orders
            ], 200);
        } 

        return response([
            'message' => 'Empty',
            'data' => null
        ],400); 
    }

    // public function show($id)
    // {
    //     $user = Auth::user();
    //     $order = Order::find($id); 

    //     if(!is_null($order) && $order->idUser == $user->id) {
    //         return response([
    //             'message' => 'Retrieve Order Success',
    //             'data' => $order
    //         ], 200); 
    //     } 

    //     return response([
    //         'message' => 'Order Not Found',
    //         'data' => null
    //     ], 404); 
    // }

    public function store(Request $request, $id)
    {
        // $user = Auth::user();

        $storeData=$request->all(); 
        $validate=Validator::make($storeData, [
            'lokasiSalon' => 'required',
            'namaPemesan' => 'required|max:64',
            'noTelp' => 'required|numeric|digits_between:10,13|phoneRules',
            'modelRambut' => 'required',
            'warnaRambut' => 'required',
            'totalHarga' => 'required',
            'statusPembayaran' => 'required',
        ]);
        
        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $storeData['idUser'] = $id;

        $order=Order::create($storeData);
        return response([
            'message' => 'Add Order Success',
            'data' => $order
        ], 200);
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if(is_null($order)) {
            return response([
                'message' => 'Order Not Found',
                'data' => null
            ], 404);
        }

        if($order->delete()) {
            return response([
                'message' => 'Delete Order Success',
                'data' => $order
            ], 200); 
        }

        return response([
            'message' => 'Delete Order Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $order=Order::find($id);
        if(is_null($order)) {
            return response([
                'message' => 'Order Not Found',
                'data' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'lokasiSalon' => 'required',
            'namaPemesan' => 'required|max:64',
            'noTelp' => 'required|numeric|digits_between:10,13|phoneRules',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $order->lokasiSalon=$updateData['lokasiSalon'];
        $order->namaPemesan=$updateData['namaPemesan'];
        $order->noTelp=$updateData['noTelp'];

        if($order->save()) {
            return response([
                'message' => 'Update Order Success',
                'data' => $order
            ], 200);
        }

        return response([
            'message' => 'Update Order Failed',
            'data' => null,
        ], 400); 
    }
}
