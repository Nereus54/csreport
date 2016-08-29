@extends('layouts.master')
@section('page_title', 'CS Reporting - Transaction Detail')

@section('content')
<div class="row">
	<div class="col-md-6 col-md-offset-3 mb">
		<h2>Transaction Detail</h2>
	</div>
</div>

<div class="row mb">
	<div class="col-md-6 col-md-offset-3">
		<table class="table table-hover">
			<tbody>
				<tr>
					<td class="bold">Customer ID</td>
					<td>{{ $data['customerInfo']['id'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Email</td>
					<td>{{ $data['customerInfo']['email'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Customer Name</td>
					<td>{{ $data['customerInfo']['billingFirstName'] or '' }} {{ $data['customerInfo']['billingLastName'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Amount</td>
					<td>{{ $data['fx']['merchant']['originalAmount'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Currency</td>
					<td>{{ $data['fx']['merchant']['originalCurrency'] or ''}}</td>
				</tr>
				<tr>
					<td class="bold">Transaction Reference No</td>
					<td>{{ $data['transaction']['merchant']['referenceNo'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Transaction status</td>
					<td>{{ $data['transaction']['merchant']['status'] or ''}}</td>
				</tr>
				<tr>
					<td class="bold">Transaction code</td>
					<td>{{ $data['transaction']['merchant']['code'] or '' }}</td>
				</tr>
				<tr>
					<td class="bold">Merchant</td>
					<td>{{ $data['merchant']['name'] or '' }}</td>
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