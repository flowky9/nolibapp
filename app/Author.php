<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['name'];

    public function books(){
    	return $this->hasMany('App\Book');
    }

    public static function boot(){
    	parent::boot();

    	self::deleting(function($author){
    		// cek
    		if($author->books->count() > 0){
    			// menyiapkan pesan error
    			$html = "Penulis tidak bisa dihapus karena masih memiliki buku : ";
    			$html .= "<ul>";
    			foreach ($author->books as $book) {
    				$html .= "<li>".$book->title."</li>";
    			}
    			$html .= "</ul>";

    			session()->flash('fail',$html);

    			// membatalkan proses penghapusan
    			return false;
    		}
    	});
    }
}
