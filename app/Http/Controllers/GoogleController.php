<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Prodotto;

class GoogleController extends Controller
{
    public function redirect()
    {
        $state = bin2hex(random_bytes(16));
        session(['google_oauth_state' => $state]);

        $params = http_build_query([
            'client_id'     => env('GOOGLE_CLIENT_ID'),
            'redirect_uri'  => env('GOOGLE_REDIRECT_URI'),
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'state'         => $state,
            'access_type'   => 'online',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $params);
    }

    public function callback(Request $request)
    {
        if ($request->state !== session('google_oauth_state')) {
            return redirect()->route('login')->withErrors(['google' => 'Errore CSRF. Riprova.']);
        }

        if ($request->has('error') || !$request->has('code')) {
            return redirect()->route('login')->withErrors(['google' => 'Accesso Google annullato.']);
        }

        $tokenResponse = $this->getToken($request->code);
        if (!isset($tokenResponse['access_token'])) {
            return redirect()->route('login')->withErrors(['google' => 'Errore token Google.']);
        }

        $userInfo = $this->getUserInfo($tokenResponse['access_token']);
        if (!isset($userInfo['email'])) {
            return redirect()->route('login')->withErrors(['google' => 'Errore userinfo Google.']);
        }

        $user = User::where('google_id', $userInfo['sub'])
            ->orWhere('email', $userInfo['email'])
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $userInfo['sub'],
                'avatar'    => $userInfo['picture'] ?? null,
            ]);
        } else {
            $username = explode('@', $userInfo['email'])[0] . '_' . Str::random(4);
            $user = User::create([
                'name'      => $username,
                'firstname' => $userInfo['given_name']  ?? '',
                'lastname'  => $userInfo['family_name'] ?? '',
                'email'     => $userInfo['email'],
                'password'  => Hash::make(Str::random(32)),
                'google_id' => $userInfo['sub'],
                'avatar'    => $userInfo['picture'] ?? null,
                'role'      => 'user',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('shop');
    }

    private function getToken(string $code): array
    {
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => http_build_query([
                'code'          => $code,
                'client_id'     => env('GOOGLE_CLIENT_ID'),
                'client_secret' => env('GOOGLE_CLIENT_SECRET'),
                'redirect_uri'  => env('GOOGLE_REDIRECT_URI'),
                'grant_type'    => 'authorization_code',
            ]),
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true) ?? [];
    }

    private function getUserInfo(string $accessToken): array
    {
        $ch = curl_init('https://www.googleapis.com/oauth2/v3/userinfo');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ["Authorization: Bearer $accessToken"],
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true) ?? [];
    }
}
