<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta name="csrf-token" content="{{ csrf_token() }}">

		<title>Field names | iReceptor')</title>

		<!-- css -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>
		
		<div class="container-fluid">
			<h1>Field names</h1>
			<div class="row">
				<div class="col-md-12">
					@if (count($field_name_list) > 0)
						<table class="table table-striped system_list table-condensed">
							<thead>
								<tr>
									<th>ir_id</th>
									<th>ir_v2</th>
									<th>ir_short</th>
									<th>ir_v1</th>
									<th>ir_v1_sql</th>
									<th>airr</th>
									<th>ir_full</th>
									<th>airr_full</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($field_name_list as $s)
								<tr>
									<td class="text-nowrap">{{ $s['ir_id'] }}</td>
									<td class="text-nowrap">{{ $s['ir_v2'] }}</td>
									<td class="text-nowrap">{{ $s['ir_short'] }}</td>
									<td class="text-nowrap">{{ $s['ir_v1'] }}</td>
									<td class="text-nowrap">{{ $s['ir_v1_sql'] }}</td>
									<td class="text-nowrap">{{ $s['airr'] }}</td>
									<td class="text-nowrap">{{ $s['ir_full'] }}</td>
									<td class="text-nowrap">{{ $s['airr_full'] }}</td>
								@endforeach
							</tbody>
						</table>
					@endif
				</div>
			</div>
		</div>

		<footer>
		<div class="container-fluid footer_container">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<p class="text-right">
							<a href="/about">About iReceptor</a>
						</p>
					</div>
				</div>
			</div>
		</div>
		</footer>

		<!-- javascript -->
		<script src="/js/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		
	</body>

</html>






