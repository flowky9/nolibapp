<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use App\Book;
use App\BorrowLog;
use App\Exceptions\BookException;

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

    public function borrow(Book $book)
    {
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
}
