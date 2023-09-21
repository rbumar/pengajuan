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
						<label for="nama_role" class="col-4 col-form-label">Nama</label> 
						<div class="col-8">
							<input id="nama_role" name="nama_role" type="text" value="<?= @$data->nama_role ?>" class="form-control" required="required">
							<input id="id_role" name="id_role" type="hidden" value="<?= @$data->id_role ?>" class="form-control" required="required">
						</div>
					</div>
					<div class="form-group row">
						<label for="nama_role" class="col-4 col-form-label">Level</label> 
						<div class="col-8">
							<input id="role_level" name="role_level" type="number" value="<?= @$data->role_level ?>" class="form-control" required="required">
						</div>
					</div>
					<div class="form-group row">
						<label for="deskripsi" class="col-4 col-form-label">Deskripsi</label> 
						<div class="col-8">
							<textarea id="deskripsi" name="deskripsi" cols="40" rows="2" value="<?= @$data->deskripsi ?>" class="form-control"><?= @$data->deskripsi ?></textarea>
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