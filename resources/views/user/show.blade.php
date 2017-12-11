@extends('layouts.app')

@section('content')
	<div class="container">
		<h4 class="page-header">
			用户详情
		</h4>
		<dl class="dl-horizontal">
			<dt>头像</dt>
			<dd><img src="{{ $user->getAvatar() }}" alt=""></dd>
			<dt>用户名</dt>
			<dd>{{ $user->name }}</dd>
			<dt>电子邮箱</dt>
			<dd>{{ $user->email }}</dd>
			<dt>加入时间</dt>
			<dd>{{ $user->created_at }}</dd>
			<dt>位置</dt>
			<dd>{{ $user->profile->location }}</dd>
			<dt>个人网站</dt>
			<dd>{{ $user->profile->website }}</dd>
			<dt>个性签名</dt>
			<dd>{{ $user->profile->bio }}</dd>
		</dl>
	</div>
@stop