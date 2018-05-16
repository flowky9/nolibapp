<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\DataTables;


class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if($request->ajax()){
            $authors = Author::select(['id','name']);
            return DataTables::of($authors)
            ->addColumn('action',function($authors){
                return view('datatables._action',
                    [
                        'edit_url'=> route('authors.edit',$authors->id),
                        'delete_url'=> route('authors.destroy',$authors->id),
                        'confirm_message'=> 'Yakin mau menghapus '.$authors->name . '?',
                    ]
                );
            })
            ->make(true);
        }

        $html = $htmlBuilder
                ->addColumn(['data'=>'name','name'=>'name','title'=>'Nama'])
                ->addColumn(['data'=>'action','name'=>'action','title'=>'Aksi','orderable'=>false,'searchable'=>'false']);

        return view('authors.index')->with(compact('html'));
    }

    public function create()
    {
        return view('authors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
                        'name' => 'required|unique:authors'
                    ]);
        $author = Author::create([
                    'name' => $request->name
                ]);
        session()->flash("success","Berhasil menambahkan ".$author->name);
        return redirect()->route('authors.index');
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

    public function edit($id)
    {
        $author = Author::find($id);
        return view('authors.edit')->with(compact('author'));
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
        $author = Author::find($id);
        $validation = $request->validate([
                        'name' => 'required|unique:authors'
                    ]);
        $author->name = $request->name;
        $author->save();

        session()->flash('success','Berhasil diubah menjadi '.$author->name);
        return redirect()->route('authors.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $author = Author::find($id);
        $name = $author->name;
        
        if($author->delete()){
            session()->flash('success','Berhasil menghapus '.$name);
            return redirect()->route('authors.index');
        }else {
            return redirect()->back();
        }

        
        
    }
}
