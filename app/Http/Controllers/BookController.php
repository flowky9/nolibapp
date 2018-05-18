<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\DataTables;
use App\Book;
use App\Author;
use App\BorrowLog;
use App\Exceptions\BookException;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookController extends Controller
{

    public function index(Request $request, Builder $htmlBuilder)
    {
        if($request->ajax()){
            $books = Book::with('author');
            return DataTables::of($books)
                    ->addColumn('stock',function($books){
                        return $books->stock;
                    })
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
                ->addColumn(['data'=>'stock','name'=>'stock','title'=>'Stock','searcable'=>false,'orderable'=>false])
                ->addColumn(['data'=>'author.name','name'=>'author.name','title'=>'Penulis'])
                ->addColumn(['data'=>'action','name'=>'action','title'=>'Aksi','searcable'=>false,'orderable'=>false]);

        return view('books.index')->with(compact('html'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validation = $request->validate(
                    [
                        'title' => 'required|unique:books,title',
                        'author_id' => 'required|exists:authors,id',
                        'amount' => 'required|numeric',
                        'cover' => 'image|max:2048',
                    ]
                    );
        $book = Book::create($request->except('cover'));

        // isi field cover
        if($request->hasFile('cover')){
            // get file
            $uploaded_cover = $request->file('cover');
            // get extension
            $file_extension = $uploaded_cover->getClientOriginalExtension();
            // filename
            $filename = md5(time()).'.'.$file_extension;
            // save to img folder
            $destinationPath = public_path().DIRECTORY_SEPARATOR.'img';
            $uploaded_cover->move($destinationPath,$filename);

            // isi db
            $book->cover = $filename;
            $book->save();
        }

        session()->flash('success','Buku berhasil ditambahkan');

        return redirect()->route('books.index');
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $book = Book::find($id);
        return view('books.edit')->with(compact('book'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        $book->update($request->all());

        if(!$book->update($request->all())) return redirect()->back();

        if($request->hasFile('cover')){
            // get file
            $uploaded_cover = $request->file('cover');
            // get extension
            $file_extension = $uploaded_cover->getClientOriginalExtension();
            // filename
            $filename = md5(time()).'.'.$file_extension;
            // save to img folder
            $destinationPath = public_path().DIRECTORY_SEPARATOR.'img';
            $uploaded_cover->move($destinationPath,$filename);

            if($book->cover){
                $old_cover = $book->cover;
                $filePath = public_path().DIRECTORY_SEPARATOR.'img'.
                            DIRECTORY_SEPARATOR.$old_cover;

                File::delete($filePath);
            }

            // isi db
            $book->cover = $filename;
            $book->save();
        }

        session()->flash('success','Buku berhasil diubah');

        return redirect()->route('books.index');

      
    }

    public function destroy($id)
    {
       $book = Book::find($id);
       $book_title = $book->title;

       if(!$book->delete()){
            return redirect()->back();
       }

       if($book->cover){
            $filename = $book->cover;
            $filepath = public_path().DIRECTORY_SEPARATOR.'img'.
                        DIRECTORY_SEPARATOR.$filename;

            try {
                File::delete($filepath);
            }catch(FileNotFoundException $e){

            }
       }

       $book->delete();

       session()->flash('success','Berhasil menghapus buku '.$book_title);

       return redirect()->route('books.index');
    }

    public function borrow($id)
    {
        try {
            $book = Book::findOrFail($id);

            // BorrowLog::create([
            //     'user_id' => auth()->user()->id,
            //     'book_id' => $book->id,
            // ]);
            auth()->user()->borrow($book);
            session()->flash('success','Berhasil meminjam buku '.$book->title);
        }catch(BookException $e){
            session()->flash('fail',$e->getMessage());
        }catch(ModelNotFoundException $e){
            session()->flash('fail','Buku tidak ditemukan');
        }

        return redirect('/');
    }

    public function return($book_id)
    {
        $borrowLog = BorrowLog::where('user_id', auth()->user()->id)
                                ->where('book_id', $book_id)
                                ->where('is_returned',0)
                                ->first();
        if($borrowLog){
            $borrowLog->is_returned = true;
            $borrowLog->save();
        }

        session()->flash('success','Buku berhasil di kembalikan');

        return redirect('/home');
    }
}
