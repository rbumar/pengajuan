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
						<label for="id_karyawan" class="col-4 col-form-label">Nik</label> 
						<div class="col-8">
							<input id="id_karyawan" name="id_karyawan" placeholder="<?= @$placeholderId ?>" value="<?= @$data->id_karyawan ?>" type="text" class="form-control" readonly>
						</div>
					</div>
					<div class="form-group row">
						<label for="nama_karyawan" class="col-4 col-form-label">Nama</label> 
						<div class="col-8">
							<input id="nama_karyawan" name="nama_karyawan" value="<?= @$data->nama_karyawan ?>" type="text" class="form-control" required="required">
						</div>
					</div>
					<div class="form-group row">
						<label for="email" class="col-4 col-form-label">Email</label> 
						<div class="col-8">
							<input id="email" name="email" type="text" class="form-control" value="<?= @$data->email ?>" required="required">
						</div>
					</div>
					<div class="form-group row">
						<label for="alamat" class="col-4 col-form-label">Alamat</label> 
						<div class="col-8">
							<textarea id="alamat" name="alamat" cols="40" rows="2" value="<?= @$data->id_karyawan ?>" class="form-control"><?= @$data->alamat ?></textarea>
						</div>
					</div> 
					<div class="form-group row">
				    <label for="select" class="col-4 col-form-label">Jabatan</label> 
				    <div class="col-8">
				      <select id="id_role" name="id_role" class="custom-select" >
				      	<?php foreach($roleData as $key => $val ): ?>
				        	<option value="<?= $val->id_role ?>" <?= $val->id_role == @$data->id_role ? 'selected' : '' ?> ><?= $val->nama_role ?> - Level <?= $val->role_level ?></option>
				        <?php endforeach ?>
				      </select>
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