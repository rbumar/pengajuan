<script src="<?php echo base_url(); ?>public/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>public/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
	function refreshView(){
		const url = '<?= @$url ?>';
		window.parent.load_content(url);
	}

	function tambahView(){
		const url = '<?= @$form ?>';
		window.parent.load_content(url);
	}

	function kembaliView(){
		const url = '<?= @$url ?>';
		window.parent.load_content(url);
	}

</script>