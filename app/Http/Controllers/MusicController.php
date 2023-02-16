<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\Type\ObjectType;
use wapmorgan\Mp3Info\Mp3Info;

class MusicController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'src' => 'required|mimetypes:audio/ogg, audio/mp3',
            'demo' => 'mimetypes:audio/ogg, audio/mp3',
            'title' => 'required|string',
            'cover' => 'required|mimetypes:image/jpeg, image/jpg',
            'artist_id' => 'array',
            'artist_id.*' => 'integer',
            'feat_id' => 'array',
            'feat_id.*' => 'integer',
            'category_id' => 'array',
            'category_id.*' => 'integer',
            'top' => 'string',
        ]);
        // return $request;
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $file = $request->file('src');
        $filename = date('YmdHi') . $file->getClientOriginalName();

        $info = pathinfo($filename);
        $filename = $info['filename'] . '.' . "mp3";

        $file->move(public_path('Voice'), $filename);
        $data['src'] = $filename;


        $voiceFile = $request->file('demo');
        $filename = date('YmdHi') . $voiceFile->getClientOriginalName();

        $info = pathinfo($filename);
        $filename = $info['filename'] . '.' . "mp3";

        $voiceFile->move(public_path('Audio'), $filename);
        $data['demo'] = $filename;

        $file = $request->file('cover');
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('Image'), $filename);
        $data['cover'] = $filename;

        $music = Music::create([
            'src' => $data['src'],
            'demo' => $data['demo'],
            'title' => $request->title,
            'cover' => $data['cover'],
            // 'artist_id' => $request->artist_id,
            // 'feat_id' => $request->feat_id,
            // 'category_id' => $request->category_id,
            'top' => $request->top,
        ]);
        if ($music) {
            $music->artists()->attach($request->artist_id);
            $music->feats()->attach($request->feat_id);
            $music->categories()->attach($request->category_id);
        }
        return response()->json(['success' => true], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $music = Music::with(['artists', 'feats', 'categories'])->where('id', $id)->get();

        // $audio = new Mp3Info("audio/202301310759-1517740286_103101696.mpeg", true);

        // return $audio;
        //         $audio = new \wapmorgan\Mp3Info\Mp3Info($fileName, true);
        // $audio->duration \\ duration in seconds
        // if($music) {
        //     return response()->json(['success' => false], 404);
        // }
        return response()->json(['result' => $music, 'success' => true], 200);
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
    public function update(Request $request, $id)
    {
        //  return $request;
        $validator = Validator::make($request->all(), [
            'src' => 'mimetypes:audio/ogg, audio/mp3',
            'demo' => 'mimetypes:audio/ogg, audio/mp3',
            'title' => 'string',
            'cover' => 'mimetypes:image/jpeg, image/jpg',
            'artist_id' => 'array',
            'artist_id.*' => 'integer',
            'feat_id' => 'array',
            'feat_id.*' => 'integer',
            'category_id' => 'array',
            'category_id.*' => 'integer',
            'top' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $data = Music::where(['id' => $id])->get()->first();

        if ($request->src) {

           
            unlink('Voice/' . $data->src);
            
            $file = $request->file('src');
            $filename = date('YmdHi') . $file->getClientOriginalName();

            $info = pathinfo($filename);
            $filename = $info['filename'] . '.' . "mp3";

            $file->move(public_path('Voice'), $filename);
            $data['src'] = $filename;
        }

        if ($request->demo) {

            unlink('Audio/' . $data->demo);
            $voiceFile = $request->file('demo');
            $filename = date('YmdHi') . $voiceFile->getClientOriginalName();

            $info = pathinfo($filename);
            $filename = $info['filename'] . '.' . "mp3";

            $voiceFile->move(public_path('Audio'), $filename);
            $data['demo'] = $filename;
        }
        if ($request->title) {
            $data->title = $request->title;
        }

        if ($request->title) {
            $file = $request->file('cover');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('Image'), $filename);
            $data['cover'] = $filename;
        }
        if ($request->top == false)
            $data->top = 0;
        if ($request->top) {
            $data->top = $request->top;
        }
        // $music = Music::where('id', $id)->update([
        //     'src' => $data['src'],
        //     'demo' => $data['demo'],
        //     'title' => $request->title,
        //     'cover' => $request->cover,
        //     // 'artist_id' => $request->artist_id,
        //     // 'feat_id' => $request->feat_id,
        //     'category_id' => $request->category_id,
        //     'top' => $request->top,
        // ]);
        $data->save();


        if ($data) {
            $data->artists()->sync($request->artist_id);
            $data->feats()->sync($request->feat_id);
            $data->categories()->sync($request->category_id);
        }
        return response()->json(['success' => true], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $find = Music::with(['artists', 'feats', 'categories'])->where('id', $id)->delete();
        if (!$find) {
            return response()->json(['success' => false], 404);
        }
        return response()->json(['success' => true], 200);
    }
}
