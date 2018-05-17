<?php

use Illuminate\Database\Seeder;
use App\Book;
use App\Author;
use App\User;
use App\Role;
use App\BorrowLog;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// membuat sample penulis
        $author1 = Author::create(['name'=>'Mohammad Fauzil Adhim']);
		$author2 = Author::create(['name'=>'Salim A. Fillah']);
		$author3 = Author::create(['name'=>'Aam Amiruddin']);

		// membuat sample buku
		$book1 = Book::create(['title'=>'Jalan Cinta Para Pejuang','author_id'=>$author2->id,'amount'=>3]);
		$book2 = Book::create(['title'=>'Membingkai Surga dalam Rumah Tangga','author_id'=>$author3->id,'amount'=>6]);
		$book3 = Book::create(['title'=>'Cinta dan Seks Rumah Tangga Muslim','author_id'=>$author3->id,'amount'=>3]);

        // membuat sample peminjaman
        $member = Role::where('name','member')->first();
        $id_member = $member->id;
        $user_member = DB::table('role_user')->where('role_id',$id_member)->first();

        BorrowLog::create(['user_id' => $user_member->user_id, 'book_id'=>$book1->id, 'is_returned' => 0]);
        BorrowLog::create(['user_id' => $user_member->user_id, 'book_id'=>$book2->id, 'is_returned' => 0]);
        BorrowLog::create(['user_id' => $user_member->user_id, 'book_id'=>$book3->id, 'is_returned' => 1]);
        
    }
}
