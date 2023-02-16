<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UploaderController extends Controller
{
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'src' => 'mimetypes:audio/ogg, audio/mp3',
            'demo' => 'mimetypes:audio/ogg, audio/mp3',
            'cover' => 'mimetypes:image/jpeg, image/jpg',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        if($request->src)
        {

        $file = $request->file('src');
        $filename = date('YmdHi') . $file->getClientOriginalName();

        $info = pathinfo($filename);
        $filename = $info['filename'] . '.' . "mp3";

        $file->move(public_path('Voice'), $filename);
        $data['src'] = $filename;
        }
        else $data['src'] = [];


        if ($request->demo) {
            $voiceFile = $request->file('demo');
            $filename = date('YmdHi') . $voiceFile->getClientOriginalName();

            $info = pathinfo($filename);
            $filename = $info['filename'] . '.' . "mp3";

            $voiceFile->move(public_path('Audio'), $filename);
            $data['demo'] = $filename;
        }
        else  $data['demo']=[];
        if($request->cover)
        {
            $file = $request->file('cover');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('Image'), $filename);
            $data['cover'] = $filename;
        } 
        else $data['cover']=[];




        return response()->json([
            'src' => $data['src'],
            'demo' => $data['demo'],
            'cover' => $data['cover']
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'src' => 'mimetypes:audio/ogg, audio/mp3',
            'demo' => 'mimetypes:audio/ogg, audio/mp3',
            'cover' => 'mimetypes:image/jpeg, image/jpg',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $data = Music::where(['id' => $id])->get()->first();

        if ($request->src) {
            // unlink('Voice/' . $data->src);
            $file = $request->file('src');
            $filename = date('YmdHi') . $file->getClientOriginalName();

            $info = pathinfo($filename);
            $filename = $info['filename'] . '.' . "mp3";

            $file->move(public_path('Voice'), $filename);
            $data['src'] = $filename;
        }
        else  $data['src']=[];

        if ($request->demo) {

            // unlink('Audio/' . $data->demo);
            $voiceFile = $request->file('demo');
            $filename = date('YmdHi') . $voiceFile->getClientOriginalName();

            $info = pathinfo($filename);
            $filename = $info['filename'] . '.' . "mp3";

            $voiceFile->move(public_path('Audio'), $filename);
            $data['demo'] = $filename;
        }else  $data['demo']=[];

        if($request->cover)
        {
            // unlink('Image/' . $data->cover);
            $file = $request->file('cover');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('Image'), $filename);
            $data['cover'] = $filename;
        } else $data['cover']=[];


        return response()->json([
            'src' => $data['src'],
            'demo' => $data['demo'],
            'cover' => $data['cover']
        ]);
    }
}
