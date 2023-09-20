<div class="row">
	<div class="col-md-6 pb-3">
		<div class="button-items">
			<a href="#" class="btn btn-secondary btn-icon-split btn-outline" onclick="kembaliView()">
				<span class="icon text-white-100">
					<i class="fas fa-arrow-left"></i>
				</span>
				<span class="text">Kembali</span>
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
			<div class="col-6">
				<form id="addForm">
					<div class="form-group row">
						<label for="id_pengajuan" class="col-4 col-form-label">Kode Pengajuan</label> 
						<div class="col-8">
							<input id="id_pengajuan" name="id_pengajuan" value="<?= @$data->id_pengajuan ?>" type="text" class="form-control readonly" placeholder="<?= $placeholderId ?>" readonly>
						</div>
					</div>
					<div class="form-group row">
						<label for="nama_pengajuan" class="col-4 col-form-label">Nama Pengajuan</label> 
						<div class="col-8">
							<input id="nama_pengajuan" name="nama_pengajuan" value="<?= @$data->nama_pengajuan ?>" type="text" class="form-control required" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="id_barang" class="col-4 col-form-label">Kode Barang</label> 
						<div class="col-8">
							<select id="id_barang" name="id_barang" value="<?= @$data->id_barang ?>" class="custom-select required" required>
								<option value="" stock="0"> - </option>
								<?php foreach($stockData as $val) : ?>
									<?php  
										if($data->id_barang == $val->id_barang){ $availableQty = $val->stok; }
										$selected = $data->id_barang == $val->id_barang ? 'selected' : '';
									?>
									<option value="<?= $val->id_barang ?>" stock="<?= $val->stok ?>" <?= $selected ?> >
										<?= $val->id_barang ?> - <?= $val->nama_barang ?>
									</option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="text" class="col-4 col-form-label">Qty Tersedia</label> 
						<div class="col-8">
							<input id="available_qty" name="available_qty" type="text" value="<?= @$availableQty ?>" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label for="kuantitas" class="col-4 col-form-label">Qty</label> 
						<div class="col-8">
							<input id="kuantitas" name="kuantitas" value="<?= @$data->kuantitas ?>" type="text" class="form-control" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="deskripsi" class="col-4 col-form-label">Deskripsi</label> 
						<div class="col-8">
							<textarea id="deskripsi" name="deskripsi" value="<?= @$data->deskripsi ?>" cols="40" rows="2" class="form-control"><?= @$data->deskripsi ?></textarea>
						</div>
					</div> 
					<div class="form-group row">
						<div class="offset-4 col-8">
							<div class="alert alert-danger" id="formAlert" role="alert" style="display:none;">
				  
				</div>
							<button name="submit" type="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#id_barang').change(function(){
				let stock = $('option:selected', this).attr('stock');
				$('#available_qty').val(stock);
			});
			
			$('#addForm').on("submit", function(event){
				event.preventDefault();

				var formValues= $(this).serialize();

				$.post("<?= $url['crud'] ?>", formValues, function(data){
					if(data !== 'OK'){
						$('#formAlert').html(data);
						$('#formAlert').show();
					}else{
						window.parent.load_content("<?= $url['url'] ?>");     	
					}
				});
			});

		});
	</script>