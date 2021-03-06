@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">Create Item</div>

					<div class="panel-body">
						@include('errors.forms')

						{!! Form::open(['url'=>'/admin/items', 'role'=>'form', 'files'=>'true']) !!}
							@include('backend.items._form', ['buttonText'=>'Save Item'])
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
@stop
