@extends('layouts.master')
@section('page_title', 'CS Reporting - Transaction List')

@section('content')
  <!--
  'per_page' => int 50
  'current_page' => int 1
  'next_page_url' => string 'https://testreportingapi.clearsettle.com/api/v3/transaction/list?page=2'
  'prev_page_url' => null
  'from' => int 1
  'to' => int 50
  -->
  
  <div class="row mb">
	 
	<div class="col-sm-12 col-md-12">
		<h2>Transaction List</h2>
	</div>
	
	<div class="col-sm-12 col-md-4">
        <div class="form-group">
			<label>From Date:</label>
            <div class='input-group date' id='datetimepicker1'>
                <input type='text' class="form-control" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
			<label>To Date:</label>
            <div class='input-group date' id='datetimepicker2'>
                <input type='text' class="form-control" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
	
	<div class="col-sm-12 col-md-4">
		<div style="padding-top: 24px;">
			<a class="btn btn-primary" id="ajax-list">Show results</a>
		</div>
	</div>
	
 </div>
  
  <div class="row">
	
	<div class="col-md-12">
		<table class="table table-bordered small-text green-th" id="list-data-table">
			<thead>
				<tr>
					<th>Amount</th>
					<th>Currency</th>
					<th>Created</th>
					<th>Customer</th>
					<th>Acquirer</th>
					<th>Merchant</th>
					<th>Transaction No</th>
					<th>Transaction Status</th>
				</tr>
			</thead>
			<tbody>
				@foreach ( $data['data'] as $row )
				<tr>
					<td>
						{{ $row['fx']['merchant']['originalAmount'] }}
					</td>
					<td>
						{{ $row['fx']['merchant']['originalCurrency'] }}
					</td>
					<td>
						{{ $row['created_at'] }}
					</td>
					<td>
						<a href="view-client/{{ $row['transaction']['merchant']['transactionId'] }}">
							{{ $row['customerInfo']['billingFirstName'] or '' }} {{ $row['customerInfo']['billingLastName'] or '' }}
						</a>
					<td>
						{{ $row['acquirer']['name'] or '' }} {{ $row['acquirer']['code'] or '' }}
					</td>
					<td>
						<a href="/view-merchant/{{ $row['transaction']['merchant']['transactionId'] }}">
							{{ $row['merchant']['name'] }}
						</a>
					</td>
					<td>
						<a href="/view-transaction/{{ $row['transaction']['merchant']['transactionId'] }}">
							{{ $row['transaction']['merchant']['referenceNo'] }}
						</a>
					</td>
					<td>
						{{ $row['transaction']['merchant']['status'] }}
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
  </div>
  
  <div class="spinner" style="display:none;"></div>
  
  <script type="text/javascript">
	
	$(document).ready(function(){
		$('#datetimepicker1').datetimepicker({
			format: 'DD/MM/YYYY'
		});

		$("#datetimepicker1").find("input").val({{ $fromDate }});

		$('#datetimepicker2').datetimepicker({
			format: 'DD/MM/YYYY',
			useCurrent: false //Important! See issue #1075
		});
		$("#datetimepicker1").on("dp.change", function (e) {
			$('#datetimepicker2').data("DateTimePicker").minDate(e.date);
		});
		$("#datetimepicker2").on("dp.change", function (e) {
			$('#datetimepicker1').data("DateTimePicker").maxDate(e.date);
		});

		$("#datetimepicker1").find("input").val('<?php echo $fromDate; ?>');
		$("#datetimepicker2").find("input").val('<?php echo $toDate; ?>');
		
		$('a#ajax-list').on('click', function(e){
			e.preventDefault();
			
			var spinner = $(".spinner");
			spinner.show();
			
			var fromDate = $("#datetimepicker1").find("input").val();
			var toDate = $("#datetimepicker2").find("input").val();
			
			fromDate = fromDate.replace(/\//g, '-');
			toDate = toDate.replace(/\//g, '-');
			
			$.ajax({
				method: 'GET',
				url: '/ajax-list',
				data: {
					'_token' : '<?php echo csrf_token() ?>',
					'fromDate' : fromDate,
					'toDate' : toDate,
					'page' : 1
				},
				success: function(response){ 
					var xFromDate = response.fromDate; 
					var xToDate = response.toDate; 
					var json_reponse = response.body;
					
					$("#datetimepicker1").find("input").val(xFromDate);
					$("#datetimepicker2").find("input").val(xToDate);
					
					var $dataTable = $('#list-data-table > tbody');
					$dataTable.empty();
					
					for ( var i = 0; i < json_reponse.length; i++ )
					{
						var billing_first_name = '';
						var billing_last_name = '';
						if ( undefined != json_reponse[i]['customerInfo'] ) {
							billing_first_name = json_reponse[i]['customerInfo']['billingFirstName']
							billing_last_name = json_reponse[i]['customerInfo']['billingLastName'];
						}
			
						acquirer_name= '';
						acquirer_code = '';
						if ( undefined != json_reponse[i]['acquirer'] ) {
							acquirer_name = json_reponse[i]['acquirer']['name'];
							acquirer_code = json_reponse[i]['acquirer']['code'];
						}
						
						var html = '<tr>';
						html += '<td>'+json_reponse[i]['fx']['merchant']['originalAmount']+'</td>';
						html += '<td>'+json_reponse[i]['fx']['merchant']['originalCurrency']+'</td>';
						html += '<td>'+json_reponse[i]['created_at']+'</td>';
						
						html += '<td>';
						html += '<a href="view-client/'+json_reponse[i]['transaction']['merchant']['transactionId']+'">';
						html += billing_first_name +' '+ billing_last_name;
						html += '</a></td>';
						
						html += '<td>'+ acquirer_name +' '+ acquirer_code +'</td>';
						
						html += '<td>';
						html += '<a href="/view-merchant/'+json_reponse[i]['transaction']['merchant']['transactionId']+'">';
						html += json_reponse[i]['merchant']['name'];
						html += '</a></td>';
						
						html += '<td>';
						html += '<a href="/view-transaction/'+json_reponse[i]['transaction']['merchant']['transactionId']+'">';
						html += json_reponse[i]['transaction']['merchant']['referenceNo'];
						html += '</a></td>';
						
						html += '<td>'+json_reponse[i]['transaction']['merchant']['status']+'</td>';
						html += '</tr>';
						
						$dataTable.append(html);
					}
					
					spinner.hide();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(JSON.stringify(jqXHR));
					console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
				}
			});
		});
		
	});
</script>

@endsection
