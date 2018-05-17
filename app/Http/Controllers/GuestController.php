<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\DataTables;
use App\Book;
use App\Author;
use Laratrust\LaratrustFacade as Laratrust;

class GuestController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        if($request->ajax()){
            $books = Book::with('author');
            return DataTables::of($books)
                    ->addColumn('action',function($books){
                        if(Laratrust::hasRole('admin')) return '<a class="btn btn-xs btn-primary disabled" href="#">Pinjam</a>';
                        return '<a class="btn btn-xs btn-primary" href="'.route('guest.books.borrow',$books->id).'">Pinjam</a>';
                   	 
                    })->make(true);
        }

        $html = $htmlBuilder
                ->addColumn(['data'=>'title','name'=>'title','title'=>'Judul'])
                ->addColumn(['data'=>'amount','name'=>'amount','title'=>'Jumlah'])
                ->addColumn(['data'=>'author.name','name'=>'author.name','title'=>'Penulis'])
                ->addColumn(['data'=>'action','name'=>'action','title'=>'Aksi','searcable'=>false,'orderable'=>false]);

        return view('guests.index')->with(compact('html'));
    }
}
