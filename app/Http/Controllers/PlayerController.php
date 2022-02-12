<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Member;
use App\Models\Players;
use Illuminate\Support\Facades\Auth;

use Faker\Core\Number;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    public function memberTop($member)
    {
        $players = Players::whereHas('member', function ($query) use ($member) {
                                    $query->where('name', $member );
                                    })
                                    ->where('status','!=','3')
                                    ->orderBy('id','DESC')
                                    ->limit(20)
                                    ->get();

        return Inertia::render('PlayerList', [
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
        
        return Inertia::render('PlayerList', [
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

        return Inertia::render('PlayerList', [
            'memberName' => $member,
            'cate' => $cate,
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
}
