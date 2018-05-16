<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\DataTables;
use App\Book;
use App\Author;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if($request->ajax()){
            $books = Book::with('author');
            return DataTables::of($books)
                    ->addColumn('action',function($books){
                        return view('datatables._action',[
                            'model' => $books,
                            'delete_url' => route('books.destroy',$books->id),
                            'edit_url' => route('books.edit',$books->id),
                            'confirm_message' => 'Yakin mau menghapus '.$books->title. '?',
                        ]);
                    })->make(true);
        }

        $html = $htmlBuilder
                ->addColumn(['data'=>'title','name'=>'title','title'=>'Judul'])
                ->addColumn(['data'=>'amount','name'=>'amount','title'=>'Jumlah'])
                ->addColumn(['data'=>'author.name','name'=>'author.name','title'=>'Penulis'])
                ->addColumn(['data'=>'action','name'=>'action','title'=>'Aksi','searcable'=>false,'orderable'=>false]);

        return view('books.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
