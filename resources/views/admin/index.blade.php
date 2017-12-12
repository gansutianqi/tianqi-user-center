@extends('layouts.app')

@section('content')
	<div class="container">
		<p class="text-muted">你好，管理员</p>
		<h4 class="page-header">用户列表</h4>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
				<tr>
					<th>用户名</th>
					<th>邮箱</th>
					<th>加入时间</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				@if(count($users))
					@foreach($users as $user)
						<tr>
							<td>{{ $user->name }}</td>
							<td>{{ $user->email }}</td>
							<td>{{ $user->created_at }}</td>
							<td>
								<a href="{{ url('/users/'.$user->id) }}" class="btn btn-info">
									<i class="fa fa-eye"></i>
								</a>
								<a class="btn btn-primary"
								   href="{{ url('/users/'.$user->id.'/edit') }}">
									<i class="fa fa-pencil"></i>
								</a>
								<form
										action="{{ url('/users/'.$user->id) }}"
										method="post"
										role="form"
										style="display: inline-block">
									{{ csrf_field() }}
									{{ method_field('DELETE') }}
									<button
											onclick="confirm('你确定要删除？')"
											class="btn btn-danger"
											type="submit"
											id="delete-user-{{ $user->id }}">
										<i class="fa fa-trash"></i>
									</button>
								</form>
							</td>
						</tr>
					@endforeach
				@endif
				</tbody>
			</table>
			{{ $users->links() }}
		</div>
	</div>
@stop