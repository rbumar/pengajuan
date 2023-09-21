<form method="post" id="formPengajuan">
        <div class="col-12" style="text-align:end;">
        <button type="submit" name="submit" value="submit" class="btn btn-outline-success mx-auto text-center"><i class="fa fa-floppy-o" aria-hidden="true"></i> Simpan</button>
    </div>
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-body  px-3 py-5">
                <div class="row">
                    <label for="" class="col-sm-2 col-form-label">Peruntukan</label>
                    <div class="col-sm-6">
                        <input type="text" name="namaPengajuan" id="namaPengajuan" class="form-control" required>
                        <input type="hidden" name="idKaryawan" value="<?php echo $this->session->userdata('idKaryawan'); ?>">
                    </div>
                </div>
                <div class="row mt-3">
                    <label for="nominal" class="col-sm-2 col-form-label">Nominal Pengajuan</label>
                    <div class="col-sm-6">
                        <input type="number" name="nominal" id="nominal" class="form-control" required>
                    </div>
                </div>
                <div class="row mt-3">
                <label for="nominal" class="col-sm-2 col-form-label">Deskirpsi Pengajuan</label>
                    <div class="col-sm-6">
                        <textarea name="deskripsi" id="" cols="30" rows="5" class="form-control" required></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function(){
        $("#formPengajuan").on('submit',function(e){
            e.preventDefault();
            const dataPengajuan = new FormData(this);

            $.ajax({
                type:'POST',
                url:'<?= base_url("pettycash/simpanPettycash")?>',
                data:dataPengajuan,
                contentType:false,
                processData:false,
                success:function(res){
                    window.parent.load_content('<?= base_url("pettycash") ?>');
                },
                error:function(xhr,status,error){
                    alert(xhr.responseText);
                    console.log(error);
                }
            });
        });
    });
</script>

