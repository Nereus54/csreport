@extends('layouts.master')
@section('page_title', 'CS Reporting - Customer Detail')

@section('content')
<div class="row">
	<div class="col-md-12 col-md-offset-3 mb">
		<h2>Client Detail</h2>
	</div>
</div>

<div class="row mb">
	<div class="col-md-6 col-md-offset-3">
		<table class="table table-hover">
			<tbody>
				<tr>
					<td class="bold">ID</td>
					<td>{{ $data['customerInfo']['id'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Email</td>
					<td>{{ $data['customerInfo']['email'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Billing First Name</td>
					<td>{{ $data['customerInfo']['billingFirstName'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Billing Last Name</td>
					<td>{{ $data['customerInfo']['billingLastName'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Billing Country</td>
					<td>{{ $data['customerInfo']['billingCountry'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Created Date</td>
					<td>{{ $data['customerInfo']['created_at'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Updated Date</td>
					<td>{{ $data['customerInfo']['updated_at'] or '' }}</td>
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