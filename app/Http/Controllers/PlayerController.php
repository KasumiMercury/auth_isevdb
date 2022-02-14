<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Member;
use App\Models\User;
use App\Models\Players;
use App\Models\BookMark;
use Illuminate\Support\Facades\Auth;

use Faker\Core\Number;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    public function TopPage()
    {
        $players = Players::where('status','!=','3')
                                    ->orderBy('id','DESC')
                                    ->limit(20)
                                    ->get();
        $id = Auth::id();
        if($id != null){
            $likesObj = DB::table('bookmarks')->whereUser_id($id)->get(['player_id']);
        }else{
            $likesObj = null;
        }
        
        return Inertia::render('TopPage', [
            'likesObj' => $likesObj,
            'players' => $players
        ]);
    }
    public function memberTop($member)
    {
        $players = Players::whereHas('member', function ($query) use ($member) {
                                    $query->where('name', $member );
                                    })
                                    ->where('status','!=','3')
                                    ->orderBy('id','DESC')
                                    ->limit(20)
                                    ->get();
        $id = Auth::id();
        if($id != null){
            $likesObj = DB::table('bookmarks')->whereUser_id($id)->get(['player_id']);
        }else{
            $likesObj = null;
        }
        
        return Inertia::render('PlayerList', [
            'likesObj' => $likesObj,
            'memberName' => $member,
            'cate' => 0,
            'players' => $players
        ]);
    }

    public function latest($member)
    {
        $players = Players::whereHas('member', function ($query) use ($member) {
                                    $query->where('name', $member );
                                    })
                                    ->where('status','!=','3')
                                    ->orderBy('id','DESC')
                                    ->limit(10)
                                    ->get();
        $id = Auth::id();
        if($id != null){
            $likesObj = DB::table('bookmarks')->whereUser_id($id)->get(['player_id']);
        }else{
            $likesObj = null;
        }
        
        return Inertia::render('PlayerList', [
            'likesObj' => $likesObj,
            'memberName' => $member,
            'cate' => 0,
            'players' => $players
        ]);
    }
    public function memberCate(string $member,int $cate)
    {
        $players = Players::whereHas('member', function ($query) use ($member) {
                            $query->where('name', $member );
                        })
                        ->where('players.cate_id', $cate)
                        ->where('status','!=','3')
                        ->orderBy('id','DESC')
                        ->get();
        $id = Auth::id();
        if($id != null){
            $likesObj = DB::table('bookmarks')->whereUser_id($id)->get(['player_id']);
        }else{
            $likesObj = null;
        }

        return Inertia::render('PlayerList', [
            'likesObj' => $likesObj,
            'memberName' => $member,
            'cate' => $cate,
            'players' => $players
        ]);
    }
    public function bookMark()
    {
        $id = Auth::id();
        $players = Players::select('players.*')->join('bookmarks','players.id','=','bookmarks.player_id')->where('user_id','=',$id)->get();
        $likesObj = DB::table('bookmarks')->whereUser_id($id)->get(['player_id']);

        return Inertia::render('UserPlayer', [
            'subTitle' => 'BookMarks',
            'likesObj' => $likesObj,
            'players' => $players
        ]);
    }
    public function addedData()
    {
        $user = Auth::user();
        $id = Auth::id();
        $name = $user["name"];
        $players = Players::where('createrHN','=',$name)->get();
        $likesObj = DB::table('bookmarks')->whereUser_id($id)->get(['player_id']);

        return Inertia::render('UserPlayer', [
            'subTitle' => 'YourData',
            'likesObj' => $likesObj,
            'players' => $players
        ]);
    }
    public function player($member,$id)
    {
        $player = Players::find($id);

        return Inertia::render('SharePage', [
            'memberName' => $member,
            'player' => $player,
            'id' => $id
        ]);
    }
    public function addTwitter(Request $request){
        $temp = $request->all();
        var_dump($temp);
        DB::table('players')->insert($temp);
    }
    public function addClip(Request $request){
        $temp = $request->all();
        var_dump($temp);
        DB::table('players')->insert($temp);
    }
    public function addYoutube(Request $request){
        $temp = $request->all();
        var_dump($temp);
        DB::table('players')->insert($temp);
    }
    public function addLike(Request $request){
        $temp = $request->all();
        var_dump($temp);
        DB::table('bookmarks')->insert($temp);
    }
    public function DisLike(Request $request){
        $playerId = $request->get('player_id');
        $userId = $request->get('user_id');
        DB::table('bookmarks')->where('player_id',$playerId)->where('user_id',$userId)->delete();
    }
}
