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
        $players = Players::with('member')->where('status','!=','3')
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
                                    $query->whereName($member);
                                    })
                                    ->where('status','!=','3')
                                    ->orderBy('id','DESC')
                                    ->limit(10)
                                    ->get();
        $currentMember = Member::whereName($member)->first();
        $id = Auth::id();
        if($id != null){
            $likesObj = DB::table('bookmarks')->whereUser_id($id)->get(['player_id']);
        }else{
            $likesObj = null;
        }
        
        return Inertia::render('PlayerList', [
            'likesObj' => $likesObj,
            'currentMember' => $currentMember,
            'cate' => 0,
            'players' => $players
        ]);
    }
    public function sharePlayer($member,$id)
    {
        $player = Players::find($id);
        $currentMember = Member::find($player['member_id'])->first();
        $related = Players::where('member_id','=',$player['member_id'])
                            ->where('id','!=', $id)
                            ->where('status','!=','3')
                            ->where('status','!=','2')
                            ->where('twitter','=',null)
                            ->inRandomOrder()
                            ->limit(10)
                            ->get();
        $relatedNum = count($related);
        if($relatedNum < 10){
            $temp = Players::where('id','!=', $id)
                            ->where('status','!=','3')
                            ->where('status','!=','2')
                            ->where('twitter','=',null)
                            ->inRandomOrder()
                            ->limit(10 -$relatedNum)
                            ->get();
            $related += $temp;
        };
        return view('SharePlayer',compact('id','player','currentMember','related'));
    }

    public function latest($member)
    {
        $players = Players::whereHas('member', function ($query) use ($member) {
                                    $query->whereName($member);
                                    })
                                    ->where('status','!=','3')
                                    ->orderBy('id','DESC')
                                    ->limit(10)
                                    ->get();
        $currentMember = Member::whereName($member)->first();
        $id = Auth::id();
        if($id != null){
            $likesObj = DB::table('bookmarks')->whereUser_id($id)->get(['player_id']);
        }else{
            $likesObj = null;
        }
        
        return Inertia::render('PlayerList', [
            'likesObj' => $likesObj,
            'currentMember' => $currentMember,
            'cate' => 0,
            'players' => $players
        ]);
    }
    public function memberCate(string $member,int $cate)
    {
        $players = Players::whereHas('member', function ($query) use ($member) {
                            $query->whereName($member);
                        })
                        ->where('players.cate_id', $cate)
                        ->where('status','!=','3')
                        ->orderBy('id','DESC')
                        ->get();
        $currentMember = Member::whereName($member)->first();
        $id = Auth::id();
        if($id != null){
            $likesObj = DB::table('bookmarks')->whereUser_id($id)->get(['player_id']);
        }else{
            $likesObj = null;
        }

        return Inertia::render('PlayerList', [
            'likesObj' => $likesObj,
            'currentMember' => $currentMember,
            'cate' => $cate,
            'players' => $players
        ]);
    }
    public function player($member,$id,Request $request)
    {
        $player = Players::find($id);
        $currentMember = Member::find($player['member_id'])->first();
        $list_type = $request->list;
        $list_id = $request->index;

        $id = Auth::id();
        if($id != null){

            if( $list_type == "BM" ){
                $list_raw = Players::join('bookmarks','players.id','=','bookmarks.player_id')
                                    ->orderBy('bookmarks.id','ASC')
                                    ->select('players.*')
                                    ->get()->toArray();

                if($list_id == null){
                    $list_id = 0;
                }
                if($list_id == 0){
                    $list = $list_raw;
                }else{
                    $list_temp1 = array_slice( $list_raw, 0, $list_id );
                    $list_temp2 = array_slice( $list_raw, $list_id );
                    $list = array_merge($list_temp2,$list_temp1);
                }

                $related = Players::where('member_id','=',$player['member_id'])
                                    ->where('id','!=', $id)
                                    ->where('status','!=','3')
                                    ->where('status','!=','2')
                                    ->where('twitter','=',null)
                                    ->inRandomOrder()
                                    ->limit(10)
                                    ->get();
                $relatedNum = count($related);
                if($relatedNum < 10){
                    $temp = Players::where('id','!=', $id)
                                    ->where('status','!=','3')
                                    ->where('status','!=','2')
                                    ->where('twitter','=',null)
                                    ->inRandomOrder()
                                    ->limit(10 -$relatedNum)
                                    ->get();
                    $related += $temp;
                };
            }elseif( $list_type == "add"){
                $user = Auth::user();
                $name = $user["name"];
                $list_raw = Players::where('createrHN','=',$name)
                                    ->join('member','players.member_id','=','member.id')
                                    ->orderBy('players.id','DESC')
                                    ->select('players.*')
                                    ->get()->toArray();

                if($list_id == null){
                    $list_id = 0;
                }
                if($list_id == 0){
                    $list = $list_raw;
                }else{
                    $list_temp1 = array_slice( $list_raw, 0, $list_id );
                    $list_temp2 = array_slice( $list_raw, $list_id );
                    $list = array_merge($list_temp2,$list_temp1);
                }

                $related = Players::where('member_id','=',$player['member_id'])
                                    ->where('id','!=', $id)
                                    ->where('status','!=','3')
                                    ->where('status','!=','2')
                                    ->where('twitter','=',null)
                                    ->inRandomOrder()
                                    ->limit(10)
                                    ->get();
                $relatedNum = count($related);
                if($relatedNum < 10){
                    $temp = Players::where('id','!=', $id)
                                    ->where('status','!=','3')
                                    ->where('status','!=','2')
                                    ->where('twitter','=',null)
                                    ->inRandomOrder()
                                    ->limit(10 -$relatedNum)
                                    ->get();
                    $related += $temp;
                };

            }else{
                $related = Players::where('member_id','=',$player['member_id'])
                                    ->where('id','!=', $id)
                                    ->where('status','!=','3')
                                    ->where('status','!=','2')
                                    ->where('twitter','=',null)
                                    ->inRandomOrder()
                                    ->limit(10)
                                    ->get();
                $relatedNum = count($related);
                if($relatedNum < 10){
                    $temp = Players::where('id','!=', $id)
                                    ->where('status','!=','3')
                                    ->where('status','!=','2')
                                    ->where('twitter','=',null)
                                    ->inRandomOrder()
                                    ->limit(10 -$relatedNum)
                                    ->get();
                    $related += $temp;
                };

                $list = null;
            };

            $likesObj = DB::table('bookmarks')->whereUser_id($id)->get(['player_id']);

        }else{
            $related = Players::where('member_id','=',$player['member_id'])
                                ->where('id','!=', $id)
                                ->where('status','!=','3')
                                ->inRandomOrder()
                                ->limit(10)
                                ->get();
            $relatedNum = count($related);
            if($relatedNum < 10){
                $temp = Players::where('id','!=', $id)
                                ->where('status','!=','3')
                                ->inRandomOrder()
                                ->limit(10 -$relatedNum)
                                ->get();
                $related += $temp;
            };

            $likesObj = null;
            $list = null;

        }

        return Inertia::render('PlayerPage', [
            'currentMember' => $currentMember,
            'player' => $player,
            'id' => $id,
            'list_type' => $list_type,
            'list_id' => $list_id,
            'list' => $list,
            'related' => $related,
            'likesObj' => $likesObj
        ]);
    }
    public function bookMark()
    {

        $id = Auth::id();
        $players = Players::join('bookmarks','players.id','=','bookmarks.player_id')
                            ->where('user_id','=',$id)
                            ->where('status','!=','3')
                            ->join('member','players.member_id','=','member.id')
                            ->orderBy('bookmarks.id','ASC')
                            ->select('players.*')
                            ->get();
        $likesObj = DB::table('bookmarks')->whereUser_id($id)->get(['player_id']);

        return Inertia::render('UserPlayer', [
            'subTitle' => 'BookMarks',
            'likesObj' => $likesObj,
            'players' => $players,
            'list_type' => 'BM'
        ]);
    }
    public function addedData()
    {
        $user = Auth::user();
        $id = Auth::id();
        $name = $user["name"];
        $players = Players::where('createrHN','=',$name)
                            ->join('member','players.member_id','=','member.id')
                            ->where('status','!=','3')
                            ->orderBy('players.id','DESC')
                            ->select('players.*')
                            ->get();
        $likesObj = DB::table('bookmarks')->whereUser_id($id)->get(['player_id']);

        return Inertia::render('UserPlayer', [
            'subTitle' => 'YourData',
            'likesObj' => $likesObj,
            'players' => $players,
            'list_type' => 'add'
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
