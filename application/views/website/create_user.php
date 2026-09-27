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
							<b style="font-size:16px; color:red;">
								<span class="glyphicon glyphicon-hand-right"></span> 
								&nbsp; CREATE NEW USER &nbsp; 
								<span class="glyphicon glyphicon-hand-left"></span>
							</b>
						</div>
					</div>
				</div>
			</div>
			
			<!-- start row -->
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
		  
						<div class="row">
							<div class="form-group col-sm-6" style="display:none;">
								<label for="email">ID</label>
								<input readonly type="text" class="form-control input-sm" id="edit_user_id" />
							</div>
							<div class="form-group col-sm-6">
								<label for="email">User Name</label>
								<input required type="text" class="form-control input-sm" id="username" placeholder="User Name (8 characters max)" maxlength="8" />
							</div>
							<div class="form-group col-sm-6">
								<label for="email">Password</label>
								<div class="input-group input-group-sm">
									<input required type="password" class="form-control input-sm" id="password" placeholder="User Name (12 characters max)" maxlength="12" />
									<span class="input-group-addon" onclick="fn_pass_hide_show()"><i id="pass_icon" class="glyphicon glyphicon-eye-open"></i></span>
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label for="email">Edit Privilege</label>
								<select required class="form-control input-sm" id="edit_privilege">
								  <option value="Yes">Yes</option>
								  <option selected value="No">No</option>
								</select>
							</div>
							<div class="form-group col-sm-6">
								<label for="email">Delete Privilege</label>
								<select required class="form-control input-sm" id="delete_privilege">
								  <option value="Yes">Yes</option>
								  <option selected value="No">No</option>
								</select>
							</div>
						</div>
						<!-- /row -->
						
						<input type="submit" value="Submit" id="Submit" class="btn btn-primary" onclick="fn_save_user();" />
						
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12" id="load_user_list">
				</div>
			</div>	
			
		</div>
		<!-- /col -->
		
		<?php
			include("login_right_bar.php");
		?>
		
	</div>
</div> 
<!-- /container -->

<script>
$(function(){
	fn_user_list(); 
});
</script>