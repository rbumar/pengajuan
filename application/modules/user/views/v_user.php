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
			<div class="col-12">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>No</th>
								<?php foreach($column as $key => $valcol) : ?>
									<?php if(!$valcol['hide']){ ?>
										<th><?= $valcol['label'] ?></th>
									<?php } ?>
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
										<?php if(!$valcol['hide']){ ?>
											<td><?= $value->$keycol ?></td>
										<?php } ?>
									<?php endforeach ?>	
									<td align="center">
										<div class="dropdown no-arrow">
                                        <a class="dropdown-toggle btn btn-info btn-rounded btn-sm" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-edit fa-sm fa-fw"></i>
                                        </a>
                                        <div class="dropdown-menu shadow animated--fade-in font-weight-bold"
                                            aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item edit" data="<?= $value->$idCol ?>" href="#"><i class="fas fa-edit fa-sm fa-fw text-primary" ></i> Edit </a>
                                            <a class="dropdown-item delete" data="<?= $value->$idCol ?>" href="#"><i class="fas fa-trash fa-fw text-danger"></i> Delete </a>
                                        </div>
                                    </div>
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
	$(document).ready(function(){
		$('#dataTable').DataTable();

		$('.edit').click(function(){
			let id = $(this).attr('data');
			let editUrl = "<?= $url['form'] ?>" + '?isEdit=Y&id='+id;
			window.parent.load_content(editUrl);
		});

		$('.delete').click(function(){
			let id = $(this).attr('data');
			let delUrl = "<?= $url['crud'] ?>" + '?isDelete=Y&id='+id;
			var confirmation = confirm('Are you sure ? ');
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