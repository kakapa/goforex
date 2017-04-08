@extends('layouts.app')

@section('content')
	<div class="content-wrapper">

		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				Invoices
			</h1>
			<ol class="breadcrumb">
				<li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li class="active">My Invoices</li>
			</ol>
		</section>

		<!-- Main Content -->
		<section class="content">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
                    <div class="box box-default">
                        <div class="box-header with-border">
							<h3 class="box-title">My Invoices</h3>
						</div>
						<div class="box-body">
							<table class="ui table table-hover table-striped table-condensed" id="events">
								<thead>
								<tr>
									<th>ID</th>
									<th>Amount</th>
									<th>Status</th>
									<th>Created</th>
									<th>Actions</th>
								</tr>
								</thead>
								<tbody>
								@foreach($invoices as $invoice)
									<tr>
										<td class='hidden-350'>{{ $invoice->id }}</td>
										<td>R{{ $invoice->amount }}</td>
										<td>{{ $invoice->status_is }}</td>
										<td>{{ $invoice->created_at }}</td>
										<td>
											<a href="{{ url('invoices', $invoice->id) }}" class="btn" rel="tooltip" title="View">
												<b>Show</b>
											</a>
											<a href="{{ url('invoices/'.$invoice->id.'/print') }}" class="btn" rel="tooltip"
											   title="Print">
												<b>Print</b>
											</a>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

@endsection

@section('styles')
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.semanticui.min.css">

	<style type="text/css">
		.ui.grid{
			margin: 0;
			padding-left: 2.5rem;
		}
		.ui.table td {
			padding: .58571429em .98571429em;
		}
		.ui.table td.unread {
			font-weight: bold;
		}
	</style>
@stop

@section('javascript')
	{{ Html::script('https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js') }}
	{{ Html::script('https://cdn.datatables.net/1.10.13/js/dataTables.semanticui.min.js') }}
	{{ Html::script('http://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.min.js') }}

	<script>
		$(document).ready(function() {
			$('#events').DataTable();
		} );
	</script>
@stop