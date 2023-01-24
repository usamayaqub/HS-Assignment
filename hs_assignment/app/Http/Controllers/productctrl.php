<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

class productctrl extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::with('owner')->latest()->get();
        return response([
                   'message' => 'All Products have been fetched!',
                  'product_detail' => $product,                  
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
       //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required',
                    ]);

        $product = new Product;
        $product->product_name  =  $req->name;
        $product->product_desc  =  $req->description;
        $product->product_price =  $req->price;
        $file = $req->image;
        $img_ext = $file->getClientOriginalExtension();
        $img_fullname = "Product_" . rand(123456,999999). "." . $img_ext;
        $img_dest_path = public_path('/uploads/');
        $file->move($img_dest_path,$img_fullname);
        $product->product_image = $img_fullname;
        $product->user_id =  auth()->user()->id;
        $product->save();  
                  
        return response([
               'product_detail' => $product,
               'message' => 'Product Successfully Saved',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $myreq, $id)
    {
        $myreq->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required',
                    ]);

        $pt = product::find($id);
        $pt->product_name = $myreq->name;
        $pt->product_desc = $myreq->description;
        $pt->product_price = $myreq->price;
        $pt->user_id =  auth()->user()->id;
        $file = $myreq->image;
        $img_ext = $file->getClientOriginalExtension();
        $img_fullname = "Product_" . rand(123456,999999). "." . $img_ext;
        $img_dest_path = public_path('/uploads/');
        $file->move($img_dest_path,$img_fullname);
        $pt->product_image = $img_fullname;
        $pt->update();
        return response([
               'product' => $pt,
               'message' => 'Product Updated Successfully!!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        $product= product::destroy($req->id);
        return response([
              'message' => 'Product Successfully Deleted!',
        ]);
    }
}
