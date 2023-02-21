<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Category;
use App\Models\Feat;
use App\Models\Like;
use App\Models\Music;
use App\Models\Visit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\Jalalian;

class UserController extends Controller
{

    public function index()
    {
        $music = Music::with(['artists', 'feats', 'categories'])->paginate(10);
        return response()->json(['result' => $music, 'success' => true], 200);
    }

    public function show($id)
    {
        
        try {
            $music = Music::findOrFail($id);
            $play = $music->play;
            $visit = Visit::where('user_ip', \request()->ip())->where('music_id', $id)->exists() ? true : null;
            if ($visit == null) {
                $newVisit = Visit::create([
                    'user_ip' => request()->ip(),
                    'music_id' => $id
                ]);
            }
            $musics = Music::where('id', $id)->update([
                'play' => $play + 1,
                'view' => Visit::where('music_id', $id)->count(),
                'heart' => Like::where('music_id', $id)->count(),
            ]);
            $music = Music::with(['artists', 'feats', 'categories'])->findOrFail($id);
            return response()->json(['result' => $music, 'success' => true], 200);
            $jDate = Jalalian::fromCarbon($music->created_at)->format('Y-m-d H:i:s');
            return $jDate;
        } catch (\Exception $ex) {
            return response()->json(['success' => false], 404);
        }
    }

    public function cats()
    {
        $category = Category::all();
        return response()->json(['result' => $category, 'success' => true], 200);
    }

    public function feats()
    {
        $feat = Feat::all();
        return response()->json(['result' => $feat, 'success' => true], 200);
    }

    public function artist()
    {
        $artist = Artist::all();
        return response()->json(['result' => $artist, 'success' => true], 200);
    }

    public function topMusic()
    {
        $artist = Music::orderBy('created_at', 'desc')->with(['artists', 'feats', 'categories'])->where('top', 1)->get();
        return response()->json(['result' => $artist, 'success' => true], 200);
    }

    public function like(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $find = like::where('music_id', $request->id)->where('user_ip', \request()->ip())->first();

        if (!$find) {
            $like = Like::create([
                'music_id' => $request->id,
                'user_ip' => \request()->ip()
            ]);
            return response()->json(['success' => true], 200);
        }

        return response()->json(['success' => true], 400);
    }

    public function unLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $like = Like::where('music_id', $request->id)->delete();
        if ($like)
            return response()->json(['success' => true], 200);
        return response()->json(['success' => false], 400);
    }

    public function musicByfilter(Request $request)
    {
        if ($request->sort) {
            $sort = $request->sort;
        } else $sort = 'view';
        $data = Music::whereNotNull('demo')
        ->withWhereHas('categories',function($q)use($request){
            if($request->categories) $q->whereIn('categories.id',$request->categories);
        })
        ->withWhereHas('artists',function($q)use($request){
            if($request->artists) $q->whereIn('artists.id',$request->artists);
        })
        ->withWhereHas('feats',function($q)use($request){
            if($request->feats) $q->whereIn('feats.id',$request->feats);
        })
        ->get();   
        if ($request->top) {
            $data = $data->whereIn('top', $request->top);     
        }
        if ($request->title) {
            $data = $data->whereIn('title', $request->title);
        }
        return response()->json([
           $data->sortByDesc("$sort")->paginatem(10)
        ]);
    }


    // public function musicByFilter(Request $request)
    // {
    //     $students = Music::when($request->filled('top'), function ($query) use ($request) {
    //         return $query->where('top', true);
    //     })->when($request->filled('like'), function ($query) use ($request) {
    //         return $query->orWhere('like', $request->like);
    //     });

    //     return response()->json([
    //         'students' => $students,
    //         'datas' => $request->all()
    //     ]);
    // }
}


















 // $collection = collect($data);

            // $filtered = $collection->only('id');
            // return $filtered;
            // $collection = $data;

            // $filtered_collection = $collection->filter(function ($item) {
            //     return $item->categories();
            // })->values();
            // $filtered_collection = $collection->filter->isDog()->values();
            // return $filtered_collection;
            // ->with('visits', fn ($query) => $query->where('user_ip', $request->ip()))

            // $rsses = Music::with('categories', fn ($query) => $query->where('user_ip', $request->ip()))
            // ->latest()
            // ->get()
            // ->map(fn ($rss) => [
            //     'id' => $rss->id,
            //     'image' => $rss->img,
            //     'title' => $rss->title,
            //     'description' => $rss->description,
            //     'news_date' => $rss->news_date,
            //     'rss_audio' => $rss->audio,
            //     'like' => $rss->likes_count,
            //     'commentCount' => $rss->rss_comments_count,
            //     'visit' => $rss->visits->contains('user_ip', $request->ip())
            // ])
            // ->paginate(10);


            // return $data;
            // $mappedcollection = $datas->map(function ($data, $key) {
            //     return [
            //         'id' => $data->id,
              //      'category' => Music::with(['categories'])->where('id', $data->id)->first()->categories()->first()->id,
            //     ];
            // });
            // return response()->json([
            //     'data' => $mappedcollection
            // ]);


            // return $tets;
            // $cate = Collection::make($data)->top;
            // return $cate;