<h2>Super Admin Login</h2>
<hr>

<?php 
  echo isset($error) ? "<div class=\"alert alert-danger\" role=\"alert\"> Warning !! ". $error ."</div>" : ''; 

  echo !empty($this->session->flashdata('data_name')) ? "<div class=\"alert alert-danger\" role=\"alert\">". $this->session->flashdata('data_name') ."</div>" : ''; 
?>

<form action="<?php echo site_url('MY_Controller/super_admin_login_action'); ?>" method="post" accept-charset="utf-8" class="was-validated">
  
  <div class="form-group">
    <label for="uname">Username:</label>
    <input type="text" class="form-control" id="uname" placeholder="Enter username" name="uname" required>
    <div class="valid-feedback">Valid.</div>
    <div class="invalid-feedback"><?php echo form_error('uname'); ?></div>
  </div>

  <div class="form-group">
    <label for="pwd">Password:</label>
    <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="psw" required>
    <div class="valid-feedback">Valid.</div>
    <div class="invalid-feedback"><?php echo form_error('psw'); ?></div>
  </div>

  <!--
  <div class="form-group form-check">
    <label class="form-check-label">
      <input class="form-check-input" type="checkbox" name="remember" required> I agree on blabla.
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Check this checkbox to continue.</div>
    </label>
  </div>
  -->
  <button type="submit" class="btn btn-primary">Submit</button>
</form>