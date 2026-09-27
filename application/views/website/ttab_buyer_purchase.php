<div class="container text-center">    
	<div class="row">

		<?php
			include("login_left_bar.php");
		?>    
    
    <div class="col-sm-6">
      <div class="row">
        <div class="col-sm-12">
			<b>
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/web_user_home"); ?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/brokers_reports"); ?>">Reports</a></li>
					<li class="breadcrumb-item active" aria-current="page">Buyer Purchase Statement</li>
				  </ol>
				</nav>
			</b> 
        </div>
      </div>
      
      <!-- start row -->
      <div class="row">
        <div class="col-sm-12">
			<div class="well" id="form_group_element">
            
                <div class="form-group">
                    <label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Buyer Purchase Statement</label>
                </div>
				
				<hr />
				
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="comment">Season</label>
						<select required class="form-control input-sm" id="season" name="season" onchange="after_sale_sale_no();">
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
						<select required class="form-control input-sm" name="auction_center" id="auction_center" onchange="after_sale_sale_no();">
							<option value="">Select</option>
							<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
							<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
						</select>
					</div>
					<div class="form-group col-sm-6">
						<label for="comment">Sale No</label>
						<div id="load_sale_no">
							<select required class="form-control input-sm" id="sale_no" name="sale_no">
								<option value="">Select</option>
							</select>	
						</div>
					</div>
					<div class="form-group col-sm-6">
						<label for="email">Combine With</label>
						<select class="form-control input-sm" id="combine_center">
							<option value="">Select</option>
							<option value="Sreemangal">Sreemangal</option>
							<option value="Chattogram">Chattogram</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-12">
						
						<input type="submit" id="button" value="BTB Buyer Purchase" style="text-transform:uppercase;" title="Buyer Purchase Tea Board Statement" onclick="fn_view_report5(season.value,auction_center.value,sale_no.value,'TB',combine_center.value);" class="btn btn-sm btn-primary" />
						
						<input type="submit" id="button" value="TTAB Buyer Purchase" style="text-transform:uppercase;" title="TTAB Buyer Purchase Statement" onclick="fn_view_report10(season.value,auction_center.value,sale_no.value,'TTAB_BP_list',combine_center.value);" class="btn btn-sm btn-primary" /> 
						
					</div>

					<div class="form-group col-sm-12">

						<input type="submit" id="button" value="BTB Buyer Purchase Upto" style="text-transform:uppercase;" title="BTB Buyer Purchase Upto Statement" onclick="fn_view_report11(season.value,auction_center.value,sale_no.value,'TTAB_BP_upto_list',combine_center.value);" class="btn btn-sm btn-primary" /> 

					</div>
				</div>	
			</div>
        </div>
      </div> 
      <!-- /row -->
  
    </div>
	<!-- /col -->
    <?php
      include("login_right_bar.php");
    ?>

	</div> 
	<!-- \row -->
</div>

<script>
$(function(){
	after_sale_sale_no();
});


function fn_view_report5(season,auction_center,sale_no,report_name,combine_center){
	
	// validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// print window
	if(combine_center=='' || auction_center==combine_center){
		
		window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"&combine_center="+combine_center+"'); ?>","Tea Board Statement","toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=100,width=1300,height=600");
		
	}else{
		
		window.open("<?php echo site_url('MY_Controller/tb_bpr_all_center?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"&combine_center="+combine_center+"'); ?>","Tea Board Statement - Combine","toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=100,width=1200,height=600");
	}
}


function fn_view_report10(season,auction_center,sale_no,report_name,combine_center){
	
	// validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// print window
	if(combine_center=='' || auction_center==combine_center){
		
		window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>","TTAB Buyer Purchase Statement - ","toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=100,width=1200,height=600");
		
	}else{
		
		window.open("<?php echo site_url('MY_Controller/ttab_bpr_all_center?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"&combine_center="+combine_center+"'); ?>","TTAB Buyer Purchase Statement - Combine","toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=100,width=1200,height=600");
	}	
}

function fn_view_report11(season,auction_center,sale_no,report_name,combine_center){
	
	// validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// print window
	if(combine_center=='' || auction_center==combine_center){
		
		window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>","BTB Buyer Purchase Upto Statement - ","toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=100,width=1200,height=600");
		
	}else{
		
		window.open("<?php echo site_url('MY_Controller/btb_bpr_all_center_upto?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"&combine_center="+combine_center+"'); ?>","BTB Buyer Purchase Upto Statement - Combine","toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=100,width=1200,height=600");
	}	
}
</script>