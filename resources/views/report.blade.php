@extends('layouts.master')
@section('page_title', 'CS Reporting - Transaction Report')

@section('content')
<div class="row mb">
	<div class="col-sm-12 col-md-12">
		<h2>Transaction Report</h2>
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
			<a id="ajax-report" class="btn btn-primary">Show results</a>
		</div>
	</div>
	
 </div>

<div class="row">
	
	<div id="report-result-table"></div>
	
	<div class="col-md-8 col-md-offset-2">
		
		<table class="table table-bordered green-th" id="report-data-table">
			<thead>
				<tr>
					<th>Transactions Count</th>
					<th>Total</th>
					<th>Currency</th>
				</tr>
			</thead>
			<tbody>
			@foreach ( $data as $row )
				<tr>
					<td>{{ $row['count'] }}</td>
					<td>{{ $row['total'] }}</td>
					<td>{{ $row['currency'] }}</td>
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
		
		$('a#ajax-report').on('click', function(e){
			e.preventDefault();
			
			var spinner = $(".spinner");
			spinner.show();
			
			var fromDate = $("#datetimepicker1").find("input").val();
			var toDate = $("#datetimepicker2").find("input").val();
			
			fromDate = fromDate.replace(/\//g, '-');
			toDate = toDate.replace(/\//g, '-');
			
			$.ajax({
				method: 'GET',
				url: '/ajax-report',
				data: {
					'_token' : '<?php echo csrf_token() ?>',
					'fromDate' : fromDate,
					'toDate' : toDate
				},
				success: function(response){
					var xFromDate = response.fromDate; 
					var xToDate = response.toDate; 
					var json_reponse = response.body;
					
					$("#datetimepicker1").find("input").val(xFromDate);
					$("#datetimepicker2").find("input").val(xToDate);
					
					var $dataTable = $('#report-data-table > tbody');
					$dataTable.empty();
					
					for ( var i = 0; i < json_reponse.length; i++ )
					{
						var html = '<tr>';
						html += '<td>'+json_reponse[i]['count']+'</td>';
						html += '<td>'+json_reponse[i]['total']+'</td>';
						html += '<td>'+json_reponse[i]['currency']+'</td>';
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
