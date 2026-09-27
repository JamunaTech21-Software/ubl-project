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
                                &nbsp; CHANGE PASSWORD &nbsp; 
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
                            <div class="form-group col-sm-12">
                                <label for="email">User Name</label>
                                <input readonly type="text" class="form-control input-sm" id="username" value="<?php echo $ubl_user_name; ?>" />
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="email">Old Password</label>
                                <div class="input-group input-group-sm">
                                    <input required type="password" class="form-control input-sm" id="old_password" placeholder="Enter Old Password" maxlength="12" />
                                    <span class="input-group-addon" onclick="fn_pass_hide_show('old_password', 'old_pass_icon')">
                                        <i id="old_pass_icon" class="glyphicon glyphicon-eye-open"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="email">New Password</label>
                                <div class="input-group input-group-sm">
                                    <input required type="password" class="form-control input-sm" id="new_password" placeholder="New Password (5-12 characters)" maxlength="12" />
                                    <span class="input-group-addon" onclick="fn_pass_hide_show('new_password', 'new_pass_icon')">
                                        <i id="new_pass_icon" class="glyphicon glyphicon-eye-open"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="email">Confirm New Password</label>
                                <div class="input-group input-group-sm">
                                    <input required type="password" class="form-control input-sm" id="confirm_password" placeholder="Confirm New Password" maxlength="12" />
                                    <span class="input-group-addon" onclick="fn_pass_hide_show('confirm_password', 'confirm_pass_icon')">
                                        <i id="confirm_pass_icon" class="glyphicon glyphicon-eye-open"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- /row -->
                        
                        <input type="submit" value="Change Password" id="Submit" class="btn btn-primary" onclick="fn_change_password();" />
                        
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
function fn_pass_hide_show(pass_id, icon_id){
    var x = document.getElementById(pass_id);
    if (x.type === "password") {
        x.type = "text";
        document.getElementById(icon_id).className = "glyphicon glyphicon-eye-close";
    } else {
        x.type = "password";
        document.getElementById(icon_id).className = "glyphicon glyphicon-eye-open";
    }
}
</script>