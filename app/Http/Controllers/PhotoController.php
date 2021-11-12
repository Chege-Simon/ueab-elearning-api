<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File; 
use Carbon\Carbon;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [
            "images" => Photo::all(),
        ];
        return response($response, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'photos'=>'required',
        ]);
        $response;
        if($request->has('photos'))
        {
            $allowedfileExtension=['jpeg','jpg','png','gif','svg'];
            $files = $request->file('photos');
            // dd($files);
            // $response = [
            //     "file" => $files,
            // ];
            // return response($response, 203);
            foreach($files as $file)
            {
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $check=in_array($extension,$allowedfileExtension);
                //dd($check);
                //  $response = [
                //     "photos" =>$files,
                //     "filename" => $filename,
                //     "extension" => $extension,
                //     "Error" => "Invalid Images"
                // ];
                // return response($response, 201);
                if($check)
                {
                    $filename = $file->store('images', 'public');
                    Photo::create([
                    'label' => $filename
                    ]);  
                    $response = [
                        "Extension" => $extension,
                        "FileName" => $filename,
                        'Message' => "Image saved Successfully."
                    ];
                }
                else{
                    $response = [
                        "Extension" => $extension,
                        "FileName" => $filename,
                        "Error" => "Invalid Image Format"
                    ];
                }
            }
        }else{
            $response = [
                "Error" => "No Images."
            ];
        }
        return response($response, 201);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photo = Photo::findOrFail($id);
        $image_path = public_path("storage/".$photo->label);
        // dd($image_path);
        if (File::exists($image_path)) {
            //File::delete($image_path);
            if(unlink($image_path)){
                $photo->delete();
                $response = [
                    "path" => $image_path,
                    "Message" => "Image Deleted Successfully."
                ];
                return response($response, 201);
            }else {
                $response = [
                    "path" => $image_path,
                    "Error" => "Error Occured Couldn't Delete Image."
                ];
                return response($response, 201);
            }
        }
        $response = [
            "path" => $image_path,
            "Error" => "Couldn't Find Image To Delete."
        ];
        return response($response, 201);
    }
}
