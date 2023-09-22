<div class="row">
	<div class="col-md-6 pb-3">
		<div class="button-items">
			<a href="#" class="btn btn-success btn-icon-split btn-outline" onclick="tambahView()">
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
			<div class="col-12 pb-3">
				<nav class="nav nav-pills flex-column flex-sm-row font-weight-bold">
					<?php foreach($statusCount as $key => $val) : ?>
						<?php $active = $activeStatus == $key ? 'active' : ''; ?>
						<a class="flex-sm-fill text-sm-center nav-link <?= $active ?>" aria-current="page" href="javascript:void(0);" onclick="refreshViewWithStatus('<?= $key ?>')">
							<span class="d-none d-sm-block">
								<?= $key ?>
								<span class="badge badge-pill badge-<?= $statusColorClass[$key] ?>"><?= $val ?></span>
							</span>
						</a>	
					<?php endforeach ?>
					</nav>
					<hr>
				</div>
				<div class="col-12">
					<div class="table-responsive">
						<table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>No</th>
									<?php foreach($column as $valcol) : ?>
										<th><?= $valcol ?></th>
									<?php endforeach ?>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$no = 1;
								foreach($data as $key => $value) :?>
									<tr>
										<td><?= $no ?></td>
										<?php foreach($column as $keycol => $valcol) : ?>
											<td><?= $value->$keycol ?></td>
										<?php endforeach ?>	
										<td>
											<a href="#" title="Edit" class="btn btn-info btn-circle btn-sm edit" data="<?= $value->$idCol ?>">
												<i class="fas fa-edit"></i>
											</a>
											<a href="#" title="Delete" class="btn btn-danger btn-circle btn-sm delete" data="<?= $value->$idCol ?>">
												<i class="fas fa-trash"></i>
											</a>
											<a href="#" title="Submit" class="btn btn-primary btn-circle btn-sm submit" data="<?= $value->$idCol ?>">
												<i class="fas fa-arrow-right"></i>
											</a>
											<a href="javascript:void(0);" title="Submit" class="btn btn-info btn-circle btn-sm details">
												<i class="fas fa-arrow-down"></i>
											</a>
										</td>
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
		
		function refreshViewWithStatus(status){
			let url = "<?= @$url['url'] ?>?status=" + status;
			window.parent.load_content(url);
		}

		function format(data) {
		    return `<table>
						<tr>
							<th>Kode Barang</th>
							<th>Nama Barang</th>
							<th>Qty</th>
						</tr>
						<tr>
							<td>001</td>
							<td>Kursi Kerja</td>
							<td>10</td>
						</tr>
					</table>`;
		}

		$(document).ready(function(){

			const table = $('#dataTable').DataTable();

			$('#dataTable tbody').on('click', 'a.details', function () {
		        var button = $(this);
		        var row = table.row(button.closest('tr'));

		        if (row.child.isShown()) {
		            // This row is already open - close it
		            row.child.hide();
		            button.find('i').removeClass('fa-arrow-up').addClass('fa-arrow-down');
		        } else {
		            // Open this row
		            row.child(format(row.data())).show();
		            button.find('i').removeClass('fa-arrow-down').addClass('fa-arrow-up');
		        }
		    });

			$('.edit').click(function(){
				let id = $(this).attr('data');
				let editUrl = "<?= $url['form'] ?>" + '?isEdit=Y&id='+id;
				window.parent.load_content(editUrl);
			});

			$('.delete').click(function(){
				let id = $(this).attr('data');
				let delUrl = "<?= $url['crud'] ?>" + '?isDelete=Y&id='+id;
				var confirmation = confirm('Are you sure want to delete this ? ');
				if (confirmation){
					$.get(delUrl, function(data){
						if(data == 'OK'){
							window.parent.load_content("<?= $url['url'] ?>");
						}else{
							alert(data);
						}
					});	
				}

			});

			$('.submit').click(function(){
				let id = $(this).attr('data');
				let delUrl = "<?= $url['crud'] ?>" + '?isSubmit=Y&id='+id;
				var confirmation = confirm('Are you sure want to submit this ? ');
				if (confirmation){
					$.get(delUrl, function(data){
						if(data == 'OK'){
							window.parent.load_content("<?= $url['url'] ?>");
						}else{
							alert(data);
						}
					});	
				}

			});
		});
	</script>