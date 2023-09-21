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
					<input type="hidden" name="id" value="<?= @$data->id ?>">
					<?php foreach($column as $key => $valcol) : ?>
						<?php if($valcol['elem'] == 'text' && $valcol['show']){ ?>
							<div class="form-group row">
								<label  class="col-4 col-form-label"><?= $valcol['label'] ?></label> 
								<div class="col-8">
									<input id="<?= $key ?>" name="<?= $key ?>" value="<?= @$data->$key ?>"  type="<?= @$valcol['type'] ? $valcol['type'] : 'text' ?>" class="form-control <?=$valcol['readonly']?>" <?=$valcol['readonly']?>>
								</div>
							</div>
						<?php } ?>
						<?php if($valcol['elem'] == 'select' && $valcol['show']){ ?>
							<?php $valSelect = explode('-', $valcol['value']); ?>
							<?php if(!$isEdit){ ?>
							<div class="form-group row">
								<label  class="col-4 col-form-label"><?= $valcol['label'] ?></label> 
								<div class="col-8">
									<select id="<?= $key ?>" required name="<?= $key ?>" class="custom-select" <?= @$data->id_karyawan ? 'readonly' : ''?> >
										<option value="">-</option>
										<?php foreach($selectData[$valcol['data']] as $keyData => $valData): ?>
											<option value="<?= $valData->$valcol['key'] ?>" 
													email="<?= $valData->email?>" 
													nama="<?= $valData->nama_karyawan?>" 
													nama_role="<?= $valData->nama_role?>"
													>
													<?= $valData->$valSelect[0] ?> - <?= $valData->$valSelect[1] ?>	
											</option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<?php }else{ ?>
								<div class="form-group row">
								<label  class="col-4 col-form-label"><?= $valcol['label'] ?></label> 
								<div class="col-8">
									<select id="<?= $key ?>" readonly name="<?= $key ?>" class="custom-select"  >
										<option value="<?= $data->$valcol['key'] ?>" selected><?= $data->$valSelect[0] ?> - <?= $data->$valSelect[1] ?></option>
									</select>
								</div>
							</div>
							<?php } ?>
						<?php } ?>
					<?php endforeach ?>
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

			$('#id_karyawan').change(function(){
				let val = $('option:selected', this).attr('email');
				let val2 = $('option:selected', this).attr('nama');
				let val3 = $('option:selected', this).attr('nama_role');
				$('#email').val(val);
				$('#nama_karyawan').val(val2);
				$('#nama_role').val(val3);
			});

		});
	</script>