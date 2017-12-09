@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">仪表盘</div>

					<div class="panel-body">
						<div class="row">
							<div class="col-sm-2">
								<img src="{{ Auth::user()->getAvatar() }}" alt="">
							</div>

							<div class="col-sm-8">
								<p>
									你好，{{ Auth::user()->name }}
								</p>
								<p>你已经登陆了!</p>
								<p>已加入 {{ \Carbon\Carbon::parse(Auth::user()->created_at)->diffForHumans() }}</p>
							</div>
						</div>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">
						<span>个人资料</span>
						<a href="{{ url('users/'.Auth::user()->id.'/edit') }}" class="pull-right"><i class="glyphicon glyphicon-pencil"></i></a>
					</div>

					<div class="panel-body">
						<dl class="dl-horizontal">
							<dt>头像</dt>
							<dd><a href="{{ url("/users/avatar/".Auth::user()->id."/edit") }}">修改</a></dd>

							<dt>位置</dt>
							<dd>{{ Auth::user()->profile->location }}</dd>

							<dt>个人网站</dt>
							<dd>{{ Auth::user()->profile->website }}</dd>

							<dt>个性签名</dt>
							<dd>{{ Auth::user()->profile->bio }}</dd>

							<dt>加入时间</dt>
							<dd>{{ Auth::user()->created_at }}</dd>
						</dl>
					</div>

				</div>
			</div>
		</div>
	</div>
@endsection
