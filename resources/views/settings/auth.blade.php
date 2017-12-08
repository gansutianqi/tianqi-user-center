
@extends('layouts.app')

@section('content')
	<div class="container">
		<h4 class="page-header">开发者</h4>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<passport-clients></passport-clients>
				<passport-personal-access-tokens></passport-personal-access-tokens>
				<passport-authorized-clients></passport-authorized-clients>
			</div>
		</div>
	</div>

@endsection

