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

    public function like($id)
    {

        $find = like::where('music_id', $id)->where('user_ip', \request()->ip())->first();

        if (!$find) {
            $like = Like::create([
                'music_id' => $id,
                'user_ip' => \request()->ip()
            ]);
            return response()->json(['success' => true], 200);
        }

        return response()->json(['success' => true], 400);
    }

    public function unLike($id)
    {
        $like = Like::where('music_id', $id)->delete();
        if ($like)
            return response()->json(['success' => true], 200);
        return response()->json(['success' => false], 400);
    }

    public function musicByfilter(Request $request)
    {
        // $items = Music::withCount('visits')->orderByDesc('visits_count')
        //     ->paginate(25);
        //     return $items;
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
            // if ($sort == "play") {
            //     $data = Music::where('top', $request->top)
            //        // // ->withCount('likes')
            //        // // ->orderByDesc('likes_count')
            //         // ->withCount("$sort")
            //         // ->orderByDesc("$sort"."_count")
            //         ->orderByDesc('play')
            //         ->paginate(10);
            //     return response()->json([
            //         'top' => $data
            //     ]);
            // } else
            /////
            $data = $data->whereIn('top', $request->top);
            // $data = Music::where('top', $request->top)
            //     // ->withCount('likes')
            //     // ->orderByDesc('likes_count')
            //     ->withCount("$sort")
            //     ->orderByDesc("$sort" . "_count");
            //     // ->orderByDesc('play')
            //     // ->paginate(10);
            // return response()->json([
            //     'top' => $data
            // ]);
        }
        // if ($request->demo) { 
        //     if ($sort == "play") {
        //         $data = Music::whereIn('demo', $request->demo)
        //             ->orderByDesc('play')
        //             ->paginate(10);
        //         return response()->json([
        //             'demo' => $data
        //         ]);
        //     } else
        //         $data = Music::whereIn('demo', $request->demo)
        //             ->withCount("$sort")
        //             ->orderByDesc("$sort" . "_count")
        //             ->paginate(10);
        //     return response()->json([
        //         'demo' => $data
        //     ]);
        // }
        if ($request->title) {
            $data = $data->whereIn('title', $request->title);
            ///////
            // if ($sort == "play") {
            //     $data = Music::whereIn('title', $request->title)
            //         ->orderByDesc('play')
            //         ->paginate(10);
            //     return response()->json([
            //         'title' => $data
            //     ]);
            // } else
            //     $data = Music::whereIn('title', $request->title)
            //         ->withCount("$sort")
            //         ->orderByDesc("$sort" . "_count")
            //         ->paginate(10);
            // return response()->json([
            //     'title' => $data
            // ]);
        }
        if ($request->categories) {
            

            // $data = collect($data)->filter(function ($item) use($request) {
            //     return array_key_exists('categories', collect($item)->toArray()) && collect($item['categories'])
            //     ->where('id', $request->categories)->count();
            // });

            // collect($data)->filter(function ($item) {
                
            //     return array_key_exists('categories', collect($item)->toArray()) && collect($item['categories'])
            //     ->whereIn('id', $request->categories);
            // });



            // $data = Category::whereIn('id', $request->categories)->with('musics')->paginate(10);
            // return response()->json([
            //     'categories' => $data
            // ]);
        }
        // if ($request->artists) {
        //     $data = Artist::whereIn('id', $request->artists)->with('musics')->paginate(10);
        //     return response()->json([
        //         'artists' => $data
        //     ]);
        // }
        // if ($request->remixCreator) {
        //     $data = Feat::whereIn('id', $request->remixCreator)->with('musics')->paginate(10);
        //     return response()->json([
        //         'remixCreator' => $data
        //     ]);
        // }

        return response()->json([
            'data' => $data->sortByDesc("$sort")->paginatem(10)
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