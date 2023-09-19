<div class="row">
	<div class="col-md-6 pb-3">
		<div class="button-items">
			<a href="#" class="btn btn-success btn-icon-split btn-outline">
				<span class="icon text-white-100">
					<i class="fas fa-plus"></i>
				</span>
				<span class="text">Tambah</span>
			</a>
			<a href="#" class="btn btn-info btn-icon-split btn-outline" onclick="refreshView()">
				<span class="icon text-white-100">
					<i class="fas fa-sync"></i>
				</span>
				<span class="text">Refresh</span>
			</a>
		</div>
	</div>
	<div class="col-md-6 pb-3 text-right">
		<div class="button-items">
		</div>
	</div>
</div>
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary"> <?= $title ?></h6>
	</div>
	<div class="card-body">
		<div class="row">
			<!-- <div class="col-12 pb-3">
				<nav class="nav nav-pills flex-column flex-sm-row font-weight-bold">
					<a class="flex-sm-fill text-sm-center nav-link " aria-current="page" href="#">
						<span class="d-none d-sm-block">
									All
									<span class="badge badge-pill badge-info">1</span>
								</span></a>
					<a class="flex-sm-fill text-sm-center nav-link active" href="#">Open</a>
					<a class="flex-sm-fill text-sm-center nav-link" href="#">Submitted</a>
					<a class="flex-sm-fill text-sm-center nav-link" href="#">Approved</a>
					<a class="flex-sm-fill text-sm-center nav-link" href="#">Rejected</a>
				</nav>
				<hr>
			</div> -->	
			<div class="col-12">
				<div class="table-responsive">
					<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>No</th>
								<th>Email</th>
								<th>Name</th>
								<th>Status</th>
								<th>Last Login</th>
								<th>Created by</th>
								<th>Created date</th>

							</tr>
						</thead>
						<tbody>
							<?php $no = 1 ?>
							<?php 
							foreach($data as $key => $value) :?>
								<tr>
									<td><?= $no ?></td>
									<td><?= $value->email ?></td>
									<td><?= $value->name ?></td>
									<td><?= $value->status_txt ?></td>
									<td><?= $value->last_login ?></td>
									<td><?= $value->created_by ?></td>
									<td><?= $value->created_date ?></td>

								</tr>
								<?php $no++ ?>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>	
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#dataTable').DataTable();
	});
</script>