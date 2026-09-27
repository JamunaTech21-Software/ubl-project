<!-- breadcrumb -->
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo site_url('MY_Controller/sa_home'); ?>">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Setup</a></li>
      <li class="breadcrumb-item active" aria-current="page"><strong>Vat / Tax And Sample Information</strong></li>
    </ol>
  </nav>
  <!-- end breadcrumb -->

  <!-- form action -->
  <form id="vat_tax_sample_details" action="#">

    <!-- fixed value -->
    <input required type="hidden" class="form-control" name="setup_id" id="setup_id">
    <input required type="hidden" class="form-control" name="user" id="user" value="<?php print $username; ?>">
    <input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $username .', Date: '. $today .', IP: '. $ip; ?>">
    <!-- End fixed data -->
    
    <div class="card">
      <div class="card-header">
        <b class="font-weight-bold"> Vat / Tax And Sample Policy # <span id="form_mode" class="badge badge-success">Add New</span></b>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label for="email">Terms & Conditions</label>
          <textarea required class="form-control" name="vat_tax_sample" id="vat_tax_sample" placeholder="Type Conditions"><?php print $no_of_total_data; ?></textarea>
        </div>

        <input type="submit" value="Submit" id="Submit" class="btn btn-primary">

      </div>
    </div>
    
  </form>
  <!-- end form action -->