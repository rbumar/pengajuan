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
.nav-tabs-custom .nav-item .nav-link {
    position: relative;
}
.nav-tabs-custom .nav-item .nav-link::after {
    content: "";
    position: absolute;
    background: #3eb7ba; /* Warna garis bawah saat diklik */
    height: 2px;
    width: 100%; /* Lebar awal garis bawah */
    left: 0;
    bottom: -2px; /* Jarak dari bawah .nav-link */
    -webkit-transition: scale 250ms ease-in-out; /* Efek transisi lebar */
    transition: scale 250ms ease-in-out;
    scale: 0;
}
.nav-tabs-custom .nav-item .nav-link.active::after {
    scale: 1; /* Lebar maksimum saat diklik */
}

</style>
<!-- button -->
<div class="col-md-4">
    <button class="btn btn-outline-success" id=formTambah><i class="fa fa-plus-circle" aria-hidden="true"></i> Pengajuan</button>
    <button class="btn btn-outline-info" id="segarkan"><i class="fa fa-refresh" aria-hidden="true"></i> <i class="fa-solid fa-arrows-rotate"></i>Refresh</button>
</div>

<!-- button status -->
<div class="card-body">
    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist" id="myTabs">
        <li class="nav-item" role="present">
            <a class="nav-link active" data-toggle="tab" href="#" id="status_all" onclick="tab_ubah_status('all')"
                role="tab" aria-selected="false">
                <span class="d-none d-sm-block">
                    ALL
                    <span class="badge badge-pill badge-primary">1</span>
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " data-toggle="tab" href="#" id="status_1" onclick="tab_ubah_status('Drafting')"
                role="tab" aria-selected="false">
                <span class="d-none d-sm-block">
                    OPEN <span class="badge badge-pill badge-warning"><?= $draft ?></span>
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#" id="status_2" onclick="tab_ubah_status('Pending')"
                role="tab" aria-selected="true">
                <span class="d-none d-sm-block">
                    SUBMITTED <span class="badge badge-pill badge-info"><?= $pending ?></span>
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#" id="status_3" onclick="tab_ubah_status('Approved')"
                role="tab">
                <span class="d-none d-sm-block">
                    APPROVED <span class="badge badge-pill badge-success"><?= $approved ?></span>
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#" id="status_4" onclick="tab_ubah_status('Rejected')"
                role="tab">
                <span class="d-none d-sm-block">
                    REJECTED <span class="badge badge-pill badge-danger"><?= $rejected ?></span>
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
                        <th>Pengajuan</th>
                        <th>Diberikan</th>
                        <th>Penggunaan</th>
                        <th>Sisa Uang</th>
                        <th>deskripsi</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot class="table-dark">
                    <tr>
                        <th colspan="3">Total Nominal Pengajuan</th>
                        <th colspan="7">
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
                        <th colspan="7">
                            <?php 
                            $totalNominalApprove = 0;
                            foreach($pengajuan as $pj){
                                $totalNominalApprove += $pj->nominal_approval;
                            }
                            echo "Rp. " .number_format($totalNominalApprove, 0, ',', '.');
                            ?>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3">Total Nominal yang digunakan</th>
                        <th colspan="7">
                        <?php 
                            $totalNominalApprove = 0;
                            foreach($pengajuan as $pj){
                                $totalNominalApprove += $pj->nominal_pj;
                            }
                            echo "Rp. " .number_format($totalNominalApprove, 0, ',', '.');
                            ?>
                        </th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php $nomor_urut =1; 
                    foreach ($pengajuan as $pgj) { 
                        $sisa = $pgj->nominal_approval - $pgj->nominal_pj;
                        ?>
                    
                    <tr class="<?= ($pgj->nominal_approval > 0 && $pgj->status == 'Approved' && $pgj->nominal_pj <=0 ? 'table-danger' : '') ?>">


                        <td><?= $nomor_urut ?></td>
                        <td><?= $pgj->nama_karyawan ?></td>
                        <td><?= $pgj->nama_pengajuan ?></td>
                        <td><?= "Rp. " . number_format($pgj->pettycash_nominal, 0, ',', '.'); ?></td>
                        <td><?= "Rp. " .number_format($pgj->nominal_approval, 0, ',', '.'); ?></td>
                        <td><?= "RP. " .number_format($pgj->nominal_pj,0,',','.'); ?></td>
                        <td><?= "RP. " . number_format($pgj->nominal_approval - $pgj->nominal_pj, 0, ',', '.'); ?></td>
                        <td><?= $pgj->pettycash_deskripsi ?></td>
                        <td><?= $pgj->status ?></td>
                        <td class="text-center">
                            <div class="dropdown mb-4">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-folder-open" aria-hidden="true"></i>
                                </button>
                                <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
                                    <button type="button" class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#detailPengajuans" id="detailPengajuansah" onclick="detailPengajuan('<?= $pgj->id_pengajuan ?>')" ><i class="fa fa-eye" aria-hidden="true"></i> Detail </button>
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
                                    <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#modalPJ" onclick="PJ('<?= $pgj->id_pengajuan ?>')" ><i class="fa fa-check-circle" aria-hidden="true"></i>Pertanggung Jawab</button>
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

<!-- form pertanggung jawaban bentul modal-->
<form id="formPJ" method="post">
    <div class="modal fade" id="modalPJ" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
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
                        <div class="col-5"><textarea name="deskripsi" id="deskripsi" cols="15" rows="5" class="form-control"></textarea></div>
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

<!-- detail pengajuan dengan model -->
<!-- Modal -->
<div class="modal fade" id="detailPengajuans" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fs-5" id="exampleModalLabel">Detail Pengajuan</h5>
      </div>
      <div class="modal-body">
        <div class="row">
            <label for="judul" class="col-4">Dibuat Oleh</label>
            <label for="value" class="col-8" id="dibuat">: </label>
        </div>
        <div class="row">
            <label for="judul" class="col-4">Disetujui Oleh</label>
            <label for="value" class="col-8" id="disetujui">:</label>
        </div>
        <div class="row">
            <label for="judul" class="col-4">status Pengajuan</label>
            <label for="value" class="col-8" id="statPengajuan">: </label>
        </div>
        <div class="row">
            <label for="judul" class="col-4">Tujuan Pembuatan</label>
            <label for="value" class="col-8" id="Tujuan">: aduh</label>
        </div>
        <div class="row">
            <table class="table" id="tableDetail">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Pengajuan</th>
                        <th scope="col">Disetujui</th>
                        <th scope="col">penggunaan</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
$(document).ready(function(){
    // menampilkan form untuk pengajuan
    $('#formTambah').on('click', function(e) {
    e.preventDefault();    
    console.log("berhasil di tampilkan");
    window.parent.load_content('<?= base_url("pettycash/form_tambah") ?>');
    });

    // refresh page
    $('#segarkan').on('click',function(e){
        e.preventDefault();
        window.parent.load_content('<?= base_url("pettycash") ?>');
    });
});

function formatNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function tab_ubah_status(status) {
    // Implementasi fungsi di sini
    $.ajax({
        type:'POST',
        url:'<?= base_url("pettycash/ambilData") ?>',
        data:{status:status},
        success:function(response){
            // console.log(response);
            $("#pengajuan tbody").empty();
            $("#pengajuan tfoot").empty();
            var responseObject = JSON.parse(response);
            var totNominal = 0;
            var totber =0;
            var totpj =0;

            $.each(responseObject, function(index, data) {
                // let row = '<tr class="<?= ($pgj->nominal_approval > 0 && $pgj->status == 'Approved' && $pgj->nominal_pj <=0 ? 'table-danger' : '') ?>">';
                let row = "<tr class='" + (parseInt(data.nominal_approval) > 0 && data.status == "Approved" && parseInt(data.nominal_pj) <= 0 ? "table-danger" : "") + "'>";
                row += '<td>' + (index + 1) + '</td>';
                row += '<td>' + data.nama_karyawan + '</td>';
                row += '<td>' + data.nama_pengajuan + '</td>';
                row += '<td> Rp. ' + formatNumber(parseInt(data.pettycash_nominal))+ '</td>';
                row += '<td> Rp. ' + formatNumber(parseInt(data.nominal_approval)) + '</td>';
                row += '<td> Rp. ' + formatNumber(parseInt(data.nominal_pj))+ '</td>';
                row += '<td> Rp. ' + formatNumber(parseInt(data.nominal_approval) - parseInt(data.nominal_pj) )+ '</td>';
                row += '<td>' + data.pettycash_deskripsi + '</td>';
                row += '<td>' + data.status + '</td>';
                row += '<td class="text-center">';
                row +='<div class="dropdown mb-4">';
                row +='<button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-folder-open" aria-hidden="true"></i></button>';
                row +='<div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">';
                row += '<button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#detailPengajuans" onclick="detailPengajuan(\'' + data.id_pengajuan + '\')"><i class="fa fa-eye" aria-hidden="true"></i>  Detail</button> ';              
                if (data.status == 'Approved' && data.nominal_approval == 0) {
                        row += '<button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#nominalFix" onclick="nominalFix(\'' + data.id_pengajuan + '\')" ><i class="fa fa-pencil"></i> Nominal Fix </button>';
                    }
                if (data.status == 'Pending') {
                    row += '<button class="dropdown-item" onclick="status(\'' + data.id_pengajuan + '\',\'Approved\',\'<?= $this->session->userdata("idKaryawan"); ?>\')"><i class="fa fa-check-circle" aria-hidden="true"></i> Approve</button>';
                    row += '<button class="dropdown-item" onclick="status(\'' + data.id_pengajuan + '\',\'Rejected\',\'<?= $this->session->userdata("idKaryawan"); ?>\')"><i class="fa fa-times-circle" aria-hidden="true"></i> Reject</button>';
                } 
                else if (data.status == 'Drafting') {
                    row += '<button class="dropdown-item" onclick="status(\'' + data.id_pengajuan + '\',\'Pending\',\'<?= $this->session->userdata("idKaryawan"); ?>\')"><i class="fa fa-check-circle" aria-hidden="true"></i> Submit</button>';
                }
                if (data.nominal_approval > 0) {
                    row += '<button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#modalPJ" onclick="PJ(\'' + data.id_pengajuan + '\')"><i class="fa fa-check-circle" aria-hidden="true"></i> Pertanggung Jawab</button>';
                }
                row +='</div></div></div>';
                row += '</td></tr>';

                $("#pengajuan tbody").append(row);
                totNominal+= parseInt(data.pettycash_nominal);
                totber+= parseInt(data.nominal_approval);
                totpj+= parseInt(data.nominal_pj);

                '</tr>'
            });
            let foot = '<tr >';
                foot+= '<th colspan="3">Total Nominal Pengajuan</th>';
                foot+='<th colspan="7"> Rp. '+ formatNumber(totNominal) +'</th></tr>';

                foot+= '<tr >';
                foot+= '<th colspan="3">Total Nominal yang diberikan</th>';
                foot+='<th colspan="7"> Rp. '+ formatNumber(totber) +'</th></tr>';

                foot+= '<tr >';
                foot+= '<th colspan="3">Total Nominal yang digunakan</th>';
                foot+='<th colspan="7"> Rp. '+ formatNumber(totpj) +'</th></tr>';

            $("#pengajuan tfoot").append(foot);
        },
        error:function(xhr,status,error){
            alert(xhr.responseText);
            console.log(error);
        }

    });
}

$(document).ready(function() {
    $('#pengajuan').DataTable({
        responsive: true
    });
    new $.fn.dataTable.FixedHeader( $('#pengajuan') );

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
            }
        });
    });

});




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
        }
    });
}

