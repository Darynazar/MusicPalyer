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
            'src' => 'required|string',
            'demo' => 'string',
            'title' => 'required|string',
            'cover' => 'required|string',
            'artist_id' => 'array',
            'artist_id.*' => 'integer',
            'feat_id' => 'array',
            'feat_id.*' => 'integer',
            'category_id' => 'array',
            'category_id.*' => 'integer',
            'top' => 'string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $audio = new \wapmorgan\Mp3Info\Mp3Info("Voice/$request->src", true);
        $demo= new \wapmorgan\Mp3Info\Mp3Info("Audio/$request->demo", true);

        $music = Music::create([
            'src' => $request->src,
            'demo' => $request->demo,
            'title' => $request->title,
            'cover' => $request->cover, 
            'musicDuration' => $audio->duration,
            'demoDuration' => $demo->duration,
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
        $validator = Validator::make($request->all(), [
            'src' => 'string',
            'demo' => 'string',
            'title' => 'string',
            'cover' => 'string',
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
            $data->src = $request->src;
        }

        if ($request->demo) {
            $data->demo = $request->demo;
        }
        if ($request->title) {
            $data->title = $request->title;
        }

        if ($request->cover) {
            $data->cover = $request->cover;
        }
        if ($request->top == false)
            $data->top = 0;
        if ($request->top) {
            $data->top = $request->top;
        }
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
