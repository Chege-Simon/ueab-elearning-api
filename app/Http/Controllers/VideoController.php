<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
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
        return "video saved";
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
                $filename = $request->video->store('videos', 'public');
                Video::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'isFile' => $request->isFile,
                    'label' => $filename
                ]);
            }
            return "video saved";
        }
        else
        {
            Video::create([
                'title' => $request->title,
                'description' => $request->description,
                'isFile' => $request->isFile,
                'youtubeUrl' => $request->youtubeUrl,
            ]);
            // Video::create($request->all());
            return "video saved";
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
        return $video;
    }

    /**
     * Download the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $video = Video::findOrFail($id);
        return response()->download(public_path("videos/".$video->label));
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
        return "video saved";
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
                $filename = $request->video->store('videos', 'public');
                $video->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'isFile' => $request->isFile,
                    'label' => $filename
                ]);
            }
            return "video saved";
        }
        else
        {
            $video->update([
                'title' => $request->title,
                'description' => $request->description,
                'isFile' => $request->isFile,
                'youtubeUrl' => $request->youtubeUrl,
            ]);
            // $video->update($request->all());
            return "video saved";
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
        $video_path = public_path("videos/".$video->label);
        $deleted = false;
        if (File::exists($video_path)) {
            //File::delete($video_path);
            if(unlink($video_path)){
                $deleted = true;
            }
            // unlink($video_path);
        }
        $video->delete();
        return "video deleted = ".$deleted;
    }
}
