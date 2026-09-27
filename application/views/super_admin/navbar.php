<nav class="navbar navbar-expand-lg navbar-light bg-dark">
  <a class="navbar-brand" href="#">Tea e-Auction</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">

      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('MY_Controller/sa_home'); ?>"><img src="<?php echo base_url().'assets/img/'.$username.'.jpg'; ?>" width=15 class="rounded-circle" alt="Admin Photo"> <?php print $username; ?></a>
      </li>

      <li class="nav-item active">
        <a class="nav-link" href="<?php echo site_url('MY_Controller/sa_home'); ?>">Home <span class="sr-only">(current)</span></a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Setup
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item <?=($this->uri->segment(2)==='garden_and_company_setup')?'active':''?>" href="<?php echo site_url('MY_Controller/garden_and_company_setup'); ?>">Garden And Company</a>
          <div class="dropdown-divider"></div>
		  <a class="dropdown-item <?=($this->uri->segment(2)==='grade_setup')?'active':''?>" href="<?php echo site_url('MY_Controller/grade_setup'); ?>">Tea Grade Information</a>
         <div class="dropdown-divider"></div>
		  <a class="dropdown-item <?=($this->uri->segment(2)==='buyer_setup')?'active':''?>" href="<?php echo site_url('MY_Controller/buyer_setup'); ?>">Buyer Information</a>
          <div class="dropdown-divider"></div>
		  <a class="dropdown-item <?=($this->uri->segment(2)==='warehouse_setup')?'active':''?>" href="<?php echo site_url('MY_Controller/warehouse_setup'); ?>">Warehouse Information</a>
		  <div class="dropdown-divider"></div>
          <a class="dropdown-item <?=($this->uri->segment(2)==='season_setup')?'active':''?>" href="<?php echo site_url('MY_Controller/season_setup'); ?>">Season Information</a>
		  <div class="dropdown-divider"></div>
          <a class="dropdown-item <?=($this->uri->segment(2)==='sale_schedule_setup')?'active':''?>" href="<?php echo site_url('MY_Controller/sale_schedule_setup'); ?>">Sale Schedule Information</a>
		  <div class="dropdown-divider"></div>
		  <a class="dropdown-item <?=($this->uri->segment(2)==='sale_order_setup')?'active':''?>" href="<?php echo site_url('MY_Controller/sale_order_setup'); ?>">Sale Order Information</a>
		  <div class="dropdown-divider"></div>
          <a class="dropdown-item <?=($this->uri->segment(2)==='catalogue_setup')?'active':''?>" href="<?php echo site_url('MY_Controller/catalogue_setup'); ?>">Catalogue Information</a>
		  <div class="dropdown-divider"></div>
          <a class="dropdown-item <?=($this->uri->segment(2)==='sample_draw_process')?'active':''?>" href="<?php echo site_url('MY_Controller/sample_draw_process'); ?>">Sample Draw Process</a>
		  <div class="dropdown-divider"></div>
          <a class="dropdown-item <?=($this->uri->segment(2)==='chest_setup')?'active':''?>" href="<?php echo site_url('MY_Controller/chest_setup'); ?>">Chest Information</a>
		  <div class="dropdown-divider"></div>
          <a class="dropdown-item <?=($this->uri->segment(2)==='brokers_setup')?'active':''?>" href="<?php echo site_url('MY_Controller/brokers_setup'); ?>">Brokers Information</a>
		  <div class="dropdown-divider"></div>
          <a class="dropdown-item <?=($this->uri->segment(2)==='regulatory_setup')?'active':''?>" href="<?php echo site_url('MY_Controller/regulatory_setup'); ?>">Regulatory Information</a>
		  
          <div class="dropdown-divider"></div>
		  
          <a class="dropdown-item <?=($this->uri->segment(2)==='sale_condtion_setup')?'active':''?>" href="<?php echo site_url('MY_Controller/sale_condtion_setup'); ?>">Condition of sale</a>
		  
		  <div class="dropdown-divider"></div>
		  
          <a class="dropdown-item <?=($this->uri->segment(2)==='vat_tax_sample_setup')?'active':''?>" href="<?php echo site_url('MY_Controller/vat_tax_sample_setup'); ?>">Vat / Tax / Sample Info</a>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('MY_Controller/admin_auction_board'); ?>">Auction board</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('MY_Controller/logout'); ?>">Logout (<?php print substr($username,0,5); ?>)</a>
      </li>
    </ul>

    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>