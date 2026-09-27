<div class="row">
	<div class="form-group col-sm-12">
		<div id="demo" class="carousel slide" data-ride="carousel">

		  <!-- Indicators -->
		  <ul class="carousel-indicators">
			<!--<li data-target="#demo" data-slide-to="0" class="active"></li>
			<li data-target="#demo" data-slide-to="1"></li>
			<li data-target="#demo" data-slide-to="2"></li>-->
		  </ul>

		  <!-- The slideshow -->
		  <div class="carousel-inner">
			<div class="carousel-item active">
			  <img src="<?php echo base_url().'assets/img/slider/1.jpg'; ?>" alt="Unity Brokers LTD" />
			</div>
			<?php
			/*
			<div class="carousel-item">
			  <img src="<?php echo base_url().'assets/img/slider/2.jpg'; ?>" alt="Chicago">
			</div>
			<div class="carousel-item">
			  <img src="<?php echo base_url().'assets/img/slider/1.jpg'; ?>" alt="New York">
			</div>
			*/
			?>
		  </div>

		  <!-- Left and right controls -->
		  <a class="carousel-control-prev" href="#demo" data-slide="prev">
			<span class="carousel-control-prev-icon"></span>
		  </a>
		  <a class="carousel-control-next" href="#demo" data-slide="next">
			<span class="carousel-control-next-icon"></span>
		  </a>

		</div>
	</div>
</div>
<!-- /.row -->

<!-- row -->
<div class="row justify-content-md-center">
	<div class="form-group col-sm-8">
		<div class="card border-danger">
			<div class="card-header">
				<b>Sign in to automation system</b>
			</div>
			<div class="card-body">
				<?php 
				echo isset($web_error) ? "<div class=\"alert alert-danger\" role=\"alert\"> Warning !! ". $web_error ."</div>" : ''; 

				echo !empty($this->session->flashdata('web_data_name')) ? "<div class=\"alert alert-danger\" role=\"alert\">". $this->session->flashdata('web_data_name') ."</div>" : ''; 
				?>

				<form action="<?php echo site_url('MY_Controller/web_login_action'); ?>" method="post" accept-charset="utf-8" class="was-validated">

				  <div class="form-group">
					<input required type="text" class="form-control" placeholder="Enter email" name="uname" id="uname" value="<?php if(get_cookie('uemail')){ print get_cookie('uemail'); } ?>">
					<div class="valid-feedback">Valid.</div>
					<div class="invalid-feedback"><?php echo form_error('uname'); ?></div>
				  </div>

				  <div class="form-group">
					<div class="input-group">
					  <input required type="password" class="form-control" maxlength="12" placeholder="Enter password" name="psw" id="psw" value="<?php if(get_cookie('upassword')){ print get_cookie('upassword'); } ?>">
					  <div class="input-group-append">
						<button class="btn btn-primary" type="button" id="togglePassword" style="border-top-left-radius: 0; border-bottom-left-radius: 0; min-width: 46px;">
						  <i class="fas fa-eye"></i>
						</button>
					  </div>
					</div>
					<div class="valid-feedback">Valid.</div>
					<div class="invalid-feedback"><?php echo form_error('psw'); ?></div>
				  </div>

				  <div class="form-group form-check">
					<label class="form-check-label">
					  <input class="form-check-input" name="chkremember" id="chkremember" type="checkbox" <?php if(get_cookie('uemail')){ ?> checked="checked" <?php } ?>> Remember me
					</label>
				  </div>

				  <button type="submit" class="btn btn-primary">Login</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.row -->

<!-- Add Font Awesome CSS if not already included -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('psw');
    
    togglePassword.addEventListener('click', function() {
        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle the eye icon
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
});
</script>