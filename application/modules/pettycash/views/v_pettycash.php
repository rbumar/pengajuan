<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    /* warna dasar background ini #f8f9fc */
.alert-info {
    color: #3f3a63 !important;
    background-color: #e4e2f2 !important;
    border-color: #dad7ed !important;
}

.nav-tabs-custom {
    border-bottom: 2px solid #dee2e6;
}

.nav-tabs-custom .nav-item .nav-link {
    border: none;
}

.nav-link.active{
    background-color: #f8f9fc !important;
}

.nav-tabs-custom .nav-item .nav-link.active {
    color: #3eb7ba;
}
.nav-tabs-custom .nav-item .nav-link::after {
    content: "";
    background: #3eb7ba !important;
    height: 2px;
    position: absolute;
    width: 100%;
    left: 0;
    bottom: -1px;
    -webkit-transition: all 250ms ease 0s;
    transition: all 250ms ease 0s;
    -webkit-transform: scale(0);
    transform: scale(0);
}
.nav-tabs-custom .nav-item .nav-link.active:after {
    -webkit-transform: scale(1);
    transform: scale(1);
}

</style>
<!-- button -->
<div class="col-md-4">
    <button class="btn btn-outline-success" id=formTambah><i class="fa fa-plus-circle" aria-hidden="true"></i> Pengajuan</button>
    <button class="btn btn-outline-info" id="segarkan"><i class="fa fa-refresh" aria-hidden="true"></i> <i class="fa-solid fa-arrows-rotate"></i>Refresh</button>
</div>

<!-- button status -->
<div class="card-body">
    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
        <li class="nav-item" role="present">
            <a class="nav-link active" data-toggle="tab" href="javascript:void(0)" id="status_all" onclick="tab_status(null)"
                role="tab" aria-selected="false">
                <span class="d-none d-sm-block">
                    ALL
                    <span class="badge badge-pill badge-primary">1</span>
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " data-toggle="tab" href="javascript:void(0)" id="status_1" onclick="tab_status(1)"
                role="tab" aria-selected="false">
                <span class="d-none d-sm-block">
                    OPEN <span class="badge badge-pill badge-warning">0</span>
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="javascript:void(0)" id="status_2" onclick="tab_status(2)"
                role="tab" aria-selected="true">
                <span class="d-none d-sm-block">
                    SUBMITTED <span class="badge badge-pill badge-info">1</span>
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="javascript:void(0)" id="status_3" onclick="tab_status(3)"
                role="tab">
                <span class="d-none d-sm-block">
                    APPROVED <span class="badge badge-pill badge-success">0</span>
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="javascript:void(0)" id="status_4" onclick="tab_status(4)"
                role="tab">
                <span class="d-none d-sm-block">
                    REJECTED <span class="badge badge-pill badge-danger">0</span>
                </span>
            </a>
        </li>
    </ul>
</div>

