<?php

namespace App\Http\Middleware;

use Closure;

class UserShouldVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if(auth()->check() && !auth()->user()->is_verified){
            $link = url('auth/send-verification').'?email='.urlencode(auth()->user()->email);
            auth()->logout();

            session()->flash('fail','Akun anda belum aktif, silahkan klik link aktivasi yang sudah kami kirim. <a class="alert-link" href="'.$link.'">Kirim lagi</a>');

            return redirect('/login');
        }
        return $response;
    }
}
