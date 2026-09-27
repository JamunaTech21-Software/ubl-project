<div class="container text-center">    
	<div class="row">

	  <?php
		  include("login_left_bar.php");
	  ?>

		<div class="col-sm-9">
			
			<div class="row">
				<div class="col-sm-12">
					<b>
						<nav aria-label="breadcrumb">
						  <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/web_user_home"); ?>">Home</a></li>
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/brokers_entry"); ?>">Data Entry</a></li>
							<li class="breadcrumb-item active" aria-current="page">Page Number <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="manage_page_number" action="#">

							<!-- fixed value -->
							<input required type="hidden" class="form-control" name="setup_id" id="setup_id">
							<input required type="hidden" class="form-control" name="user" id="user" value="<?php print $ubl_user_name; ?>">
							<input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<!-- End fixed data -->

							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Manage Page Number & Catalog Publish </label>
							</div>
							
							<hr />

							<div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="season">Season</label>
                                    <select required class="form-control input-sm" id="season" name="season" onchange="get_invoice_entry_sale_no();">
                                        <?php 
                                        foreach($season_data as $season_list){
											$selected = ($season_list['season_name'] == $current_season_data->season_name) ? 'selected' : '';
                                        	print "<option value=\"$season_list[season_name]\" $selected>$season_list[season_name]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="auction_center">Auction Center</label>
                                    <select required class="form-control input-sm" name="auction_center" id="auction_center" onchange="get_invoice_entry_sale_no();">
                                        <option value="">Select</option>
                                        <option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
                                        <option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="sale_no">Sale No</label>
                                    <div id="load_sale_no">
                                        <select required class="form-control input-sm" id="sale_no" name="sale_no">
                                            <option value="">Select</option>
                                        </select>	
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="catalogue">Cataloge</label>
                                    <select required class="form-control input-sm" id="catalogue" name="catalogue">
										<option value="">Select</option>
                                        <?php 
                                        foreach($catalogue_data as $catalogue_list){
                                        print "<option value=\"$catalogue_list[catalouge_name]\">$catalogue_list[catalouge_name]</option>";
                                        }
                                        ?>
                                    </select>	
                                </div>
								<div class="form-group col-sm-4">
                                    <label for="catalogue_part">Cataloge Part</label>
                                    <select required class="form-control input-sm" id="catalogue_part" name="catalogue_part">
										<option value="1st">1st</option>
										<option value="2nd">2nd</option>
                                    </select>	
                                </div>
								
								<div class="form-group col-sm-4">
									<label for="lot_no_start">Lot No</label>
									
									<div class="input-group input-group-sm">
										<input required type="number" class="form-control input-sm" id="lot_no_start" name="lot_no_start" autocomplete="off"/>
										<span class="input-group-addon">-</span>
										<input required type="number" class="form-control input-sm" id="lot_no_end" name="lot_no_end" autocomplete="off"/>
									</div>
								</div>

                                <div class="form-group col-sm-4">
									<label for="page_no">Page Number</label>
									<input required type="number" class="form-control input-sm" id="page_no" name="page_no" autocomplete="off" value="0" onfocus="if(this.value==0) this.value=''" onblur="if(this.value=='') this.value='0'"/>
								</div>

								<div class="form-group col-sm-4">
									<label for="comment">Catalogue Publish</label>
									<select required class="form-control input-sm" id="published" name="published">
										<option value="Yes">Yes</option>
									</select>	
								</div>
							</div>	
							
							<input type="submit" value="Submit" id="Submit" class="btn btn-primary">
						</form>
					</div>	
				</div> <!-- /col -->
			</div> <!-- /row -->

		</div> <!-- /col -->
	</div> <!-- /row -->	
</div> <!-- /container -->	  

<script>
$(function(){
	get_invoice_entry_sale_no();
});
function external_comeback() {
	alert('Let your data view details.');
	window.focus();
	$("#page_no").focus();
}
</script>