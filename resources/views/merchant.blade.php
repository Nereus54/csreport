@extends('layouts.master')
@section('page_title', 'CS Reporting - Merchant Detail')

@section('content')
<div class="row">
	<div class="col-md-12 col-md-offset-3 mb">
		<h2>Merchant Detail</h2>
	</div>
</div>

<div class="row mb">
	<div class="col-md-6 col-md-offset-3">
		<table class="table table-hover">
			<tbody>
				<tr>
					<td class="bold">ID</td>
					<td>{{ $data['merchant']['id'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Name</td>
					<td>{{ $data['merchant']['name'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">3D Status</td>
					<td>{{ $data['merchant']['3dStatus'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">IPN URL</td>
					<td>{{ $data['merchant']['ipnUrl'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">API Key</td>
					<td>{{ $data['merchant']['apiKey'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Type</td>
					<td>{{ $data['merchant']['type'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Descriptor</td>
					<td>{{ $data['merchant']['descriptor'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Create Date</td>
					<td>{{ $data['merchant']['createDate'] or '' }}</td>
				</tr>
			</tbody>
		</table>
		
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="text-center">
			<a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-primary">Back</a>
		</div>
	</div>
</div>
@endsection