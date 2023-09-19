<h3>User</h3>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">User Table</h6>
    </div>
    <div class="card-body">
		<form id="user_form" class="col-12">
		  <div class="form-group row">
		    <label for="name" class="col-4 col-form-label">Name</label> 
		    <div class="col-8">
		      <input id="name" name="name" placeholder="Name" type="text" class="form-control" required="required">
		    </div>
		  </div>
		  <div class="form-group row">
		    <label for="text" class="col-4 col-form-label">Email</label> 
		    <div class="col-8">
		      <input id="email" name="email" placeholder="Email" type="text" class="form-control" required="required" aria-describedby="textHelpBlock"> 
		      <!-- <span id="textHelpBlock" class="form-text text-muted">This email will be the user name</span> -->
		    </div>
		  </div>
		  <div class="form-group row">
		    <label for="password" class="col-4 col-form-label">Password</label> 
		    <div class="col-8">
		      <input id="password" name="password" placeholder="Password" type="password" class="form-control" aria-describedby="passwordHelpBlock" required="required"> 
		      <!-- <span id="passwordHelpBlock" class="form-text text-muted">min 4 char</span> -->
		    </div>
		  </div> 
		  <div class="form-group row">
		    <div class="offset-4 col-8">
		    	<div class="alert alert-danger" id="user_form_alert" role="alert" style="display:none;">
				  
				</div>
		      <button name="submit" type="submit" class="btn btn-primary">Submit</button>

		    </div>
		  </div>
		</form>
</div>
</div>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">User Table</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Created by</th>
                        <th>Created date</th>
                        <!-- <th><a href="#" class="btn btn-success btn-sm btn-circle btn-sm">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </th> -->
                    </tr>
                </thead>
                <tbody>
                	<?php $no = 1 ?>
                	<?php 
                	foreach($user_data as $key => $value) :?>
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $value->email ?></td>
                        <td><?= $value->name ?></td>
                        <td><?= $value->status_txt ?></td>
                        <td><?= $value->last_login ?></td>
                        <td><?= $value->created_by ?></td>
                        <td><?= $value->created_date ?></td>
                        <!-- <td>
                        	<a href="#" class="btn btn-primary btn-sm btn-circle btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td> -->
                    </tr>
                <?php $no++ ?>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>public/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>public/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>public/js/demo/datatables-demo.js"></script>

<script type="text/javascript">
	function user_load_content(url){
		$('#main_page_menu').load(url, function(response, status, xhr){
			if(xhr.statusText !== 'OK'){
				$(this).html('<h1>Failed to load page</h1>');
			}
		})
	}
	$(document).ready(function(){
		const url = '<?php echo base_url('user');?>' ;
		user_load_content(url);
		$('#btn').click(function(){
			// user_load_content(url);
			window.parent.load_content(url);
		});

		$('#user_form').on("submit", function(event){
        	event.preventDefault();
 
	        var formValues= $(this).serialize();
	 
	        $.post('<?php echo base_url("user/add_user");?>', formValues, function(data){
	            if(data !== 'OK'){
	            	$('#user_form_alert').html(data);
	            	$('#user_form_alert').show();
	            }else{
	       			window.parent.load_content(url);     	
	            }
	        });
	    });
	})
</script>