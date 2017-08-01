@extends('template')

@section('title', 'CANARIE links')

@section('content')
<div class="container">
	<h1>CANARIE links</h1>
	
	<div class="row">
		<div class="col-md-6">

			<h2>Service: iReceptor Database</h2>
			<ul>
				<li><a href="{{ URL::to('/db/service/info') }}">/db/service/info</a></li>
				<li><a href="{{ URL::to('/db/service/stats') }}">/db/service/stats</a></li>
				<li><a href="{{ URL::to('/db/service/doc') }}">/db/service/doc</a></li>
				<li><a href="{{ URL::to('/db/service/releasenotes') }}">/db/service/releasenotes</a></li>
				<li><a href="{{ URL::to('/db/service/support') }}">/db/service/support</a></li>
				<li><a href="{{ URL::to('/db/service/source') }}">/db/service/source</a></li>
				<li><a href="{{ URL::to('/db/service/tryme') }}">/db/service/tryme</a></li>
				<li><a href="{{ URL::to('/db/service/licence') }}">/db/service/licence</a></li>
				<li><a href="{{ URL::to('/db/service/provenance') }}">/db/service/provenance</a></li>
				<li><a href="{{ URL::to('/db/service/factsheet') }}">/db/service/factsheet</a></li>
			</ul>

			<h2>Service: iReceptor Database Migration</h2>
			<ul>
				<li><a href="{{ URL::to('/dbmigration/service/info') }}">/dbmigration/service/info</a></li>
				<li><a href="{{ URL::to('/dbmigration/service/stats') }}">/dbmigration/service/stats</a></li>
				<li><a href="{{ URL::to('/dbmigration/service/doc') }}">/dbmigration/service/doc</a></li>
				<li><a href="{{ URL::to('/dbmigration/service/releasenotes') }}">/dbmigration/service/releasenotes</a></li>
				<li><a href="{{ URL::to('/dbmigration/service/support') }}">/dbmigration/service/support</a></li>
				<li><a href="{{ URL::to('/dbmigration/service/source') }}">/dbmigration/service/source</a></li>
				<li><a href="{{ URL::to('/dbmigration/service/tryme') }}">/dbmigration/service/tryme</a></li>
				<li><a href="{{ URL::to('/dbmigration/service/licence') }}">/dbmigration/service/licence</a></li>
				<li><a href="{{ URL::to('/dbmigration/service/provenance') }}">/dbmigration/service/provenance</a></li>
				<li><a href="{{ URL::to('/dbmigration/service/factsheet') }}">/dbmigration/service/factsheet</a></li>
			</ul>			

		</div>
	</div>

</div>
@stop

