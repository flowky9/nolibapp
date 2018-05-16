@if(session('success'))
	<div class="alert alert-success">
		<p>{{ session('success') }}</p>
	</div>
@elseif(session('fail'))
	<div class="alert alert-danger">
		<p>{!! session('fail') !!}</p>
	</div>
@endif