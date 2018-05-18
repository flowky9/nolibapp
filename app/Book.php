<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title','author_id','amount'];

    public function author(){
    	return $this->belongsTo('App\Author');
    }

    public function borrowLogs()
    {
    	return $this->hasMany('App\BorrowLog');
    }

    public function getStockAttribute()
    {
    	$borrowed = $this->borrowLogs()->borrowed()->count();
    	$stock = $this->amount - $borrowed;
    	return $stock;
    }

    public static function boot()
    {
    	parent::boot();

    	self::updating(function($book){
    		if($book->amount < $book->borrowed){
    			session()->flash('fail',$book->borrowed.' buku sedang dipinjam, jumlah buku harus => '. $book->borrowed);
    			return false;
    		}

    	});

        self::deleting(function($book){
            if($book->borrowed > 0){
                session()->flash('fail',$book->borrowed.' buku sedang dipinjam');
                return false;
            }
        });
    }

    public function getBorrowedAttribute()
    {
    	return $this->borrowLogs()->borrowed()->count();
    }
}
