<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class LoginController extends Controller {
    // カスタムログイン処理（ログイン後元の画面に遷移）
    public function index()
    {
        if (!session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }
        return redirect('login');
    }
    // メディア側へのリダイレクト
    public function redirectToProvider(Request $request) {
        $provider = $request->provider;
        return Socialite::driver($provider)->redirect();
    }
    // メディア側から返されるユーザー情報
    public function handleProviderCallback(Request $request) {
        $provider = $request->provider;
        $sns_user = Socialite::driver($provider)->stateless()->user();
        $sns_email = $sns_user->getEmail();
        $sns_name = $sns_user->getName();
 
        // 登録済ならログイン。未登録ならアカウント登録してログイン
        if(!is_null($sns_email)) {
            $user = User::firstOrCreate(   // Userモデルに、レコードがあれば取得、なければ保存
                [ 'name' => $sns_name ],
                [ 'name' => $sns_name, 'password' => Hash::make($provider), 'provider' => $provider
            ]);
            auth()->login($user);
            session()->flash('oauth_login', $provider.'でログインしました。');
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->previous()]);
            }
            return redirect('login');
        }
        return '情報が取得できませんでした。';
    }
}