@extends('layouts.app')

@section('content')
	<div class="passport-authorize">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4>授权请求</h4>
						</div>
						<div class="panel-body">
							<!-- Introduction -->
							<p><strong>{{ $client->name }}</strong> 请求允许进入你的帐户。</p>

							<!-- Scope List -->
							@if (count($scopes) > 0)
								<div class="scopes">
									<p><strong>这个应用程序将能够：</strong></p>

									<ul>
										@foreach ($scopes as $scope)
											<li>{{ $scope->description }}</li>
										@endforeach
									</ul>
								</div>
							@endif

							<div class="buttons">
								<!-- Authorize Button -->
								<form method="post" action="/oauth/authorize">
									{{ csrf_field() }}

									<input type="hidden" name="state" value="{{ $request->state }}">
									<input type="hidden" name="client_id" value="{{ $client->id }}">
									<button type="submit" class="btn btn-success btn-approve">授权</button>
								</form>

								<!-- Cancel Button -->
								<form method="post" action="/oauth/authorize">
									{{ csrf_field() }}
									{{ method_field('DELETE') }}

									<input type="hidden" name="state" value="{{ $request->state }}">
									<input type="hidden" name="client_id" value="{{ $client->id }}">
									<a href="javascript:history.back()" class="btn btn-danger">取消</a>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@push('css')
	<style>
		.passport-authorize .container {
			margin-top: 30px;
		}

		.passport-authorize .scopes {
			margin-top: 20px;
		}

		.passport-authorize .buttons {
			margin-top: 25px;
			text-align: center;
		}

		.passport-authorize .btn {
			width: 125px;
		}

		.passport-authorize .btn-approve {
			margin-right: 15px;
		}

		.passport-authorize form {
			display: inline;
		}
	</style>
@endpush