<!-- card table -->
<div class="card shadow my-4">
    <div class="card-header alert-info h4 mb-1">Pengajuan Pettycash</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="pengajuan" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pengaju</th>
                        <th>Mengajukan</th>
                        <th>nominal</th>
                        <th>Nominal diberikan</th>
                        <th>deskripsi</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot class="table-dark">
                    <tr>
                        <th colspan="3">Total Nominal Pengajuan</th>
                        <th colspan="5">
                        <?php
                            $totalNominal = 0;
                            foreach ($pengajuan as $pj) {
                                $totalNominal += $pj->pettycash_nominal;
                            }
                            echo "Rp. " . number_format($totalNominal, 0, ',', '.');
                            ?>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3">total nominal yang diberikan</th>
                        <th colspan="5">
                            <?php 
                            $totalNominalApprove = 0;
                            foreach($pengajuan as $pj){
                                $totalNominalApprove += $pj->nominal_approval;
                            }
                            echo "Rp. " .number_format($totalNominalApprove, 0, ',', '.');
                            ?>
                        </th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php $nomor_urut =1; 
                    foreach ($pengajuan as $pgj) { ?>
                    
                    <tr class="<?= $pgj->nominal_approval > 0 ? 'table-danger' : '' ?>  <?= $pgj->nominal_pj > $pgj->nominal_approval && $pgj->nominal_pj != 0 ?'table-info' : 'table->success'  ?>">
                        <td><?= $nomor_urut ?></td>
                        <th><?= $pgj->nama_karyawan ?></th>
                        <th><?= $pgj->nama_pengajuan ?></th>
                        <th><?php
                        $nominal = "Rp. " . number_format($pgj->pettycash_nominal, 0, ',', '.');
                        echo $nominal;
                         ?></th>
                        <th><?php
                        $nominalApproval = "Rp. ". number_format($pgj->nominal_approval, 0, ',', '.');
                        echo $nominalApproval; ?></th>
                        <th><?= $pgj->pettycash_deskripsi ?></th>
                        <th><?= $pgj->status ?></th>
                        <th class="text-center">
                            <div class="dropdown mb-4">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-folder-open" aria-hidden="true"></i>
                                </button>
                                <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#"><i class="fa fa-eye" aria-hidden="true"></i> Detail </a>
                                    <!-- ketika status approved muncul maka nominal fix yang diberikan akan muncul -->
                                    <?php if($pgj->status == 'Approved' && $pgj->nominal_approval == 0){ ?>
                                           <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#nominalFix" onclick="nominalFix('<?= $pgj->id_pengajuan ?>' )" ><i class="fa fa-pencil"></i> Nominal Fix </button>
                                    
                                     <!-- ketika status pending maka akan muncul menu approved atau reject -->
                                    <?php } 
                                        if($pgj->status == 'Pending'){?>
                                    <button class="dropdown-item" onclick="status('<?= $pgj->id_pengajuan ?>','Approved','<?= $this->session->userdata('idKaryawan'); ?>')"><i class="fa fa-check-circle" aria-hidden="true"></i>Approve</button>
                                    <button class="dropdown-item" onclick="status('<?= $pgj->id_pengajuan ?>','Rejected','<?= $this->session->userdata('idKaryawan'); ?>')"><i class="fa fa-times-circle" aria-hidden="true"></i> Reject</button>
                                    <?php } ?>
                                    
                                    <!-- jika uang sudah diterima, maka akan muncul pertanggung jawaban -->
                                    <?php
                                    if($pgj->nominal_approval > 0){ 
                                    ?>
                                    <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#formPJ" onclick="PJ('<?= $pgj->id_pengajuan ?>')" ><i class="fa fa-check-circle" aria-hidden="true"></i>Pertanggung Jawab</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </th>
                    </tr>
                    
                    <?php $nomor_urut++; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- modal editing nominal yang diberikan -->
<form id="formNominal" method="post">
    <div class="modal fade" id="nominalFix" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="exampleModalLabel">Nominal Yang Diberikan</h5>
                    <input type="hidden" name="id_pengajuan" id="inputNominalFix">
                </div>
                <div class="modal-body">
                    <div class="row p-3">
                        <label for="nominal" class="col-6">Nominal Yang Diberikan</label>
                        <div class="col-5"><input type="number" name="nominalDiberikan" id="" class="form-control" required></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form id="formPJ" method="post">
    <div class="modal fade" id="formPJ" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="exampleModalLabel">Form Penanggung Jawab</h5>
                    <input type="hidden" name="idPengajuan" id="idPengajuan">
                </div>
                <div class="modal-body">
                    <div class="row p-3">
                        <label for="nominal" class="col-6">Nominal Yang Digunakan</label>
                        <div class="col-5"><input type="number" name="nominalDigunakan" id="" class="form-control" required></div>
                    </div>
                    <div class="row p-3">
                        <label for="nominal" class="col-6">Deskripsi</label>
                        <div class="col-5"><textarea name="deskripsi" id="deskripsi" cols="30" rows="5"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
$(document).ready(function() {
    $('#pengajuan').DataTable({
        responsive: true
    });

    // mengirimkan nilai ke sisi server
    $("#formNominal").on('submit',function(e){
        e.preventDefault();
        // mengambil data di inputan
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: '<?= base_url('pettycash/nominalDiberikan'); ?>',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // alert('data berhasil ditambahkan');
                $('#nominalFix').modal('hide');
                // $('#pengajuan').html(response.updateData);
                window.parent.load_content('<?= base_url("pettycash") ?>');
            },
            error:function(xhr,status,error){
                alert(xhr.responseText);
                console.log(error);
            }
        });
    });

    // menampilkan form untuk pengajuan
    $('#formTambah').on('click', function(e) {
    e.preventDefault();    
    window.parent.load_content('<?= base_url("pettycash/form_tambah") ?>');
    });

    // refresh page
    $('#segarkan').on('click',function(e){
        e.preventDefault();
        window.parent.load_content('<?= base_url("pettycash") ?>');
    });

});


// untuk mengambil data berdasarkan statusnya
function tab_status(status){
    event.preventDefault();
    console.log(status);

}

// perubahan nominal yang diberikan
function nominalFix(id){
    $('#inputNominalFix').val(id);
}

function status(id, status,idKaryawan) {
    $.ajax({
        type: 'POST',
        data: { id: id, status: status,idKaryawan : idKaryawan },
        url: '<?= base_url('pettycash/ubahStatus'); ?>',
        success: function (response) {
            window.parent.load_content('<?= base_url("pettycash") ?>');
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
            console.log(error);
        }
    });
}

// fungsi untuk memproses penanggung jawaban
function PJ(id){

}

</script>