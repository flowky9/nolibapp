<?php

use Illuminate\Database\Seeder;
use App\Book;
use App\Author;

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
    }
}
