<div class="container text-center">    
	<div class="row">

	  <?php
		  include("login_left_bar.php");
	  ?>    
    
		<div class="col-sm-6">
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-default text-center">
						<div class="panel-body">
							<b style="font-size:18px;"> <span class="glyphicon glyphicon-hand-right"></span> &nbsp; DASHBOARD &nbsp; <span class="glyphicon glyphicon-hand-left"></span></b>
						</div>
					</div>
				</div>
			</div>
		  
			<div class="row">
				<div class="col-sm-6">
					<div class="well">
						<p>
						  <?php print $upcoming_catalogue; ?>
						</p>
					</div>
				</div>
			</div>     
		</div>

		<?php
			include("login_right_bar.php");
		?>
		
	</div> 
	<!-- /row -->
</div>