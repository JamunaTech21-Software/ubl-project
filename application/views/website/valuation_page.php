<div class="container text-center">    
	<div class="row">

	  <?php
		  include("login_left_bar.php");
		  $uri_segment = $this->uri->segment(2);
	  ?>    
    
    <div class="col-sm-6">
      <div class="row">
        <div class="col-sm-12">
			<b>
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/web_user_home"); ?>">Home</a></li>
					<?php if($uri_segment=='valuation_entry'){ ?>
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/brokers_entry"); ?>">Data Entry</a></li>
					<li class="breadcrumb-item active" aria-current="page">Valuation Entry</li>
					<?php }else{ ?>
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/brokers_reports"); ?>">Reports</a></li>
					<li class="breadcrumb-item active" aria-current="page">Valuation</li>
					<?php } ?>
				  </ol>
				</nav>
			</b> 
        </div>
      </div>
      
      <!-- start row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="well" id="form_group_element">
            <form target="blink" action="<?php echo site_url('MY_Controller/get_catalogue_prepared_data'); ?>" method="get">

                <div class="form-group">
				<?php if($uri_segment=='valuation_entry'){ ?>
					<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Valuation Entry <small class="text-danger">(Unpublished Valuation Sale No.)</small></label>
				<?php }else{ ?>
					<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Valuation <small class="text-danger">(Published Valuation Sale No.)</small></label>
				<?php } ?>	
                </div>
				
				<hr />
				
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="comment">Season</label>
						<select required class="form-control input-sm" id="season" name="season" <?php if($uri_segment=='valuation_entry'){ ?> onchange="pre_valuation_sale_no();" <?php }else{ ?> onchange="get_universal_sale_no();" <?php } ?>>
						<?php 
							foreach($season_data as $season_list){
								$selected = ($season_list['season_name'] == $current_season_data->season_name) ? 'selected' : '';
							  	print "<option value=\"$season_list[season_name]\" $selected>$season_list[season_name]</option>";
							}
						?>
						</select>
					</div>
					<div class="form-group col-sm-6">
						<label for="email">Auction Center</label>
						<select required class="form-control input-sm" name="auction_center" id="auction_center" <?php if($uri_segment=='valuation_entry'){ ?> onchange="pre_valuation_sale_no();" <?php }else{ ?> onchange="get_universal_sale_no();" <?php } ?>>
							<option value="">Select</option>
							<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
							<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
						</select>
					</div>
					<div class="form-group col-sm-6">
						<label for="comment">Catalog</label>
						<select required class="form-control input-sm" id="catalouge_name" name="catalouge_name">
							<option value="">Select Catalogue</option>
								<option value="Full Catalogue">Full Valuation</option>
							<?php 
							foreach($catalogue_data as $catalogue_list){
							  print "<option value=\"$catalogue_list[catalouge_name]\">$catalogue_list[catalouge_name]</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group col-sm-6">
						<label for="comment">Sale No</label>
						<div id="load_sale_no">
						<select required class="form-control input-sm" id="sale_no" name="sale_no">
							<option value="">Select Sale No</option>
						</select>	
						</div>
					</div>
					<div class="form-group col-sm-6">
						<label for="comment">Prepared to do</label>
						<select required class="form-control input-sm" name="prepared_to" id="prepared_to">
							<?php if($uri_segment=='valuation_entry'){ ?>	
							<option selected value="Valuation"> Valuation Prepared </option>
							<?php }else{ ?>
							<option selected value="Valuation Print"> Valuation Final </option>
							<?php } ?>
						</select>
					</div>
				</div>	
                <input type="submit" name="Submit" id="Submit" value="Search Data" class="btn btn-sm btn-primary">
            </form>    
          </div>
        </div>
      </div> 
      <!-- end row -->

    </div>
	<!-- end col -->
	
		<?php
			include("login_right_bar.php");
		?>

	</div> 
	<!-- /row -->
</div>

<script>
$(function(){
	<?php if($uri_segment=='valuation_entry'){ ?> pre_valuation_sale_no(); <?php }else{ ?> get_universal_sale_no(); <?php } ?>
});
</script>