@extends('layouts.app')

@section('content')
	<div class="container">
		<h4 class="page-header">个人资料</h4>
		@if (Auth::user()->can('update',$user))

			<p>
				<a href="/users/avatar">修改头像</a>
			</p>

			<form action="{{ url('users/'.$user->id) }}" method="post">
				{{ csrf_field() }}
				{{ method_field('put') }}
				<div class="form-group">
					<label for="name">名称</label>
					<input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}">
				</div>

				<div class="form-group">
					<label for="email">电子邮箱</label>
					<input type="text" id="email" name="email" class="form-control" value="{{ $user->email }}" disabled>
				</div>

				<div class="form-group">
					<label for="location">位置</label>
					<input type="text" id="location" name="location" class="form-control" value="{{ $user->profile->location }}">
				</div>

				<div class="form-group">
					<label for="location">个人网站</label>
					<input type="text" id="website" name="website" class="form-control" value="{{ $user->profile->website }}">
				</div>

				<div class="form-group">
					<label for="bio">个性签名</label>
					<textarea name="bio" id="bio" class="form-control">{{ $user->profile->bio }}</textarea>
				</div>

				<button type="submit" class="btn btn-default">确认</button>

			</form>
		@else
			<div class="alert alert-warning">
				你没有权限
			</div>
		@endif

	</div>
@stop