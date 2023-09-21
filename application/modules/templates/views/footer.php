 </div>
 <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2020</span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Are you sure ?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="<?php echo base_url('auth/sign_out'); ?>">Logout</a>
        </div>
    </div>
</div>
</div>


</body>

<!-- Bootstrap core JavaScript-->
<script src="<?php echo base_url(); ?>public/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?php echo base_url(); ?>public/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?php echo base_url(); ?>public/js/sb-admin-2.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

<script type="text/javascript">

    function load_content(url){
        $('#main_content_page').load(url, function(response, status, xhr){
            if(xhr.statusText !== 'OK'){
                $(this).html('<h1 class="danger">Failed to load page</h1>');
            }
        });
    }

    $(document).ready(function(){
        $('.collapse-item').click(function(){
            $('.collapse-item').removeClass('active');
            $(this).addClass('active');
            let url = $(this).attr('url');
            load_content(url);
        });
    });

    $(document).ajaxStart(function(){
        $.LoadingOverlay("show", {
            image       : "",
            fontawesome : "fa fa-cog fa-1x fa-spin",
            fontawesomeAutoResize : true,
            fontawesomeResizeFactor : 0.3
        });
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });

</script>
</html>