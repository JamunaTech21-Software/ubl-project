<style>
body {
  background: #FFFFFF !important;
}
</style>

<!-- Example row of columns -->
<h1 class="mt-4">Dashboard</h1>

<!-- breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page"><strong><a href="<?php echo site_url('MY_Controller/sa_home'); ?>">Dashboard</a></strong></li>
    </ol>
  </nav>
<!-- end breadcrumb -->

  <div class="card">

    <div class="card-header">
      Featured
    </div>

    <div class="card-body">
      <div class="row">
        <div class="col-md-3">
          <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
              <h5 class="card-title">Primary card title</h5>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
              <h5 class="card-title">Info card title</h5>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
              <h5 class="card-title">Success card title</h5>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
              <h5 class="card-title">Dark card title</h5>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
          </div>
        </div>
      </div> <!--row-->
  </div> <!--card body-->
</div> <!--card-->

<br>

<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
      <svg class="bi bi-pie-chart-fill" width="32" height="32" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path d="M17.985 10.5h-7.778l-5.5 5.5a8 8 0 0013.277-5.5zM4 15.292A8 8 0 019.5 2.015v7.778l-5.5 5.5zm6.5-13.277V9.5h7.485A8.001 8.001 0 0010.5 2.015z"></path>
      </svg> Pie Chart
      </div>
      <div class="card-body">
        <div>
          <canvas id="pieChart" style="max-width: 500px;"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
      <svg class="bi bi-bar-chart-fill" width="32" height="32" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <rect width="4" height="5" x="3" y="12" rx="1"></rect>
        <rect width="4" height="9" x="8" y="8" rx="1"></rect>
        <rect width="4" height="14" x="13" y="3" rx="1"></rect>
      </svg> Bar Chart
      </div>
      <div class="card-body">
        <div>
          <canvas id="barChart" style="max-width: 500px;"></canvas>
        </div>
      </div>
    </div>
  </div>
</div><!--row-->

<br>

<div class="card">
      <div class="card-header">
      <svg class="bi bi-table" width="32" height="32" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M16 3H4a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1zM4 2a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4z" clip-rule="evenodd"></path>
        <path fill-rule="evenodd" d="M17 6H3V5h14v1z" clip-rule="evenodd"></path>
        <path fill-rule="evenodd" d="M7 17.5v-14h1v14H7zm5 0v-14h1v14h-1z" clip-rule="evenodd"></path>
        <path fill-rule="evenodd" d="M17 10H3V9h14v1zm0 4H3v-1h14v1z" clip-rule="evenodd"></path>
        <path d="M2 4a2 2 0 012-2h12a2 2 0 012 2v2H2V4z"></path>
      </svg> Data Table
      </div>
      <div class="card-body">
      <table class="table table-bordered table-hover" id="mydata">
        <caption>List of Warehouse</caption>
        <thead class="thead-dark">
        <tr>
            <th>SL</th>
            <th>Warehouse <br> Name</th>
            <th>Warehouse <br> SL 1</th>
            <th>Warehouse <br> SL 2</th>
            <th>Contact No</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        </thead>  

        <tbody>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tbody>
      </table>  
      </div>
    </div>

<!-- chart js-->
<script type="text/javascript" src="<?php echo base_url().'assets/js/Chart.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url().'assets/js/mdb.js'; ?>"></script>