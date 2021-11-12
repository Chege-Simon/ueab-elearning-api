<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File; 

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Video::all();
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
            'title'=>'required|unique:videos',
            'description'=>'required',
            'isFile'=>'required',
        ]);
        if($request->has('video'))
        {
            $allowedfileExtension=['mkv','mp4','mp3'];
            $file = $request->file('video');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $check=in_array($extension,$allowedfileExtension);
            //dd($check);
            if($check)
            {
                $filename = $request->video->store('films', 'public');
                Video::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'isFile' => $request->isFile,
                    'label' => $filename
                ]);
                $response = [
                    "FileName" => $filename,
                    "Message" => "Film Saved Successfully."
                ];
                return response($response, 201);
            }else
                {
                    $response = [
                        "FileName" => $filename,
                        "Error" => "Invalid Film Format"
                    ];
                    return response($response, 201);
                }
        }else
            {
                Video::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'isFile' => $request->isFile,
                    'youtubeUrl' => $request->youtubeUrl,
                ]);
                // Video::create($request->all());
                
                $response = [
                    "Message" => "Film Saved Successfully."
                ];
                return response($response, 201);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $video = Video::findOrFail($id);

        $response = [
            "data" => $video,
        ];
        return response($response, 201);    }

    /**
     * Download the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $video = Video::findOrFail($id);
        return response()->download(public_path("storage/".$video->label));
        // return Storage::download(public_path("videos/"), $video->label);
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
        $video = Video::findOrFail($id);
        $this->validate($request, [
            'title'=>'required|unique:videos',
            'description'=>'required',
            'isFile'=>'required',
        ]);
        if($request->hasFile('video'))
        {
            $allowedfileExtension=['mkv','mp4','mp3'];
            $file = $request->file('video');
            $filename = $file->getClientOriginalName().Carbon\Carbon::now();
            $extension = $file->getClientOriginalExtension();
            $check=in_array($extension,$allowedfileExtension);
            //dd($check);
            if($check)
            {
                $filename = $request->video->store('films', 'public');
                $video->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'isFile' => $request->isFile,
                    'label' => $filename
                ]);
                $response = [
                    "FileName" => $filename,
                    "Message" => "Film Edited Successfully."
                ];
                return response($response, 201);
            }else
                {
                    $response = [
                        "FileName" => $filename,
                        "Error" => "Invalid Film Format"
                    ];
                    return response($response, 201);
                }
        }else
            {
                $video->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'isFile' => $request->isFile,
                    'youtubeUrl' => $request->youtubeUrl,
                ]);
                // $video->update($request->all());
                
                $response = [
                    "Message" => "Film Edited Successfully."
                ];
                return response($response, 201);
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $video = Video::findOrFail($id);
        $video_path = public_path("storage/".$video->label);
        if (File::exists($video_path)) {
            //File::delete($video_path);
            if(unlink($video_path)){
                $video->delete();
                $response = [
                    "path" => $video_path,
                    "Message" => "Film Deleted Successfully."
                ];
                return response($response, 201);
            }else {
                $response = [
                    "path" => $video_path,
                    "Error" => "Error Occured Couldn't Delete Film."
                ];
                return response($response, 201);
            }
        }
        $response = [
            "path" => $video_path,
            "Error" => "Couldn't Find Film To Delete."
        ];
        return response($response, 201);
    }
}
