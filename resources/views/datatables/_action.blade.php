<a href="{{ $edit_url }}" class="btn btn-xs btn-primary">Edit</a>

{!! Form::open(['url'=>$delete_url,'method'=>'delete',
				'data-confirm'=>$confirm_message,'class'=>'js-confirm form-inline']) !!}

	{!! Form::submit('Delete',['class'=>'btn btn-xs btn-danger']) !!}

{!! Form::close() !!}