<!DOCTYPE html>
<html>
    <head>
        <title>@yield('page_title')</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">

		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet"> 
		<link href="/css/style.css" rel="stylesheet" type="text/css">
		
		<script src="/js/jquery-2.1.4.js" type="text/javascript"></script>
		<script src="/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="/js/moment.js" type="text/javascript"></script>
		<script src="/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    </head>
	
    <body>
		<div class="container-fluid">
			<div class="container">
				
				<nav class="navbar navbar-default">
					<div class="container-fluid">
					  <!-- Brand and toggle get grouped for better mobile display -->
					  <div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						  <span class="sr-only">Toggle navigation</span>
						  <span class="icon-bar"></span>
						  <span class="icon-bar"></span>
						  <span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="#">Navigation</a>
					  </div>

					  @php $request_uri = trim((string)$_SERVER['REQUEST_URI']) @endphp
					  
					  <!-- Collect the nav links, forms, and other content for toggling -->
					  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
						  <li class="{{ ( in_array($request_uri, array('/', '/report')) ) ? 'active' : '' }}">
							  <a href="/report">Transaction report</a>
						  </li>
						  <li class="{{ ( '/list' === $request_uri ) ? 'active' : '' }}">
							  <a href="/list">Transaction list</a>
						  </li>
						</ul>
						
					  </div><!-- /.navbar-collapse -->
					</div><!-- /.container-fluid -->
				  </nav>
				
				<div class="row">
					<div class="col-md-12">@yield('content')</div>
				</div>
			</div>
		</div>
		
    </body>
</html>