// fungsi untuk memproses penanggung jawaban
function PJ(id){
}

// untuk menampilkan detail pengajuan
function detailPengajuan(id){
    console.log(id,'berhasil');
    $.ajax({
        type:'POST',
        url:'<?= base_url("pettycash/detailPengajuan") ?>',
        data:{id:id},
        success:function(res){
            console.log(res);
            var resobj = JSON.parse(res);
            // Mengakses elemen pertama dalam array "all" (jika ada)
            var data = resobj.all[0];

            $("#dibuat").html(': ' + resobj.crd);
            $("#disetujui").html(': ' + resobj.acc);
            $("#statPengajuan").html(': ' + data.status);
            $("#tujuan").html(': ' + data.pettycash_deskripsi);

            let row = "<tr>";
            row += '<td>1</td>';
            row += '<td>' + data.pettycash_nominal + '</td>';
            row += '<td>' + data.nominal_approval + '</td>';
            row += '<td>' + data.nominal_pj + '</td>';
            row += "</tr>";

            let rows = "<tr><td>Keterangan</td>";
            rows += "<td colspan='3'> : " + data.pj_deskripsi + "</td></tr>";

            $("#tableDetail tbody").html(row);
            $("#tableDetail tfoot").html(rows);


                
        },
        error:function(xhr,status,error){
            alert(xhr.responseText);
            console.log(error);
        }
    });
}

</script>