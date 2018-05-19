<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use App\Book;
use App\BorrowLog;
use App\Exceptions\BookException;
use Mail;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone_number'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = ['is_verified'=>'boolean'];

    public function borrow(Book $book)
    {
        if($book->stock < 1){
            throw new BookException("Buku $book->title tidak tersedia");
        }
        // cek apakah buku sedang di pinjam user
        if($this->borrowLogs()->where('book_id',$book->id)->where('is_returned',0)->count() > 0){
            throw new BookException("Buku $book->title sedang anda pinjam");
        }

        $borrowLog = BorrowLog::create([
                    'user_id' => auth()->user()->id,
                    'book_id' => $book->id,
                    'is_returned' => 0
                ]);

        return $borrowLog;
    }

    public function borrowLogs()
    {
        return $this->hasMany('App\BorrowLog');
    }

    public function genereateVerificationToken()
    {
        $token = $this->verification_token;
        if(!$token){
            $token = str_random(40);
            $user = $this;
            $user->verification_token = $token;
            $user->save();
        }

        return $token;
    }

    public function sendVerification()
    {
        $token = $this->genereateVerificationToken();
        $user = $this;
        Mail::send('auth.emails.verification',compact('user','token'),function($m) use ($user){
            $m->to($user->email,$user->name)->subject('Verifikasi akun NoLibApp');
        });
    }

    public function verify(){
        $this->is_verified = 1;
        $this->verification_token = null;
        $this->save();
    }
}
