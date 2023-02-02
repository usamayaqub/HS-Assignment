<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\uploadfiles;
use Illuminate\Support\Facades\Validator;

class filectrl extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        
        $validateData = Validator::make($req->all(),
        [
        'file' => 'required|mime:jpg|xlsx|png|jpeg|docx',
        ]);
         if($validateData->fails()){

                return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateData->errors()
            ], 401);
        }

        foreach($req->file('file') as $image){
            $file = new uploadfiles;
            $file_ext = $image->getClientOriginalExtension();
            $file_fullname = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $img_name = $file_fullname . '-'     . time() . '.' . $file_ext;
            
            if ($file_ext == 'jpeg' || $file_ext  == 'png' || $file_ext == 'jpg') {
            $img=\Image::make($image);
            $img->save(public_path('/uploads/').($img_name),20);
            }
            if ($file_ext == 'pdf' || $file_ext  == 'docx' || $file_ext == 'xlsx') {
                $image->move(public_path('/uploads/'),($img_name));
                }
            $file->file1 = url('/uploads/').'/'.$img_name;
            $file->user_id=auth()->user()->id;
            $file->save();
        }
        // Image::make($image->getRealPath())->encode(Config::get('img_encode', 'webp'), Config::get('img_quality', 60));
            return response([
            'message' => 'Succefully Uploaded',
            'Status' => 200,
            ],200); 
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
    public function update(Request $req, $id)
    {
        $validateData = Validator::make($req->all(),
        [
        'file' => 'required|mime:jpg|xlsx|png|jpeg|docx',
        ]);
         if($validateData->fails()){

                return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateData->errors()
            ], 401);
        }

        foreach($req->file('file') as $image){
            $file = uploadfiles::find($id);
            $file_ext = $image->getClientOriginalExtension();
            $file_fullname = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $img_name = $file_fullname . '-'     . time() . '.' . $file_ext;
            
            if ($file_ext == 'jpeg' || $file_ext  == 'png' || $file_ext == 'jpg') {
            $img=\Image::make($image);
            $img->save(public_path('/uploads/').($img_name),20);
            }
            if ($file_ext == 'pdf' || $file_ext  == 'docx' || $file_ext == 'xlsx') {
                $image->move(public_path('/uploads/'),($img_name));
                }
            $file->file1 = url('/uploads/').'/'.$img_name;
            $file->user_id=auth()->user()->id;
            $file->update();
        }
            return response([
            'message' => 'Succefully Uploaded',
            'Status' => 201,
            ],201); 

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = uploadfiles::destroy($id);
        return response([
          'message' => 'successfully deleted',
          'file' => $file,
        ]);
    }
}
