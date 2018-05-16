@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><a href="{{ url('/home') }}">Dashboard</a></li>
					<li class="active"><a href="{{ url('admin/authors') }}" >Penulis</a></li>
				</ul>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title">Penulis</h2>
					</div>
					<div class="panel-body">
						@include('authors._notification')
						<p><a class="btn btn-primary" href="{{ route('authors.create') }}">Add +</a></p>
						<div class="table-responsive">
							{!! $html->table(['class'=>'table table-hover table-striped']) !!}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	{!! $html->scripts() !!}
@endpush