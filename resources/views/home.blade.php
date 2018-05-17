@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @include('authors._notification')
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
            
                    @role('member')
                        <!-- apakah buku yang di pinjam ada ? -->
                        @if($borrowLogs->count() == 0)
                            {{ 'Tidak ada buku yang di pinjam' }}
                        @else
                            <!-- menampilkan buku yang di pinjam -->
                            <fieldset>
                                <legend> Buku yang sedang di pinjam </legend>
                                    <ul>
                                        @foreach($borrowLogs as $borrowLog)
                                            <li>{{ $borrowLog->book->title }}

                                                {!! Form::open(['url'=>route('member.books.return',$borrowLog->book->id),'method'=>'put',
                                                    'class'=>'form-inline js-confirm',
                                                    'data-confirm'=>'Anda yakin hendak mengembalikan '.$borrowLog->book->title.' ?'
                                                ]) !!}

                                                {!! Form::submit('Kembalikan',['class'=>'btn btn-xs btn-primary']) !!}

                                                {!! Form::close() !!}
                                            </li>
                                        @endforeach
                                    </ul>
                                
                            </fieldset>
                        @endif
                    @endrole

                    @role('admin')
                        {{ 'Halo '.auth()->user()->name }}
                    @endrole

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
