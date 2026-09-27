<script>
$(function () {
	
	// Company Setup 
    $('#company_details').submit(function(e){
        e.preventDefault();

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_company_setup'); ?>",
            dataType : "JSON",
            data: $('#company_details').serialize(),
            success: function(gcs_data) {
				
                if(gcs_data == 1){
                    
                    alert("Form Submitted: Success");
                    $("input").val('');
                    $("#user").val("<?php print $ubl_user_name; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    $("#password").val("<?php print $password; ?>");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                    total_data_return('company_setup');
                    $('html, body').animate({ scrollTop: $('#total_data_counter').offset().top }, 'slow');
                      
                }else if(gcs_data == 2){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false; 
                    // open page after update
                    window.open('<?php echo site_url('MY_Controller/get_company_data'); ?>', '');
                    return false;   
                  
                }else if(gcs_data == 3){

                    alert("Form Submitted: Save failed due to duplicate data in Company Email.");
                    $("#company_email").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;

                }else{
                    alert(gcs_data);
                }
            }
        });
        return false;
    });
	
	// Garden Setup 
    $('#garden_company_details').submit(function(e){
        e.preventDefault();

        var garden_type = $("#garden_type").val().trim();
        var main_garden_id = $("#main_garden_id").val().trim();
        var gardentext = $("#main_garden_id option:selected").text();

        // Validation
        if (garden_type == 'A/C Garden' && main_garden_id == 0) {
            alert("Select Main Garden...");
            $("#main_garden_id").focus();
            return false;
        }

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_gc_setup'); ?>",
            dataType : "JSON",
            data: $('#garden_company_details').serialize() + "&gardentext=" + encodeURIComponent(gardentext),
            success: function(gcs_data) {

                // if successfully inserted
                if(gcs_data == 1){
                    
                    alert("Form Submitted: Success");
                    // clear form data
                    $("input").val('');
                    $("#report_cc").val('');
                    $("#report_letter_cc").val('');
                    $("#garden_type").val('');
                    $("#seller_brokers").val('');
                    $("#garden_id").val('0');
                    $("#user").val("<?php print $ubl_user_name; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    $("#password").val("<?php print $password; ?>");
                    // reset submit value
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                    // call function
                    total_data_return('gc_setup');
                    // scroll top
                    $('html, body').animate({ scrollTop: $('#total_data_counter').offset().top }, 'slow');
                    
                // if successfully updated    
                }else if(gcs_data == 2){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    
                    // open page after update
                    window.open('<?php echo site_url('MY_Controller/get_garden_and_company_data'); ?>', '');
                    return false;
                
                // if duplicate data    
                }else if(gcs_data == 3){

                    alert("Form Submitted: Save failed due to duplicate data in Garden Email.");
                    $("#garden_email").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;

                }else if(gcs_data == 4){

                    alert("Form Submitted: Save failed due to duplicate data in Company Email.");
                    $("#company_email").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;

                }else{
                    alert(gcs_data);
                }
            }
        });
        return false;
    });
    
	// Grade Setup
    $('#tea_grade_details').submit(function(e){
        e.preventDefault();

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
		$("#Submit").prop("disabled",true);
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_grade_setup'); ?>",
            dataType : "JSON",
            data: $('#tea_grade_details').serialize(),
            success: function(data) {

                if(data == 1){
                    
                    alert("Form Submitted: Success");
                    $("input").val('');
                    $("#user").val("<?php print $ubl_user_name; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    $('#Submit').val('Submit');
                    $("#Submit").prop("disabled",false);
                    total_data_return('grade_setup');
                    $('html, body').animate({ scrollTop: $('#total_data_counter').offset().top }, 'slow');
                    $('#total_data_counter').fadeOut(500);
                    $('#total_data_counter').fadeIn(500);
   
                }else if(data == 2){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    $("#Submit").prop("disabled",false);  
                     // open page after update
                    window.open('<?php echo site_url('MY_Controller/get_tea_grade_data'); ?>', '');
                    return false; 
                 
                }else if(data == 3){

                    alert("Form Submitted: Save failed due to duplicate data in Grade Name.");
                    $("#grade_name").focus();
                    $('#Submit').val('Submit');
                    $("#Submit").prop("disabled",false);

                }else{
                    alert(data);
					$('#Submit').val('Submit');
                    $("#Submit").prop("disabled",false);
                }
                
            }
        });
        return false;
    });

    // Contact Person Setup
    $('#contact_person_setup').submit(function(e) {
        e.preventDefault();

        // Get values
        var contact_type = $("#contact_type").val();
        var company_id = $("#company_id").val();
        var company_name = company_id === '0' ? '' : $("#company_id option:selected").text();
        var garden_id = $("#garden_id").val();
        var garden_name = garden_id === '0' ? '' : $("#garden_id option:selected").text();
        var buyer_id = $("#buyer_id").val();
        var buyer_name = buyer_id === '0' ? '' : $("#buyer_id option:selected").text(); 
		var warehouse_id = $("#warehouse_id").val();
        var warehouse_name = warehouse_id === '0' ? '' : $("#warehouse_id option:selected").text();
        var contact_no = $("#contact_no").val();

        // Validation
        if (contact_type == '1' && company_id == '0') {
            alert("Select company name...");
            $("#company_id").select2('open');
            return false;
        }

        if (contact_type == '2' && garden_id == '0') {
            alert("Select garden name...");
            $("#garden_id").select2('open');
            return false;
        }

        if (contact_type == '3' && buyer_id == '0') {
            alert("Select buyer name...");
            $("#buyer_id").select2('open');
            return false;
        }
		
		if (contact_type == '4' && warehouse_id == '0') {
            alert("Select Warehouse name...");
            $("#warehouse_id").select2('open');
            return false;
        }

        if (contact_no == '') {
            alert("Enter contact no...");
            $('#contact_no').focus();
            return false;
        } else if (!/^(\+?88|0088)?01[3-9]\d{8}$/.test(contact_no)) {
            alert("Enter a valid Bangladeshi phone number.");
            $('#contact_no').focus();
            return false;
        }

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        $("#Submit").prop("disabled", true);

        // Use Ajax to submit
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_contact_list_setup'); ?>",
            dataType: "JSON",
            data: $('#contact_person_setup').serialize() + 
                "&company_name=" + encodeURIComponent(company_name) +
                "&garden_name=" + encodeURIComponent(garden_name) +
                "&buyer_name=" + encodeURIComponent(buyer_name) +
                "&warehouse_name=" + encodeURIComponent(warehouse_name),
            success: function(data) {
                if (data == 1) {
                    alert("Form Submitted: Success");
                    $("#contact_type").val('');
                    $("#company_id").val('0').select2();
                    $("#garden_id").val('0').select2();
                    $("#buyer_id").val('0').select2();
                    $("#warehouse_id").val('0').select2();
                    $("#contact_details").val('');
                    $("#contact_designation").val('');
                    $("#contact_no").val('');
                    $('#Submit').val('Submit');
                    $("#Submit").prop("disabled", false);
                    total_data_return('contact_person_setup');
                    $('html, body').animate({ scrollTop: $('#total_data_counter').offset().top }, 'slow');
                    $('#total_data_counter').fadeOut(500).fadeIn(500);
                } else if (data == 2) {
                    alert("Form Updated: Success");
                    window.location.reload(true);
                } else {
                    alert(data);
                }
                $('#Submit').val('Submit');
                $("#Submit").prop("disabled", false);
            }
        });
        return false;
    });
	
	// Warehouse Setup
    $('#warehouse_setup_details').submit(function(e){
        e.preventDefault();

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_warehouse_setup'); ?>",
            dataType : "JSON",
            data: $('#warehouse_setup_details').serialize(),
            success: function(data) {

                // if successfully inserted
                if(data == 1){
                    
                    alert("Form Submitted: Success");
                    // clear form data
                    $("input").val('');
                    $("#user").val("<?php print $ubl_user_name; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    $("#password").val("<?php print $password; ?>");
                    // reset submit value
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                    // call function
                    total_data_return('warehouse_setup');
                    // scroll top
                    $('html, body').animate({ scrollTop: $('#total_data_counter').offset().top }, 'slow');

                // if successfully updated    
                }else if(data == 2){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    
                    // open page after update
                    window.open('<?php echo site_url('MY_Controller/get_warehouse_data'); ?>', '');
                    return false;
                
                // if duplicate data    
                }else if(data == 3){

                    alert("Form Submitted: Save failed due to duplicate data in BIN No.");
                    $("#warehouse_bin_no").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;

                }else if(data == 4){

                    alert("Form Submitted: Save failed due to duplicate data in Email Address.");
                    $("#warehouse_email_address").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;

                }else{
                    alert(data);
                }
                
            }
        });
        return false;
    });
	
	// Brokers Setup
    $('#brokers_setup_details').submit(function(e){
        e.preventDefault(); // for prevent form submit

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_brokers_setup'); ?>",
            dataType : "JSON",
            data: $('#brokers_setup_details').serialize(),
            success: function(data) {

                // if successfully inserted
                if(data == 1){
                    
                    alert("Form Submitted: Success");
                    // clear form data
                    $("input").val('');
                    $("select").val('');
                    $("#user").val("<?php print $ubl_user_name; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    $("#password").val("<?php print $password; ?>");
                    // reset submit value
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                    // call function
                    total_data_return('brokers_setup');
                    // scroll top
                    $('html, body').animate({ scrollTop: $('#total_data_counter').offset().top }, 'slow');
                    // blink text
                    $('#total_data_counter').fadeOut(500);
                    $('#total_data_counter').fadeIn(500);

                // if successfully updated    
                }else if(data == 2){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    
                
                // if duplicate data    
                }else if(data == 3){

                    alert("Form Submitted: Save failed due to duplicate data in Brokers Email.");
                    $("#brokers_email").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;

                }else if(data == 4){

                    alert("Form Submitted: Save failed due to duplicate data in Brokers TIN No.");
                    $("#brokers_tin_no").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;

                }else if(data == 5){

                    alert("Form Submitted: Save failed due to duplicate data in Brokers BIN No.");
                    $("#brokers_bin_no").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                }
                
            }
        });
        return false;
    });
	
	// Buyer Setup
    $('#buyer_setup_details').submit(function(e){
        e.preventDefault();

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_buyer_setup'); ?>",
            dataType : "JSON",
            data: $('#buyer_setup_details').serialize(),
            success: function(data) {

                // if successfully inserted
                if(data == 1){
                    
                    alert("Form Submitted: Success");
                    // clear form data
                    $("input").val('');
                    $("#user").val("<?php print $ubl_user_name; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    $("#password").val("<?php print $password; ?>");
                    // reset submit value
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                    // call function
                    total_data_return('buyer_setup');
                    // scroll top
                    $('html, body').animate({ scrollTop: $('#total_data_counter').offset().top }, 'slow');

                // if successfully updated    
                }else if(data == 2){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    
                     // open page after update
                    window.open('<?php echo site_url('MY_Controller/get_buyer_data'); ?>', '');
                    return false;
                
                // if duplicate data    
                }else if(data == 3){

                    alert("Form Submitted: Save failed due to duplicate data in Buyer Email.");
                    $("#buyer_email").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;

                }else if(data == 4){

                    alert("Form Submitted: Save failed due to duplicate data in Buyer TIN No.");
                    $("#buyer_tin_no").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;

                }else if(data == 5){

                    alert("Form Submitted: Save failed due to duplicate data in Buyer BIN No.");
                    $("#buyer_bin_no").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;

                }else{
                    alert(data);
                }
                
            }
        });
        return false;
    });
	
	// Season Setup
    $('#season_setup_details').submit(function(e){
        e.preventDefault();

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_season_setup'); ?>",
            dataType : "JSON",
            data: $('#season_setup_details').serialize(),
            success: function(data) {

                // if successfully inserted
                if(data == 1){
                    
                    alert("Form Submitted: Success");
                    // clear form data
                    $("input").val('');
                    $("#user").val("<?php print $ubl_user_name; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    // reset submit value
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                    // call function
                    total_data_return('season_setup');
                    // scroll top
                    $('html, body').animate({ scrollTop: $('#total_data_counter').offset().top }, 'slow');
                    // blink text
                    $('#total_data_counter').fadeOut(500);
                    $('#total_data_counter').fadeIn(500);

                // if successfully updated    
                }else if(data == 2){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    
                     // open page after update
                    window.open('<?php echo site_url('MY_Controller/get_season_data'); ?>', '');
                     return false;
                
                // if duplicate data    
                }else if(data == 3){

                    alert("Form Submitted: Save failed due to duplicate data in Season Name.");
                    $("#season_name").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                    
                }else{
                    alert(data);
                }
                
            }
        });
        return false;
    });

    // Current Season Setup
    $('#current_season_setup_details').submit(function(e){
        e.preventDefault();

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_current_season_setup'); ?>",
            dataType : "JSON",
            data: $('#current_season_setup_details').serialize(),
            success: function(data) {

                // if successfully inserted
                if(data == 1){
                    
                    alert("Form Submitted: Success");
                    // clear form data
                    $("select").val('');
                    $("#user").val("<?php print $ubl_user_name; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    // reset submit value
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                    // scroll top
                    $('html, body').animate({ scrollTop: $('#total_data_counter').offset().top }, 'slow');

                    // if successfully updated    
                }else if(data == 2){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    
                 
                }else{
                    alert(data);
                }
                
            }
        });
        return false;
    });

	// Sale schedule Setup
    $('#sale_schedule_setup_details').submit(function(e){
        e.preventDefault(); // for prevent form submit

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_sale_schedule_setup'); ?>",
            dataType : "JSON",
            data: $('#sale_schedule_setup_details').serialize(),
            success: function(data) {

                // if successfully inserted
                if(data == 1){
                    
                    alert("Form Submitted: Success");
                    // clear form data
                    $("input").val('');
                    $("#user").val("<?php print $ubl_user_name; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    // reset submit value
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                    // call function
                    total_data_return('sale_schedule_setup');
                    // scroll top
                    $('html, body').animate({ scrollTop: $('#total_data_counter').offset().top }, 'slow');
                    // blink text
                    $('#total_data_counter').fadeOut(500);
                    $('#total_data_counter').fadeIn(500);

                // if successfully updated    
                }else if(data == 2){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    
                    // open page after update
                    window.open('<?php echo site_url('MY_Controller/get_salse_schedule_data'); ?>', '');
                    return false;
                
                // if duplicate data    
                }else if(data == 3){

                    alert("Form Submitted: Save failed due to duplicate data in Season, Sale No. and Sale date.");
                    $("#sale_no").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                }
                
            }
        });
        return false;
    });
    
    // Manage Page number
    $('#manage_page_number').submit(function(e){
        e.preventDefault(); // for prevent form submit

        var published = $('#published').val();

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_manage_page_number'); ?>",
            dataType : "JSON",
            data: $('#manage_page_number').serialize(),
            success: function(data) {

                // if successfully updated
                if(data == 1){
                    
                    if(published=='Yes'){
						alert("Catalogue Published: Success");
					}else{
						alert("Catalogue is now Unpublished.");
					}	

                    // clear form data
                    $("input").val('');
                    $("#user").val("<?php print $ubl_user_name; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    // reset submit value
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
 
                }else if(data == 2){
                   
                    alert("Sorry !! There is no available catalogue page to publish.");

                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    

                }else {

                    alert(data);
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                }
                
            }
        });
        return false;
    });

    $('#all_garden_avg').submit(function(e){
    e.preventDefault(); // for prevent form submit

    var brokers_name = $("#brokers_id option:selected").text();
    var garden_name = $("#garden_id option:selected").text();

    // BEFORE RESPONSE
    $('#Submit').val('Please Wait...');
    document.getElementById('Submit').disabled=true;
    
    //use ajax to run the check
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('MY_Controller/save_all_garden_avg'); ?>",
        dataType : "JSON",
        data: $('#all_garden_avg').serialize() + "&brokers_name=" + encodeURIComponent(brokers_name) + "&garden_name=" + encodeURIComponent(garden_name),
        success: function(data) {
            // if successfully inserted
            if(data == 1){
                alert("Form Submitted: Success");
                // clear form data
                $("input").val('');
                $('#garden_id').val('').trigger('change').focus();
                $("#user").val("<?php print $ubl_user_name; ?>");
                $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                
                // Refresh the table
                refreshTable();
                
            }else if(data == 2){ // if successfully updated
                alert("Form Updated: Success");
                
                // Refresh the table
                refreshTable();
                
            }else if(data == 3){
                alert("This data already exists in the system. Please check and try again with unique information.");
            }else {
                alert(data);
            }
            
            // Reset submit button
            $('#Submit').val('Submit');
            document.getElementById('Submit').disabled=false;
            
            // Scroll to table
            $('html, body').animate({ scrollTop: $('#garden_avg_list').offset().top }, 'slow');
        }
    });
    return false;
});
	
//all broker buyer purchase
$('#all_brokers_buyer_purchase').submit(function(e){
    e.preventDefault(); // for prevent form submit

    var brokers_name = $("#brokers_id option:selected").text();
    var buyer_name = $("#buyer_id option:selected").text();

    // BEFORE RESPONSE
    $('#Submit').val('Please Wait...');
    document.getElementById('Submit').disabled=true;
    
    //use ajax to run the check
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('MY_Controller/save_all_broker_buyer_purchase'); ?>",
        dataType : "JSON",
        data: $('#all_brokers_buyer_purchase').serialize() + "&brokers_name=" + encodeURIComponent(brokers_name) + "&buyer_name=" + encodeURIComponent(buyer_name),
        success: function(data) {
            // if successfully inserted
            if(data == 1){
                alert("Form Submitted: Success");
                // clear form data
                $("input").val('');
                $('#buyer_id').val('').trigger('change').focus();
                $("#user").val("<?php print $ubl_user_name; ?>");
                $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                
                // Refresh the table
                loadbbpTable();
                //refreshTable();
                
            }else if(data == 2){ // if successfully updated
                alert("Form Updated: Success");
                 $("input").val('');
                // Refresh the table
                loadbbpTable();
                //refreshTable();
                
            }else if(data == 3){
                alert("This data already exists in the system. Please check and try again with unique information.");
            }else {
                alert(data);
            }
            
            // Reset submit button
            $('#Submit').prop('disabled', false).val('Submit');
            $('#View').prop('disabled', false).val('View');

            
            // Scroll to table
           // $('html, body').animate({ scrollTop: $('#all_bbp_avg_list').offset().top }, 'slow');
        }
    });
    return false;
});
	
	// catalogue setup
    $('#catalogue_setup_details').submit(function(e){
        e.preventDefault(); // for prevent form submit

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_catalogue_setup'); ?>",
            dataType : "JSON",
            data: $('#catalogue_setup_details').serialize(),
            success: function(data) {

                // if successfully inserted
                if(data == 1){
                    
                    alert("Form Submitted: Success");
                    // clear form data
                    $("input").val('');
                    $("select").val('');
                    $("#auction").val('Live Auc');
                    $("#user").val("<?php print $ubl_user_name; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    // reset submit value
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                    // call function
                    total_data_return('catalogue_setup');
                    // scroll top
                    $('html, body').animate({ scrollTop: $('#total_data_counter').offset().top }, 'slow');
                    // blink text
                    $('#total_data_counter').fadeOut(500);
                    $('#total_data_counter').fadeIn(500);

                // if successfully updated    
                }else if(data == 2){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    
                
                // if duplicate data    
                }else if(data == 3){

                    alert("Form Submitted: Save failed due to duplicate data in Catalogue Name.");
                    $("#catalouge_name").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                }
                
            }
        });
        return false;
    });
	
    // Catalogue Header information
    $('#cover_page_information').submit(function(e){
        e.preventDefault(); // for prevent form submit

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_cover_page_info'); ?>",
            dataType : "JSON",
            data: $('#cover_page_information').serialize(),
            success: function(data) {

                // if successfully updated
                if(data == 1){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    
                   
                }else{

                    alert(data);
                }
                
            }
        });
        return false;
    });

    // garden letter entry
    $('#garden_letter_information').submit(function(e){
    e.preventDefault(); // for prevent form submit


    // BEFORE RESPONSE
    $('#Submit').val('Please Wait...');
    document.getElementById('Submit').disabled=true;
    
    //use ajax to run the check
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('MY_Controller/save_garden_letter'); ?>",
        dataType : "JSON",
        data: $('#garden_letter_information').serialize(),
        success: function(data) {
            // if successfully inserted
            if(data == 1){
                alert("Form Submitted: Success");
                // clear form data
                $("input").val('');
                $("#user").val("<?php print $ubl_user_name; ?>");
                $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                
                window.location.reload();
            }else if(data == 2){ // if successfully updated
                alert("Form Updated: Success");
                
                 window.location.reload();
            }else if(data == 3){
                alert("This data already exists in the system. Please check and try again with unique information.");
            }else {
                alert(data);
            }
            
            // Reset submit button
            $('#Submit').val('Submit');
            document.getElementById('Submit').disabled=false;
            
            // Scroll to table
            $('html, body').animate({ scrollTop: $('#form_group_element').offset().top }, 'slow');
        }
    });
    return false;
});

    // Page Size Setup
    $('#all_page_setup_information').submit(function(e){
        e.preventDefault(); // for prevent form submit

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled = true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_page_setup_info'); ?>",
            dataType : "JSON",
            data: $('#all_page_setup_information').serialize(),
            success: function(data) {

                // if successfully updated
                if(data == 1){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled = false;    
                    
                }else{

                    alert(data);
                }
                
            }
        });
        return false;
    });
	
	// Chest Setup
    $('#chest_setup_details').submit(function(e){
        e.preventDefault(); // for prevent form submit

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_chest_setup'); ?>",
            dataType : "JSON",
            data: $('#chest_setup_details').serialize(),
            success: function(data) {

                // if successfully inserted
                if(data == 1){
                    
                    alert("Form Submitted: Success");
                    // clear form data
                    $("input").val('');
                    $("select").val('');
                    $("#user").val("<?php print $ubl_user_name; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    // reset submit value
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                    // call function
                    total_data_return('chest_setup');
                    // scroll top
                    $('html, body').animate({ scrollTop: $('#total_data_counter').offset().top }, 'slow');
                    // blink text
                    $('#total_data_counter').fadeOut(500);
                    $('#total_data_counter').fadeIn(500);

                // if successfully updated    
                }else if(data == 2){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    
                
                // if duplicate data    
                }else if(data == 3){

                    alert("Form Submitted: Save failed due to duplicate data in Chest Name.");
                    $("#sample_name").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                }
                
            }
        });
        return false;
    });
	
	// Regulatory Setup
    $('#regulatorty_setup_details').submit(function(e){
        e.preventDefault(); // for prevent form submit

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_regulatory_setup'); ?>",
            dataType : "JSON",
            data: $('#regulatorty_setup_details').serialize(),
            success: function(data) {

                // if successfully inserted
                if(data == 1){
                    
                    alert("Form Submitted: Success");
                    // clear form data
                    $("input").val('');
                    $("select").val('');
                    $("#user").val("<?php print $ubl_user_name; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    $("#password").val("<?php print $password; ?>");
                    // reset submit value
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                    // call function
                    total_data_return('regulatory_setup');
                    // scroll top
                    $('html, body').animate({ scrollTop: $('#total_data_counter').offset().top }, 'slow');
                    // blink text
                    $('#total_data_counter').fadeOut(500);
                    $('#total_data_counter').fadeIn(500);

                // if successfully updated    
                }else if(data == 2){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    
                
                // if duplicate data    
                }else if(data == 3){

                    alert("Form Submitted: Save failed due to duplicate data in Regulatory Email.");
                    $("#regulatory_email").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;

                }else{

                    alert(data);
                }
                
            }
        });
        return false;
    });
	
	// Sale condition Setup
    $('#sale_condition_details').submit(function(e){
        e.preventDefault(); // for prevent form submit

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_sale_condtion_setup'); ?>",
            dataType : "JSON",
            data: $('#sale_condition_details').serialize(),
            success: function(data) {

                // if successfully updated
                if(data == 1){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    
                   
                }else{

                    alert(data);
                }
                
            }
        });
        return false;
    });
	
    // INVOICE ENTRY
    $('#brokers_invoice_entry').submit(function(e) {
        e.preventDefault(); // prevent default form submit

        // Validation
        var current_season = $("#season").val();
        var prv_lot_no = $("#lot_no").val();
        var chest_number = $("#chest_number").val();
		var lot_type = $("#lot_type").val();
		var olValue = $("#ol_os_sc").val();
        var grade_catalog_name = $('#grade_id').find("option:selected").attr("title");
        var catalogue_name = $("#catalouge_name").val();
        var supplement_tea = $("#supplement_tea").val();
        if (chest_number < 1) {
            alert("Sorry !! You have to must enter chest value greater than zero");
            $("#chest_number").focus();
            return false;
        }
		if (lot_type === "Reprint") { 
		if (olValue === "") {
			alert("Please select 'Yes' or 'No'");
			$("#ol_os_sc").focus();
			return false;
		}
		}

        if (catalogue_name === "Leaf" && supplement_tea === "Leaf" && grade_catalog_name !== "Leaf") {
            alert("Error !! For Leaf catalogue and supplement, you must select a Leaf grade");
            $("#grade_id").focus();
            return false;
        }

        if (catalogue_name === "Dust" && supplement_tea === "Dust" && grade_catalog_name !== "Dust") {
            alert("Error !! For Dust catalogue and supplement, you must select a Dust grade");
            $("#grade_id").focus();
            return false;
        }

        // Show loading state
        $('#Submit').val('Please Wait...');
        $('#Submit').prop('disabled', true);

        // Temporarily enable disabled select fields before serialize
        $('#w_house_id, #garden_id, #grade_id').prop('disabled', false).trigger('change');

        // Prepare serialized form data
        var formData = $('#brokers_invoice_entry').serialize();

        // Re-disable the select fields if needed (optional)
        $('#w_house_id, #garden_id, #grade_id').prop('disabled', true).trigger('change');

        // Submit via AJAX
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_brokers_invoice_info'); ?>",
            dataType: "JSON",
            data: formData,
            success: function(ie_data) {

                if (ie_data == 1) {
                    alert("Form Submitted: Success");

                    $("#lot_no").val('').focus();
                    $("#old_sale_no, #old_lot_no, #ol_os_sc, #remarks, #short_note, #pre_invoice_id").val('');
                    ($('#bulk_lot').is(":checked")) ? '' : $("#w_inv_no").val('');
                    ($('#bulk_lot').is(":checked")) ? '' : $("#grade_id").val('').trigger('change').prop('disabled', false);
                    ($('#bulk_lot').is(":checked")) ? '' : $("#additional_grade").val('');
                    
                    $('#grade_id').val('').trigger('change').prop('disabled', false);
                    $('#w_house_id').trigger('change').prop('disabled', false);
                    $('#garden_id').trigger('change').prop('disabled', false);
                    $('#w_inv_no, #additional_grade').prop('readonly', false);

                    $("#chest_number").val('0').trigger('keyup');
                    $("#net_wt_per_bag").val('50').trigger('keyup');
                    $("#gross_wt_per_bag").val('50.2').trigger('keyup');
                    $("#additional_sample_kgs").val('0').trigger('keyup');
                    $('#lot_part, #account_concerned, #weight_short').prop("checked", false);

                    $("#old_season").val(current_season);
                    $("#user").val("<?php print $ubl_web_user_id; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");

                    $('#Submit').val('Submit').prop("disabled", false);
                    show_invoice_list('0');
                    (window.opener != null && !window.opener.closed) ? window.opener.location.reload() + window.close() : '';

                    // 🔒 Auto readonly after insert
                    $("#gross_wt_per_bag, #net_wt_per_bag").prop("readonly", true);
                    $("#edit_gross_wt, #edit_net_wt").prop("checked", false);

                } else if (ie_data == 2) {
                    alert("Form Updated: Success");
                    $('#form_mode').html('');
                    $("#setup_id, #org_id, #reprinted_invoice, #invoice, #lot_no, #old_sale_no, #old_lot_no, #ol_os_sc, #pre_invoice_id, #w_inv_no, #additional_grade, #remarks, #short_note").val('');
                    $('#bulk_lot, #e_auc, #lot_part, #account_concerned, #weight_short').prop("checked", false);
                    $("#chest_number").val('0').trigger('keyup');
                    $("#net_wt_per_bag").val('50').trigger('keyup');
                    $("#gross_wt_per_bag").val('50.2').trigger('keyup');
                    $("#additional_sample_kgs").val('0').trigger('keyup');
                    $("#user").val("<?php print $ubl_web_user_id; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    $('#Submit').val('Submit').prop("disabled", false);
                    show_invoice_list('0');
                    $('html, body').animate({ scrollTop: $('#invoice_list').offset().top }, 'slow');
                    (window.opener != null && !window.opener.closed) ? window.opener.location.reload() + window.close() : '';

                    $('#w_house_id, #garden_id, #grade_id').val('').trigger('change').prop('disabled', false);
                    $('#w_inv_no, #additional_grade').prop('readonly', false);
                    $("#old_season").val(current_season);
                    get_modal_sale_no();

                    // 🔒 Auto readonly after update
                    $("#gross_wt_per_bag, #net_wt_per_bag").prop("readonly", true);
                    $("#edit_gross_wt, #edit_net_wt").prop("checked", false);

                } else if (ie_data == 3) {
                    alert("Error !! Lot No already exists.");
                    $("#lot_no").focus();
                    $('#Submit').val('Submit').prop("disabled", false);

                } else if (ie_data == 4) {
                    alert("Error !! Invoice No already exists.");
                    $("#w_inv_no").focus();
                    $('#Submit').val('Submit').prop("disabled", false);

                } else {
                    alert(ie_data);
                    $('#Submit').val('Submit').prop("disabled", false);
                }
            }
        });
		// reset all time
        $("#sample_kgs").val('0.00');
        return false;
    });


    // Pre INVOICE ENTRY
    $('#pre_invoice_entry').submit(function(e){
        e.preventDefault(); // for prevent form submit
		
        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        $("#Submit").prop("disabled",true);
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_pre_invoice_entry_info'); ?>",
            dataType : "JSON",
            data: $('#pre_invoice_entry').serialize(),
            success: function(ie_data) {

                // if successfully inserted
                if(ie_data == 1){
                   
                    alert("Form Submitted: Success");
                    $("#edit_system_id").val('');
                    $("#w_inv_no").val('').focus();
                    $("#grade_id").val('');
                    $("#additional_grade").val('');
                    $("#no_of_bag").val('');
                    $("#user").val("<?php print $ubl_web_user_id; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    $('#Submit').val('Submit');
                    $("#Submit").prop("disabled",false);
					show_pre_invoice_list();

                // if successfully Updated    
                }else if(ie_data == 2){

					alert("Form Updated: Success");
					$('#form_mode').html('');
					$("#setup_id").val('');
					$("#edit_system_id").val('');
                    $("#challan_received_date").val('<?php print date('Y-m-d');?>');
                    $("#w_house_id").val('');
                    $("#chest_name").val('');
                    $("#garden_id").val('');
                    $("#w_inv_no").val('').focus();
                    $("#grade_id").val('');
                    $("#additional_grade").val('');
                    $("#gross_kg").val('');
                    $("#tare_kg").val('');
                    $("#net_kg").val('');
                    $("#no_of_bag").val('');
                    $("#invoice_group_txt").val('');
                    $("#invoice_group").val('');
                    $("#invoice_group_upto").val('');
                    $("#user").val("<?php print $ubl_web_user_id; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    $('#Submit').val('Submit');
					$("#Submit").prop("disabled",false);
					show_pre_invoice_list();
					$('html, body').animate({ scrollTop: $('#pre_invoice_list').offset().top }, 'slow');
					(window.opener != null && !window.opener.closed) ? window.opener.location.reload() + window.close() : '';

                }else{
                    alert(ie_data);
                }
                
            }
        });
        return false;
    });

    // Market average entry
    $('#market_average_entry').submit(function(e){
        e.preventDefault(); // for prevent form submit
		
        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        $("#Submit").prop("disabled",true);
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/market_average_info'); ?>",
            dataType : "JSON",
            data: $('#market_average_entry').serialize(),
            success: function(data) {

                // if successfully inserted
                if(data == 1){
                   
                    alert("Form Submitted: Success");
					$("#broker_id").focus();
                    $("#package1").val('0');
                    $("#kgs1").val('0');
                    $("#value_tk1").val('0');
                    $("#avg_tk1").val('0');
                    $("#package2").val('0');
                    $("#kgs2").val('0');
                    $("#value_tk2").val('0');
                    $("#avg_tk2").val('0');
                    $("#package3").val('0');
                    $("#kgs3").val('0');
                    $("#value_tk3").val('0');
                    $("#avg_tk3").val('0');
                    $("#package4").val('0');
                    $("#kgs4").val('');
                    $("#value_tk4").val('0');
                    $("#avg_tk4").val('0');
                    $("#package5").val('0');
                    $("#kgs5").val('0');
                    $("#value_tk5").val('0');
                    $("#avg_tk5").val('0');
                    $("#user").val("<?php print $ubl_web_user_id; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    $('#Submit').val('Submit');
                    $("#Submit").prop("disabled",false);
					show_market_average_list();
                    (window.opener != null && !window.opener.closed) ? window.opener.location.reload() + window.close() : '';

                // if successfully Updated    
                }else if(data == 2){

					alert("Form Updated: Success");
					$('#form_mode').html('');
					$("#setup_id").val('');
					$("#sale_no").val('');
					$("#broker_id").val('');
                    $("#package1").val('0');
                    $("#kgs1").val('0');
                    $("#value_tk1").val('0');
                    $("#avg_tk1").val('0');
                    $("#package2").val('0');
                    $("#kgs2").val('0');
                    $("#value_tk2").val('0');
                    $("#avg_tk2").val('0');
                    $("#package3").val('0');
                    $("#kgs3").val('0');
                    $("#value_tk3").val('0');
                    $("#avg_tk3").val('0');
                    $("#package4").val('0');
                    $("#kgs4").val('0');
                    $("#value_tk4").val('0');
                    $("#avg_tk4").val('0');
                    $("#package5").val('0');
                    $("#kgs5").val('0');
                    $("#value_tk5").val('0');
                    $("#avg_tk5").val('0');
                    $("#user").val("<?php print $ubl_web_user_id; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    $('#Submit').val('Submit');
					$("#Submit").prop("disabled",false);
					show_market_average_list();
					$('html, body').animate({ scrollTop: $('#form_group_element').offset().top }, 'slow');
					(window.opener != null && !window.opener.closed) ? window.opener.location.reload() + window.close() : '';

                // if duplicate data in season_type
                }else if(data == 3){

                   alert("Error !! This season market average already exists.");
                   $("#season_type").focus();
                   $('#Submit').val('Submit');
                   $("#Submit").prop("disabled",false);
				
                }else{
                    alert(data);
                }
                
            }
        });
        return false;
    });
	
    // Buyer purchase entry
    $('#buyers_purchases_entry').submit(function(e){
        e.preventDefault(); // for prevent form submit
		
        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        $("#Submit").prop("disabled",true);
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/buyers_purchases_info'); ?>",
            dataType : "JSON",
            data: $('#buyers_purchases_entry').serialize(),
            success: function(data) {

                // if successfully inserted
                if(data == 1){
                   
                    alert("Form Submitted: Success");
					$("#package1").val('0').focus();
                    $("#kgs1").val('0');
                    $("#percentage1").val('0');
                    $("#avg_tk1").val('0');
                    $("#package2").val('0');
                    $("#kgs2").val('0');
                    $("#percentage2").val('0');
                    $("#avg_tk2").val('0');
                    $("#user").val("<?php print $ubl_web_user_id; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    $('#Submit').val('Submit');
                    $("#Submit").prop("disabled",false);
					show_buyers_purchases_list();
                    (window.opener != null && !window.opener.closed) ? window.opener.location.reload() + window.close() : '';

                // if successfully Updated    
                }else if(data == 2){

					alert("Form Updated: Success");
					$('#form_mode').html('');
					$("#setup_id").val('');
					$("#package1").val('0');
                    $("#kgs1").val('0');
                    $("#percentage1").val('0');
                    $("#avg_tk1").val('0');
                    $("#package2").val('0');
                    $("#kgs2").val('0');
                    $("#percentage2").val('0');
                    $("#avg_tk2").val('0');
                    $("#user").val("<?php print $ubl_web_user_id; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    $('#Submit').val('Submit');
					$("#Submit").prop("disabled",false);
                    show_buyers_purchases_list();
					$('html, body').animate({ scrollTop: $('#form_group_element').offset().top }, 'slow');
					(window.opener != null && !window.opener.closed) ? window.opener.location.reload() + window.close() : '';

                // if duplicate data in season_type
                }else if(data == 3){

                   alert("Error !! This season buyer purchase already exists.");
                   $("#season_type").focus();
                   $('#Submit').val('Submit');
                   $("#Submit").prop("disabled",false);
				
                }else{
                    alert(data);
                }
                
            }
        });
        return false;
    });
	

	// Sample Darw Setup
    $('#sample_draw_process_details').submit(function(e){
        e.preventDefault(); // for prevent form submit

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_sample_draw_process_setup'); ?>",
            dataType : "JSON",
            data: $('#sample_draw_process_details').serialize(),
            success: function(data) {

                // if successfully inserted
                if(data == 1){
                    
                    alert("Form Submitted: Success");
                    // clear form data
                    $("input").val('');
                    $("select").val('');
                    $("#user").val("<?php print $ubl_user_name; ?>");
                    $("#last_update").val("<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>");
                    // reset submit value
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                    // call function
                    total_data_return('sample_draw_process');
                    // scroll top
                    $('html, body').animate({ scrollTop: $('#total_data_counter').offset().top }, 'slow');
                    // blink text
                    $('#total_data_counter').fadeOut(500);
                    $('#total_data_counter').fadeIn(500);

                // if successfully updated    
                }else if(data == 2){
                   
                    alert("Form Updated: Success");
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    
                
                // if duplicate data    
                }else if(data == 3){

                    alert("Form Submitted: Save failed due to duplicate data in Sample Name.");
                    $("#sample_name").focus();
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;
                }
                
            }
        });
        return false;
    });
	
	// profile picture upload form
	$('#profile_pic_upload_form').on('submit', function(e){  
           e.preventDefault();  
           if($('#wu_image_file').val() == ''){
                alert("Please Select the File");  
           }else{
                // before response
				$('#uploaded_image').html('Loading...');
				// send request
				$.ajax({  
                     url: "<?php echo site_url('MY_Controller/wu_pro_pic_upload'); ?>",
                     method:"POST",  
                     data:new FormData(this),  
                     contentType: false,  
                     cache: false,  
                     processData:false,  
                     success:function(data){ 
						if(data=='success'){
							alert("Update Profile Picture :: success");
							window.location.reload();
						}else{
							$('#uploaded_image').html(data);	
						}  
                     }  
                });  
           }  
      });
	// END profile picture upload form  
	
	// Sending data: - catalogue publish
    $('#catalogue_published').submit(function(e){
        e.preventDefault(); // for prevent form submit

        // GET HTML DATA
		var published = $('#published').val();
		// BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_catalogue_published_info'); ?>",
            dataType : "JSON",
            data: $('#catalogue_published').serialize(),
            success: function(data) {

                // if successfully updated
                if(data == 1){
                   
                    if(published=='Yes'){
						alert("Catalogue Published: Success");
					}else{
						alert("Catalogue is now Unpublished.");
					}	
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    
                   
                }else if(data == 2){
					
					alert("Sorry !! There is no available catalogue page to publish. Please make change in catalogue lock option and then try again.");
					
					$('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;  
					
				}else{
                    alert(data);
                }
                
            }
        });
        return false;
    });
    // END Sending data: - catalogue publish
	
	// Sending data: - valuation publish
    $('#valuation_published').submit(function(e){
        e.preventDefault(); // for prevent form submit

        // GET HTML DATA
		var published = $('#published').val();
		// BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_valuation_published_info'); ?>",
            dataType : "JSON",
            data: $('#valuation_published').serialize(),
            success: function(data) {
                if(data == 1){
                   
                    if(published=='Yes'){
						alert("Valuation Published: Success");
					}else{
						alert("Valuation is now Unpublished.");
					}	
                    $('#Submit').val('Submit');
                    document.getElementById('Submit').disabled=false;    
                   
                }else{
                    alert(data);
                }
            }
        });
        return false;
    });
    // END Sending data: - valuation publish
	
});

// sale order insert
function fn_sale_order_insert(order_serial,brokers_box_id){
	
    // html get value
	var season = $("#season").val();
	var auction_center = $("#auction_center").val();
	var sale_no = $("#sale_no").val();
	var brokers_id = $("#"+brokers_box_id).val();
	var user = $("#user").val();
	var last_update = $("#last_update").val();
	
	// form validation
	if(sale_no==''){
		alert("Please Enter Sale No.");
		$("#sale_no").focus();
		return false;
	}
	if(brokers_id==''){
		alert("Please Select Brokers Name");
		$("#"+brokers_id).focus();
		return false;
	}
	// before save
	document.getElementById('response_message'+order_serial).innerHTML = '<font color=red><b>saving...</b></font>';
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_sale_order'); ?>",
            dataType : "JSON",
            data: { season: season,auction_center: auction_center,sale_no: sale_no,brokers_id: brokers_id,order_serial: order_serial,user: user,last_update: last_update },
            success: function(data) {
				if(data==1){
					$('#response_message'+order_serial).html('<font color=green>you have made save</font>');
				}else if(data==2){
					$('#response_message'+order_serial).html('<font color=green>you have made updated</font>');
				}else if(data==3){
                    alert("Sorry !! Sale No. is not exists. Please Check in Sale Schedule to Know Details.");
                    $('#response_message'+order_serial).html('<font color=red>error...</font>');
				}else if(data==4){
                    alert("Sorry !! Brokers catalogue is not available.");
                    $('#response_message'+order_serial).html('<font color=red>error...</font>');
				}else{
					alert(data);
				}
            }
        });
        return false;
}

// get total data
function total_data_return(form_name){
    $.ajax({
		type: "POST",
		url: "<?php echo site_url('MY_Controller/get_total_data'); ?>",
		dataType : "JSON",
		data: {form_name: form_name},
		success: function(no_of_data) {
			$("#total_data_counter").html(no_of_data);
		}
    });
    return false;
}

// suppliment data hide / show
function catalogue_setup_supplement(ctc_name){
    if(ctc_name == 'Supplement'){
        document.getElementById('suppliment_group').style.visibility = 'visible';
        $("#suppliment_tea").attr('required', ''); //turns required on
    }else{
        document.getElementById('suppliment_group').style.visibility = 'hidden';
        $("#suppliment_tea").removeAttr('required');  //turns required off
    }
}

// suppliment data hide / show
function sg_hide_show(ctc_name){

    // suppliment options hide / show
    if(ctc_name == 'Leaf'){
        $('.Dust').hide();
        $('.Leaf').show();
        $('#supplement_tea').val('Leaf');
    }else if(ctc_name == 'Dust'){
        $('.Leaf').hide();
        $('.Dust').show();
        $('#supplement_tea').val('Dust');
    }else{
        $('.Leaf').show();
        $('.Dust').show();
    }

    // sample kgs data
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_sample_kgs_data'); ?>",
            dataType : "JSON",
            data: {CatalogName: ctc_name},
            success: function(data) {
                document.getElementById('sample_kgs').value = data;
                invoice_entry_cal();
            }
        });
    return false;
}

// function lot wise catalogue loading
function fn_change_lot_wise_catalogue(){

    var lot_no = $("#lot_no").val().trim();

    if (lot_no === '') {
        return false;
    }

    // sample kgs data
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('MY_Controller/get_lot_wise_catalogue'); ?>",
        dataType : "JSON",
        data: {get_lot_no: lot_no},
        success: function(data) {

            if(data !== null){
                document.getElementById('catalouge_name').value = data.catalouge_name;
                document.getElementById('supplement_tea').value = data.suppliment_tea;

                // Callback
                sg_hide_show(data.catalouge_name);

                // Checkbox handle
                if (data.auction === "Main Catalogue E-Auc") {
                    document.getElementById('e_auc').checked = true;
                } else {
                    document.getElementById('e_auc').checked = false;
                }

                fn_e_auction_sk(data.suppliment_tea);

            }else{
                document.getElementById('catalouge_name').value = '';
                document.getElementById('supplement_tea').value = '';
                document.getElementById('e_auc').checked = false;

                sg_hide_show(null);
                fn_e_auction_sk(null);
            }
           
        }
    });
    return false;
}

// function
function fn_e_auction_sk(e_auction_cat){

    // html data
    var getVal = $('#e_auc').is(":checked");

    // if true
    if(getVal==true){

        // sample kgs data
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_sample_kgs_data_eauction'); ?>",
            dataType : "JSON",
            data: { e_auction_cat: e_auction_cat },
            success: function(data) {

                // Delay function call by 100 milliseconds
                setTimeout(function () {
                    document.getElementById('sample_kgs').value = data;
                    invoice_entry_cal();
                }, 100);
            }
        });

    }else{
        var catalouge_name = $('#catalouge_name').val();
        sg_hide_show(catalouge_name);
    }
    // end
}

// pre invoice parking get sample drawn
function fn_e_auction_sk2(id){

    // html data
    var getVal = $('#e_auc'+id).is(":checked");
    var supplement_tea = $('#supplement_tea'+id).val();

    // if true
    if(getVal==true){

        // sample kgs data
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_sample_kgs_data_eauction2'); ?>",
            dataType : "JSON",
            data: { get_pre_invoice_id: id, supplement_tea: supplement_tea },
            success: function(data) {
                var invoice_info = data.split("@");
                document.getElementById('sample_kgs'+invoice_info[0]).value = invoice_info[1];
                // callback
                fn_cal_pre_invoice_parking_net_wt(invoice_info[0]);
                invoice_verify_ctl_save(invoice_info[0]);
            }
        });

    }else{
        invoice_verify_sg_hide_show(id);
    }
    // end
}

// suppliment data hide / show in pre invoice verify report
function invoice_verify_sg_hide_show(id){

    // html data
    var catalouge_name = $("#catalouge_name"+id).val();

    // suppliment options hide / show
    if(catalouge_name == 'Leaf'){
        $('.Dust').hide();
        $('.Leaf').show();
        $('#supplement_tea'+id).val('Leaf');
    }else if(catalouge_name == 'Dust'){
        $('.Leaf').hide();
        $('.Dust').show();
        $('#supplement_tea'+id).val('Dust');
    }else{
        $('.Leaf').show();
        $('.Dust').show();
    }

    // callback
    fn_get_sample_kgs_pre_invoice_parking(id);

    setTimeout(function() {
        
        fn_cal_pre_invoice_parking_net_wt(id);

        setTimeout(function() {

            invoice_verify_ctl_save(id);

        }, 1000); // Wait for 1 second before calling invoice_verify_ctl_save
    }, 500); // Wait for 0.5 seconds before calling fn_cal_pre_invoice_parking_net_wt


}

function fn_get_sample_kgs_pre_invoice_parking(id){

    // html data
    var catalouge_name = $("#catalouge_name"+id).val();

    // sample kgs data
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('MY_Controller/get_sample_kgs_data_pre_invoice'); ?>",
        dataType : "JSON",
        data: {get_pre_invoice_id: id, catalouge_name: catalouge_name},
        success: function(data) {
            var invoice_info = data.split("@");

            document.getElementById('sample_kgs'+invoice_info[0]).value = invoice_info[1];
        }

    });
    return false;
}

function fn_cal_pre_invoice_parking_net_wt(id){

    var total_kgs = $("#total_kgs"+id).val();
    var sample_kgs = $("#sample_kgs"+id).val();

    var net_kgs = total_kgs - sample_kgs;
    isNaN(net_kgs) ? $("#net_kgs"+id).val('0') : $("#net_kgs"+id).val(net_kgs);
}

// suppliment data hide / show in pre invoice verify report
function preinvoice_verify_sg_hide_show(catalouge_name){

    // html data
    var catalouge_name = $("#catalouge_name").val();

    // suppliment options hide / show
    if(catalouge_name == 'Leaf'){
        $('.Dust').hide();
        $('.Leaf').show();
        $('#supplement_tea').val('Leaf');
    }else if(catalouge_name == 'Dust'){
        $('.Leaf').hide();
        $('.Dust').show();
        $('#supplement_tea').val('Dust');
    }else{
        $('.Leaf').show();
        $('.Dust').show();
    }
}

// catalog name save
function invoice_verify_ctl_save(id){
    // html data
    var season = $("#season"+id).val();
    var auction_center = $("#auction_center"+id).val();
    var sale_no = $("#sale_no"+id).val();
    var lot_no = $("#lot_no"+id).val();
    var group_no = $("#group_no"+id).val();
    var catalouge_name = $("#catalouge_name"+id).val();
    var supplement_tea = $("#supplement_tea"+id).val();
    var e_auc_isChecked = $('#e_auc'+id).is(":checked");
    var e_auc = e_auc_isChecked ? "yes" : "no";
    var category = $("#category"+id).val();
    var total_kgs = $("#total_kgs"+id).val();
    var sample_kgs = $("#sample_kgs"+id).val();
    var net_kgs = $("#net_kgs"+id).val();
    var sale_date = $("#sale_date"+id).val();
    var old_season = $("#old_season"+id).val();
    var invoice_group_txt = $("#invoice_group_txt"+id).val();
    var invoice_group = $("#invoice_group"+id).val();
    var invoice_group_upto = $("#invoice_group_upto"+id).val();

    // before response
	$("#response_msg").fadeIn();
	$("#response_msg").html("saving...");

    // send server
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('MY_Controller/get_save_invoice_verify_data'); ?>",
        dataType : "JSON",
        data: {row_id: id, season: season, auction_center: auction_center, sale_no: sale_no, lot_no: lot_no, group_no: group_no, catalouge_name: catalouge_name, supplement_tea: supplement_tea, e_auc: e_auc, category: category, total_kgs: total_kgs, sample_kgs: sample_kgs, net_kgs: net_kgs, sale_date: sale_date, old_season: old_season, invoice_group_txt: invoice_group_txt, invoice_group: invoice_group, invoice_group_upto: invoice_group_upto},
        success: function(data) {
            if(data==1){
                $("#response_msg").html("<font color=green>saved</font>");
                $("#response_msg").fadeOut(3000);
            
            }else{
                $("#response_msg").html("<font color=red>error !!</font>");
            }
        }
    });
    return false;
}

// final catalog name save
function fn_save_verify_ctl(id){
    // html data
    var user = $("#user").val();
    var last_update = $("#last_update").val();
    var season = $("#season"+id).val();
    var auction_center = $("#auction_center"+id).val();
    var sale_no = $("#sale_no"+id).val();
    var lot_no = $("#lot_no"+id).val();
    var group_no = $("#group_no"+id).val();
    var catalouge_name = $("#catalouge_name"+id).val();
    var supplement_tea = $("#supplement_tea"+id).val();
    var e_auc_isChecked = $('#e_auc'+id).is(":checked");
    var e_auc = e_auc_isChecked ? "yes" : "no";
    var category = $("#category"+id).val();
    var total_kgs = $("#total_kgs"+id).val();
    var sample_kgs = $("#sample_kgs"+id).val();
    var net_kgs = $("#net_kgs"+id).val();
    var sale_date = $("#sale_date"+id).val();
    var old_season = $("#old_season"+id).val();
    var garden_id = $("#garden_id"+id).val();
    var invoice_group_txt = $("#invoice_group_txt"+id).val();
    var invoice_group = $("#invoice_group"+id).val();
    var invoice_group_upto = $("#invoice_group_upto"+id).val();
    var w_inv_no = $("#w_inv_no"+id).val();
    var grade_id = $("#grade_id"+id).val();
    var additional_grade = $("#additional_grade"+id).val();
    var w_house_id = $("#w_house_id"+id).val();
    var chest_number = $("#chest_number"+id).val();
    var gross_wt_per_bag = $("#gross_wt_per_bag"+id).val();
    var net_wt_per_bag = $("#net_wt_per_bag"+id).val();
    var chest_name = $("#chest_name"+id).val();
    var old_season2 = $("#old_season2"+id).val();
    var ol_ac = $("#ol_ac"+id).val();
    var old_sale_no = $("#old_sale_no"+id).val();
    var old_lot_no = $("#old_lot_no"+id).val();
    var account_concerned = $("#account_concerned"+id).val();

    // validation
    if(user==''){
        alert('User ID is missing.');
        return false;
    }

    if(last_update==''){
        alert('Last update is missing.');
        return false;
    }

    if(garden_id==''){
        alert('Garden is missing.');
        return false;
    }

    if(grade_id==''){
        alert('Grade is missing.');
        return false;
    }

    if(w_inv_no==''){
        alert('Invoice is missing.');
        return false;
    }

    if(invoice_group_txt==''){
        alert('Inv gorup text is missing.');
        return false;
    }

    if(invoice_group==''){
        alert('Inv gorup is missing.');
        return false;
    }

    if(sale_no==''){
        alert('Sale no is missing.');
        return false;
    }

    if(lot_no==''){
        alert('Lot no is missing.');
        return false;
    }

    if(season==''){
        alert('Season is missing.');
        return false;
    }
    
    if(auction_center==''){
        alert('Auction center is missing.');
        return false;
    }

    if(catalouge_name==''){
        alert('Catalouge is missing.');
        return false;
    }

    if(old_season2==''){
        console.log('Old season 2 is missing.');
        return false;
    }

    if(ol_ac==''){
        console.log('Ol auction center is missing.');
        return false;
    }
    
    if(account_concerned==''){
        console.log('A/C CONCERNED is missing.');
        return false;
    }
   
    // BEFORE RESPONSE
    $('#submit'+id).val('saving...');
    $("#submit"+id).prop("disabled", true);

    // send server
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('MY_Controller/get_save_pre_invoice_verify_data'); ?>",
        dataType : "JSON",
        data: {row_id: id, season: season, auction_center: auction_center, sale_no: sale_no, lot_no: lot_no, group_no: group_no, catalouge_name: catalouge_name, supplement_tea: supplement_tea, e_auc: e_auc, category: category, total_kgs: total_kgs, sample_kgs: sample_kgs, net_kgs: net_kgs, sale_date: sale_date, old_season: old_season, user: user, last_update: last_update, garden_id: garden_id, invoice_group_txt: invoice_group_txt, invoice_group: invoice_group, invoice_group_upto: invoice_group_upto, w_inv_no: w_inv_no, grade_id: grade_id, additional_grade: additional_grade, w_house_id: w_house_id, chest_number: chest_number, gross_wt_per_bag: gross_wt_per_bag, net_wt_per_bag: net_wt_per_bag, chest_name: chest_name, old_season2: old_season2, ol_ac: ol_ac, old_sale_no: old_sale_no, old_lot_no: old_lot_no, account_concerned: account_concerned },
        success: function(data) {
            if(data==1){

                alert('Successfully Done !!');
                $("#system_row_id"+id).hide(500);
            }else if(data==2){

                alert("Error !! Lot No already exists.");
                // reset data
                $('#submit'+id).val('Save');
                $("#submit"+id).prop("disabled", false);
            }else if(data==3){

                alert("Error !! Invoice No already exists.");
                // reset data
                $('#submit'+id).val('Save');
                $("#submit"+id).prop("disabled", false);
            }else{

                alert(data);
                // reset data
                $('#submit'+id).val('Save');
                $("#submit"+id).prop("disabled", false);
            }
        }
    });
    return false;
}

// sample draw process
function get_kgs_sample(sample_name){
    if(sample_name == 'Leaf'){
        $('#sample_kgs').val('1.500');
    }else if(sample_name == 'Dust'){
        $('#sample_kgs').val('0.800');
    }else if(sample_name == 'Supplement'){
        $('#sample_kgs').val('0.500');
    }
}

// page size return
function page_size_return(page_name){
	// before response
	$('#page_size').val('Please Wait...');
	
    //use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_page_size_data'); ?>",
            dataType : "JSON",
            data: {page_name: page_name},
            success: function(data) {
                $('#page_size').val(data);
            }
        });
    return false;
}
// end page size return

// sale date return
function get_sale_date(){
    // variable define
	var auction_center = $("#auction_center").val();
	var season = $("#season").val();
	var sale_no = $("#sale_no").val();
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_sale_date_data'); ?>",
            dataType : "JSON",
            data: {season: season, auction_center: auction_center, sale_no: sale_no},
            success: function(data) {
                document.getElementById('sale_date').value = data;
            }
        });
    return false;
}
// end sale date return

// sale date return Invoice Parking
function get_sale_date_invoice_parking(id){
    // variable define
	var auction_center = $("#auction_center"+id).val();
	var season = $("#season"+id).val();
	var sale_no = $("#sale_no"+id).val();
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_sale_date_data_invoice_parking'); ?>",
            dataType : "JSON",
            data: {get_pre_invoice_id: id, season: season, auction_center: auction_center, sale_no: sale_no},
            success: function(data) {

                var invoice_info = data.split("@");

                document.getElementById('sale_date'+invoice_info[0]).value = invoice_info[1];
				// call function
				get_group_no2(invoice_info[0]);
            }
        });
    return false;
}
// end sale date return

// group no return
function get_group_no(){
    // variable define
	var season 	= $("#season").val();
	var sale_no = $("#sale_no").val();
	
	// validation
	if(sale_no == ''){
		console.log("Please Select Sale No");
		$("#sale_no").focus();
		return false;
	}
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_group_no_data'); ?>",
            dataType : "JSON",
            data: {sale_no: sale_no,season: season},
            success: function(data){
                document.getElementById('group_no').value = data;
				// reset value
				$("#old_season").val(season);
				$("#invoice").val('');
				$("#remarks").val('');
            }
        });
    return false;
}

// group no data pre invoice parking
function get_group_no2(id){
    // variable define
	var season 	= $("#season"+id).val();
	var sale_no = $("#sale_no"+id).val();
	
	// validation
	if(sale_no == ''){
		console.log("Please Select Sale No");
		return false;
	}
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_group_no_data2'); ?>",
            dataType : "JSON",
            data: {get_pre_invoice_id: id, sale_no: sale_no, season: season},
            success: function(data){
                var invoice_info = data.split("@");
                document.getElementById('group_no'+invoice_info[0]).value = invoice_info[1];
            }
        });
    return false;
}

// os ol shown control in catalogue
function fn_ol_os_sc(){
	
	// html entity values
	var ol_os_sc = $("#ol_os_sc").val();
	
	// check yes/no
	if(ol_os_sc=='Yes'){
		
		$("#sample_kgs").val('0.00');
		invoice_entry_cal();
		
	}else if(ol_os_sc=='No'){
		
		sg_hide_show(catalouge_name.value);
		invoice_entry_cal();
		
	}else{
        $("#sample_kgs").val('0.00');
    }
}

// invoice_entry_calculation
function invoice_entry_cal(){
	
	// html entity values
    var old_sale_no = parseFloat(document.getElementById('old_sale_no').value);
	var old_lot_no 	= parseFloat(document.getElementById('old_lot_no').value);
	
	if((old_sale_no=='0' || isNaN(old_sale_no)) && (old_lot_no=='0' || isNaN(old_lot_no))){
		
		var chest_number = parseFloat(document.getElementById('chest_number').value);
		var net_wt_per_bag = parseFloat(document.getElementById('net_wt_per_bag').value);
		var tkgs = (chest_number * net_wt_per_bag);
		var total_kgs = document.getElementById('total_kgs').value = tkgs;
		
	}else{
		var total_kgs = parseFloat(document.getElementById('total_kgs').value);
	}

    var sample_kgs = parseFloat(document.getElementById('sample_kgs').value);
    var additional_sample_kgs = parseFloat(document.getElementById('additional_sample_kgs').value);
    var nkgs = (total_kgs - sample_kgs - additional_sample_kgs);
    var net_kgs = document.getElementById('net_kgs').value = nkgs;
}


// show :: old sale and lot no data
function show_old_sale_lot_info(){
	
    // html entity values
    var season = $("#old_season2").val();
    var old_sale_no = $("#old_sale_no").val();
    var ol_ac = $("#ol_ac").val();
    var old_lot_no = $("#old_lot_no").val();
    var user = $("#user").val();

    // ajax execution
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_old_sale_lot_data'); ?>",
            dataType : "JSON",
            data: {season: season, old_sale_no: old_sale_no, ol_ac: ol_ac, old_lot_no: old_lot_no, user: user },
            success: function(data) {
               
                if(typeof data['w_house_id'] != 'undefined' && typeof data['garden_id'] != 'undefined'){
					
					$("#ol_os_sc").prop("disabled",false);
					$("#ol_os_sc").val('');
                  
                    setTimeout(function () {
                        fn_ol_os_sc();
                    }, 100);
                    
                    $('#org_id').val(data['org_id']);
                    $('#reprinted_invoice').val(data['reprinted_invoice']);

                    // To disable
                    $('#w_house_id').val(data['w_house_id']).trigger('change').prop('disabled', true);
                    $('#garden_id').val(data['garden_id']).trigger('change').prop('disabled', true);
                    $('#grade_id').val(data['grade_id']).trigger('change').prop('disabled', true);

                    $('#w_inv_no, #additional_grade').prop('readonly', true);

                    $('#w_inv_no').val(data['w_inv_no']);
                    $('#additional_grade').val(data['additional_grade']);
                    $('#chest_number').val(data['chest_number']);
                    $('#gross_wt_per_bag').val(data['gross_wt_per_bag']);
                    $('#net_wt_per_bag').val(data['net_wt_per_bag']);
                    $('#total_kgs').val(data['total_kgs']);
                    $('#sold_status').html(data['sold_status']);
                    $('#old_season').val(data['old_season']);
                    $('#invoice_group_txt').val(data['invoice_group_txt']);
                    $('#invoice_group').val(data['invoice_group']);
                    $('#invoice_group_upto').val(data['invoice_group_upto']);

                    $('#Submit').focus();

                    if (data['sold_status'] !== null && data['sold_status'] !== undefined && data['sold_status'] !== '') {
                        $('#Submit').prop('disabled', true);
                    } else {
                        $('#Submit').prop('disabled', false);
                    }

                    invoice_entry_cal();
					
                }else{
					
					$("#ol_os_sc").prop("disabled",true);
					$("#ol_os_sc").val('');

                    // To enable
                    $('#w_house_id').val(data['w_house_id']).trigger('change').prop('disabled', false);
                    $('#garden_id').val(data['garden_id']).trigger('change').prop('disabled', false);
                    $('#grade_id').val(data['grade_id']).trigger('change').prop('disabled', false);

                    $('#w_inv_no, #additional_grade').prop('readonly', false);

                    $('#Submit').prop('disabled',false);
                    $('#org_id').val('');
                    $('#reprinted_invoice').val('');
                    $('#w_inv_no').val('');
                    $('#additional_grade').val('');
                    $('#chest_number').val('0');
                    $('#gross_wt_per_bag').val('50.2');
                    $('#net_wt_per_bag').val('50');
                    $('#total_kgs').val('0');

                }
            }
        });
    return false;
}


// lot invoice entry parking
function fn_lot_parking2(){
	
	// html values
    var season = $("#season").val();
    var auction_center = $("#auction_center").val();
    var sale_no = $("#sale_no").val();
    var lot_no = $("#lot_no").val();
	
	// form validation
	if(auction_center==''){
		alert("Select - Auction Center");
		$("#auction_center").focus();
		return false;
	}
	
	if(sale_no==''){
		alert("Select - Sale No.");
		$("#sale_no").focus();
		return false;
	}
	
	if(lot_no==''){
		alert("Enter - Lot No.");
		$("#lot_no").focus();
		return false;
	}
	
	// before response
	$("#load_bulk_lot").html("Loading...");
	
	// ajax execution
    $.ajax({
		type: "POST",
		url: "<?php echo site_url('MY_Controller/sale_lot_parking_data'); ?>",
		dataType : "JSON",
		data: {season: season, auction_center: auction_center, sale_no: sale_no, lot_no: lot_no}, 
		success: function(data){
			
			// bulk manipulate
			if(data['bulk_lot'] == 'y'){
				show_lot_info('0');
				$("#load_bulk_lot").load("<?php echo site_url('MY_Controller/bulk_lot_list2'); ?>/"+season+"/"+auction_center+"/"+sale_no+"/"+lot_no);
			}else{
				show_lot_info(data['lot_id']);
				$("#load_bulk_lot").html("Single Lot");
			}
		}
	});	
}

// Searce pre invoice data
function fn_search_pre_invoice_id(){
	
	// html values
    var pre_invoice_id = $("#pre_invoice_id").val();
	
	// ajax execution
    $.ajax({
		type: "POST",
		url: "<?php echo site_url('MY_Controller/search_pre_invoice_data'); ?>",
		dataType : "JSON",
		data: {pre_invoice_id: pre_invoice_id}, 
		success: function(data){
		
            if(data['pre_invoice_id'] != null){
                $('#pre_inv_details').html('');
                document.getElementById('pre_invoice_id').value = data['pre_invoice_id'];
                document.getElementById('season').value = data['season'];
                get_invoice_entry_sale_no();
                document.getElementById('auction_center').value = data['auction_center'];
                document.getElementById('lot_no').value = data['lot_no'];
                document.getElementById('catalouge_name').value = data['catalouge_name'];
                document.getElementById('supplement_tea').value = data['supplement_tea'];
                document.getElementById('w_house_id').value = data['w_house_id'];
                document.getElementById('chest_name').value = data['chest_name'];
                document.getElementById('garden_id').value = data['garden_id'];
                document.getElementById('w_inv_no').value = data['w_inv_no'];
                document.getElementById('grade_id').value = data['grade_id'];
                document.getElementById('additional_grade').value = data['additional_grade'];
                document.getElementById('gross_wt_per_bag').value = data['gross_wt_per_bag'];
                document.getElementById('chest_number').value = data['chest_number'];
                document.getElementById('net_wt_per_bag').value = data['net_wt_per_bag'];
                setTimeout(function() { document.getElementById('sale_no').value = data['sale_no']; }, 1000);
                setTimeout(function() { get_sale_date(); }, 1500);
                sg_hide_show(data['catalouge_name']);
            }
		}
	});	
}

// invoive entry lot details
function show_lot_info(lot_id){
	
	// form validation
	if(lot_id==''){
		alert("Sorry! Lot ID is missing!!");
		return false;
	}
	
	// before response
	document.getElementById('Submit').value = 'Lot data searching...';
	
    // ajax execution
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_lot_info_data'); ?>",
            dataType : "JSON",
            data: {lot_id: lot_id},
            success: function(data) {
				if(data != '404'){
					
					document.getElementById('setup_id').value = data['id'];
					document.getElementById('catalouge_name').value = data['catalouge_name'];
					document.getElementById('supplement_tea').value = data['supplement_tea'];
					document.getElementById('chest_name').value = data['chest_name'];
					document.getElementById('old_season').value = data['old_season'];
					document.getElementById('invoice').value = data['invoice'];
					document.getElementById('group_no').value = data['group_no'];
					$("#group_no").attr("readonly", false);
					document.getElementById('old_sale_no').value = data['old_sale_no'];
					document.getElementById('old_lot_no').value = data['old_lot_no'];
					document.getElementById('ol_os_sc').value = data['ol_os_sc'];
					$("#ol_os_sc").prop("disabled",false);
					document.getElementById('w_house_id').value = data['w_house_id'];
					document.getElementById('garden_id').value = data['garden_id'];
					document.getElementById('w_inv_no').value = data['w_inv_no'];
					document.getElementById('grade_id').value = data['grade_id'];
					document.getElementById('additional_grade').value = data['additional_grade'];
					document.getElementById('chest_number').value = data['chest_number'];
					document.getElementById('gross_wt_per_bag').value = data['gross_wt_per_bag'];
					document.getElementById('net_wt_per_bag').value = data['net_wt_per_bag'];
					document.getElementById('total_kgs').value = data['total_kgs'];
					document.getElementById('sample_kgs').value = data['sample_kgs'];
					document.getElementById('additional_sample_kgs').value = data['additional_sample_kgs'];
					(data['weight_short']=='y') ? $('#weight_short').prop("checked", true) : '';
					(data['bulk_lot']=='y') ? $('#bulk_lot').prop("checked", true) : '';
					document.getElementById('net_kgs').value = data['net_kgs'];
					document.getElementById('remarks').value = data['remarks'];
					document.getElementById('Submit').value = 'Update Lot Data';
					
				}else{
					
					document.getElementById('setup_id').value = '';
					document.getElementById('catalouge_name').value = '';
					document.getElementById('supplement_tea').value = '';
					document.getElementById('chest_name').value = '';
					document.getElementById('old_season').value = '';
					document.getElementById('invoice').value = '';
					document.getElementById('group_no').value = '';
					document.getElementById('old_sale_no').value = '';
					document.getElementById('old_lot_no').value = '';
					document.getElementById('w_house_id').value = '';
					document.getElementById('garden_id').value = '';
					document.getElementById('w_inv_no').value = '';
					document.getElementById('grade_id').value = '';
					document.getElementById('additional_grade').value = '';
					document.getElementById('chest_number').value = '0';
					document.getElementById('gross_wt_per_bag').value = '55.2';
					document.getElementById('net_wt_per_bag').value = '55';
					document.getElementById('total_kgs').value = '';
					document.getElementById('sample_kgs').value = '0';
					document.getElementById('additional_sample_kgs').value = '0';
					$('#weight_short').prop("checked", false);
					$('#bulk_lot').prop("checked", false);
					document.getElementById('net_kgs').value = '0';
					document.getElementById('remarks').value = '';
					document.getElementById('Submit').value = 'Submit';
				}
            }
        });
    return false;
}

// lot sale parking
function fn_lot_parking(){
	
	// html values
    var season = $("#season").val();
    var auction_center = $("#auction_center").val();
    var sale_no = $("#sale_no").val();
    var lot_no = $("#lot_no").val();
	
	// form validation
	if(auction_center==''){
		alert("Select - Auction Center");
		$("#auction_center").focus();
		return false;
	}
	
	if(sale_no==''){
		alert("Select - Sale No.");
		$("#sale_no").focus();
		return false;
	}
	
	if(lot_no==''){
		alert("Enter - Lot No.");
		$("#lot_no").focus();
		return false;
	}
	
	// before response
	$("#load_bulk_lot").html("Loading...");
	
	// ajax execution
    $.ajax({
		type: "POST",
		url: "<?php echo site_url('MY_Controller/sale_lot_parking_data'); ?>",
		dataType : "JSON",
		data: {season: season, auction_center: auction_center, sale_no: sale_no, lot_no: lot_no}, 
		success: function(data){
			
			// bulk manipulate
			if(data['bulk_lot'] == 'y'){
				sale_lot_info('0');
				$("#load_bulk_lot").load("<?php echo site_url('MY_Controller/bulk_lot_list'); ?>/"+season+"/"+auction_center+"/"+sale_no+"/"+lot_no);
			}else{
				sale_lot_info(data['lot_id']);
				$("#load_bulk_lot").html("Single Lot");
			}
		}
	});	
}


// sale lot details
function sale_lot_info(lot_id){
	
	// form validation
	if(lot_id==''){
		alert("Lot ID is missing!");
		return false;
	}
	
	// ajax execution
    $.ajax({
		type: "POST",
		url: "<?php echo site_url('MY_Controller/sale_lot_info_data'); ?>",
		dataType : "JSON",
		data: {lot_id: lot_id}, 
		success: function(data){
			
			// result data
			$("#lot_id").val(data['id']);
			$("#lot_details").html(data['lot_details']);
			$("#inv_lot_no").val(data['inv_lot_no']);
            $("#sale_date").val(data['sale_date']);
            $("#inv_season").val(data['inv_season']);
			$("#sold_status").html(data['sold_status']);
			$("#chest_number").val(data['chest_number']);
			$("#net_wt_per_bag").val(data['net_wt_per_bag']);
			$("#sample_kgs").val(data['sample_kgs']);
			$("#net_kgs").val(data['net_kgs']);
			$("#valuation").val(data['valuation']);
			$("#sold_price").val(data['sold_price']);
			$("#complete_price").val(data['complete_price']);
			(data['buyer1']=='0') ? $("#buyer1").val('').trigger('chosen:updated') : $("#buyer1").val(data['buyer1']).trigger('chosen:updated');
			(data['buyer1_bag']=='0') ? $("#buyer1_bag").val(data['chest_number']) : $("#buyer1_bag").val(data['buyer1_bag']);
			$("#buyer_type1").val(data['buyer_type1']);
			(data['buyer2']=='0') ? $("#buyer2").val('').trigger('chosen:updated') : $("#buyer2").val(data['buyer2']).trigger('chosen:updated');
			$("#buyer2_bag").val(data['buyer2_bag']);
			$("#buyer_type2").val(data['buyer_type2']);
			(data['buyer3']=='0') ? $("#buyer3").val('').trigger('chosen:updated') : $("#buyer3").val(data['buyer3']).trigger('chosen:updated');
			$("#buyer3_bag").val(data['buyer3_bag']);
			$("#buyer_type3").val(data['buyer_type3']);
			fn_buyer_type_hs(data['buyer1'],'Buyer1');
			fn_buyer_type_hs(data['buyer2'],'Buyer2');
			fn_buyer_type_hs(data['buyer3'],'Buyer3');
			
			// disabled all content when its W/D
			if(data['sss'] == 'W/D'){
				
				$("#sold_price").prop("readonly", true);
				$("#complete_price").prop("readonly", true);
				$("#buyer1").prop("disabled", true);
				$("#buyer1_bag").prop("readonly", true);
				$("#buyer_type1").prop("disabled", true);
				$("#buyer2").prop("disabled", true);
				$("#buyer2_bag").prop("readonly", true);
				$("#buyer_type2").prop("disabled", true);
				$("#buyer3").prop("disabled", true);
				$("#buyer3_bag").prop("readonly", true);
				$("#buyer_type3").prop("disabled", true);
				
			}else{
				
				$("#sold_price").prop("readonly", false);
				$("#complete_price").prop("readonly", false);
				$("#buyer1").prop("disabled", false);
				$("#buyer1_bag").prop("readonly", false);
				$("#buyer_type1").prop("disabled", false);
				$("#buyer2").prop("disabled", false);
				$("#buyer2_bag").prop("readonly", false);
				$("#buyer_type2").prop("disabled", false);
				$("#buyer3").prop("disabled", false);
				$("#buyer3_bag").prop("readonly", false);
				$("#buyer_type3").prop("disabled", false);
			}
		}
	});	
}

// bulk sale lot details
function bulk_sale_lot_info(lot_id){
	
	// form validation
	if(lot_id==''){
		alert("Lot ID is missing!");
		return false;
	}
	
	// ajax execution
    $.ajax({
		type: "POST",
		url: "<?php echo site_url('MY_Controller/sale_lot_info_data2'); ?>",
		dataType : "JSON",
		data: {lot_id: lot_id}, 
		success: function(data){
            
            var row_id = data['id'];

			// result data
			$("#lot_id"+row_id).val(data['id']);
			$("#sold_status"+row_id).html(data['sold_status']);
			$("#chest_number"+row_id).val(data['chest_number']);
			$("#net_wt_per_bag"+row_id).val(data['net_wt_per_bag']);
			$("#sample_kgs"+row_id).val(data['sample_kgs']);
			$("#net_kgs"+row_id).val(data['net_kgs']);
			$("#sold_price"+row_id).val(data['sold_price']);
			$("#complete_price"+row_id).val(data['complete_price']);
			(data['buyer1']=='0') ? $("#buyer1"+row_id).val('').trigger('chosen:updated') : $("#buyer1"+row_id).val(data['buyer1']).trigger('chosen:updated');
			(data['buyer1_bag']=='0') ? $("#buyer1_bag"+row_id).val(data['chest_number']) : $("#buyer1_bag"+row_id).val(data['buyer1_bag']);
			$("#buyer_type1"+row_id).val(data['buyer_type1']);
			(data['buyer2']=='0') ? $("#buyer2"+row_id).val('').trigger('chosen:updated') : $("#buyer2"+row_id).val(data['buyer2']).trigger('chosen:updated');
			$("#buyer2_bag"+row_id).val(data['buyer2_bag']);
			$("#buyer_type2"+row_id).val(data['buyer_type2']);
			(data['buyer3']=='0') ? $("#buyer3"+row_id).val('').trigger('chosen:updated') : $("#buyer3"+row_id).val(data['buyer3']).trigger('chosen:updated');
			$("#buyer3_bag"+row_id).val(data['buyer3_bag']);
			$("#buyer_type3"+row_id).val(data['buyer_type3']);
			fn_bulk_buyer_type_hs(row_id,data['buyer1'],'Buyer1');
			fn_bulk_buyer_type_hs(row_id,data['buyer2'],'Buyer2');
			fn_bulk_buyer_type_hs(row_id,data['buyer3'],'Buyer3');
			
			// disabled all content when its W/D
			if(data['sss'] == 'W/D'){
				
				$("#sold_price"+row_id).prop("readonly", true);
				$("#buyer1"+row_id).prop("disabled", true);
				$("#buyer1_bag"+row_id).prop("readonly", true);
				$("#buyer_type1"+row_id).prop("disabled", true);
				$("#buyer2"+row_id).prop("disabled", true);
				$("#buyer2_bag"+row_id).prop("readonly", true);
				$("#buyer_type2"+row_id).prop("disabled", true);
				$("#buyer3"+row_id).prop("disabled", true);
				$("#buyer3_bag"+row_id).prop("readonly", true);
				$("#buyer_type3"+row_id).prop("disabled", true);
				
			}else{
				
				$("#sold_price"+row_id).prop("readonly", false);
				$("#buyer1"+row_id).prop("disabled", false);
				$("#buyer1_bag"+row_id).prop("readonly", false);
				$("#buyer_type1"+row_id).prop("disabled", false);
				$("#buyer2"+row_id).prop("disabled", false);
				$("#buyer2_bag"+row_id).prop("readonly", false);
				$("#buyer_type2"+row_id).prop("disabled", false);
				$("#buyer3"+row_id).prop("disabled", false);
				$("#buyer3_bag"+row_id).prop("readonly", false);
				$("#buyer_type3"+row_id).prop("disabled", false);
			}
		}
	});	
}

// Bulk lot sale parking
function fn_bulk_lot_parking(row_id){
	
	// html values
    var season = $("#season").val();
    var auction_center = $("#auction_center").val();
    var sale_no = $("#sale_no").val();
    var lot_no = $("#lot_no"+row_id).val();
	
	// form validation
	if(auction_center==''){
		alert("Select - Auction Center");
		$("#auction_center").focus();
		return false;
	}
	
	if(sale_no==''){
		alert("Select - Sale No.");
		$("#sale_no").focus();
		return false;
	}
	
	if(lot_no==''){
		alert("Enter - Lot No.");
		return false;
	}
	
	// ajax execution
    $.ajax({
		type: "POST",
		url: "<?php echo site_url('MY_Controller/sale_lot_parking_data2'); ?>",
		dataType : "JSON",
		data: {season: season, auction_center: auction_center, sale_no: sale_no, lot_no: lot_no, row_id: row_id}, 
		success: function(data){
			// bulk manipulate
			bulk_sale_lot_info(data['lot_id']);
		}
	});	
}


// invoice entry cart list
function show_invoice_list(edit_id){
	document.getElementById('invoice_list').innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
	$('#invoice_list').load("<?php echo site_url('MY_Controller/get_invoice_entry_data'); ?>/"+edit_id, 
	function(){ 
		//$('#mydata').dataTable(); 
	});
}

// pre invoice entry cart list
function show_pre_invoice_list(edit_id){
	document.getElementById('pre_invoice_list').innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
	$('#pre_invoice_list').load("<?php echo site_url('MY_Controller/get_pre_invoice_entry_data'); ?>/"+edit_id, 
	function(){ 
		//$('#mydata').dataTable(); 
	});
}

// market average cart list
function show_market_average_list(edit_id){
	document.getElementById('market_average_list').innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
	$('#market_average_list').load("<?php echo site_url('MY_Controller/get_market_average_data'); ?>/"+edit_id, 
	function(){ 
		//$('#mydata').dataTable(); 
	});
}

// buyer purchase cart list
function show_buyers_purchases_list(edit_id){
	document.getElementById('buyers_purchases_list').innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
	$('#buyers_purchases_list').load("<?php echo site_url('MY_Controller/get_buyers_purchases_data'); ?>/"+edit_id, 
	function(){ 
		//$('#mydata').dataTable(); 
	});
}

// show invoice verify list
function show_invoice_parking_data(){
	
	// html data
	var garden_id = ($("#garden_id").val()=='') ? "0" : $("#garden_id").val();
	var grade_id = ($("#grade_id").val()=='') ? "0" : $("#grade_id").val();
	
	// before response
	$('#pre_invoice_verify_list').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>");
	
	// brefore response
	$('#pre_invoice_verify_list').load("<?php echo site_url('MY_Controller/get_invoice_verify_list_data'); ?>/"+garden_id+"/"+grade_id);
}

// Get Sale No (if catalogue is prepared)
function get_cp_sale_no(){
	
    // html data
	var season = $("#season").val();
	var catalouge_name = $("#catalouge_name").val();
	var auction_center = $("#auction_center").val();
	
	// before response
	$('#load_sale_no').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>");
	
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_sale_no_cp_data'); ?>",
            dataType : "JSON",
            data: { season: season, catalouge_name: catalouge_name, auction_center: auction_center },
            success: function(data) {
                $('#load_sale_no').html(data);
            }
        });
    return false;
}

// Get Sale No (if catalogue is published)
function get_universal_sale_no(){ 

    // html data
	var season = $("#season").val();
	var auction_center = $("#auction_center").val();
	
	// before response
	$('#load_sale_no').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>");

	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_universal_sale_no_data'); ?>",
            dataType : "JSON",
            data: { season: season, auction_center: auction_center },
            success: function(data) {
				$('#load_sale_no').html(data);
            }
        });
    return false;
}

// Get bulk lot - Sale No (if catalogue is published)
function bulk_sale_entry_sale_no(){ 

    // html data
	var season = $("#season").val();
	var auction_center = $("#auction_center").val();
	
	// before response
	$('#load_sale_no').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>");

	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/bulk_sale_entry_sale_no_data'); ?>",
            dataType : "JSON",
            data: { season: season, auction_center: auction_center },
            success: function(data) {
				$('#load_sale_no').html(data);
            }
        });
    return false;
}

// Get Sale No
function get_invoice_entry_sale_no(edit_sale_no = null){
    // variable define
    var season = $("#season").val();
    var auction_center = $("#auction_center").val();
    
    // before response
    $('#load_sale_no').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>");
    
    //use ajax to run the check
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('MY_Controller/get_invoice_entry_sale_no_data'); ?>",
        dataType : "JSON",
        data: { 
            season: season, 
            auction_center: auction_center,
            edit_sale_no: edit_sale_no
        },
        success: function(data) {
            $('#load_sale_no').html(data);
        }
    });
    return false;
}

// Get garden letter Sale No
function get_gl_entry_sale_no(){
    // variable define
    var season = $("#season").val();
    var auction_center = $("#auction_center").val();
    
    // Clear setup_id and form mode when changing filters
    document.getElementById('setup_id').value = '';
    document.getElementById('form_mode').innerText = '';
    document.getElementById('garden_letter_text').value = '';
    
    // before response
    $('#load_sale_no').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>");
    
    //use ajax to run the check
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('MY_Controller/get_gl_entry_sale_no_data'); ?>",
        dataType : "JSON",
        data: { season: season, auction_center: auction_center },
        success: function(data) {
            $('#load_sale_no').html(data);
        }
    });
    return false;
}

// garden letter text
function loadGardenLetterText(){
    // variable define
    var auction_center = $("#auction_center").val();
    var season = $("#season").val();
    var sale_no = $("#sale_no").val();
    
    //use ajax to run the check
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('MY_Controller/get_garden_letter_data'); ?>",
        dataType : "JSON",
        data: {season: season, auction_center: auction_center, sale_no: sale_no},
        success: function(data) {
            // Check if data exists and has the setup_id
            if(data.setup_id) {
                document.getElementById('setup_id').value = data.setup_id;
                document.getElementById('garden_letter_text').value = data.garden_letter_text;
                document.getElementById('gl_story_text').value = data.gl_story_text;
                document.getElementById('form_mode').innerText = '(Edit Mode)';
            } else {
                document.getElementById('setup_id').value = '';
                document.getElementById('garden_letter_text').value = data.garden_letter_text || '';
                document.getElementById('gl_story_text').value = data.gl_story_text || '';
                document.getElementById('form_mode').innerText = '(New Mode)';
            }
        }
    });
    return false;
}
// end garden letter text

// Report :: Garden list
function get_garden_list(){
    // variable define
	var season = $("#season").val();
	var auction_center = $("#auction_center").val();
	var sale_no = $("#sale_no").val();
	// form validation
	if(season==''){
		alert("Please Select Season.");
		$("#season").focus();
		return false;
	}
	if(auction_center==''){
		alert("Please Select Auction Center.");
		$("#auction_center").focus();
		return false;
	}
	if(sale_no==''){
		alert("Please Enter Sale No.");
		$("#sale_no").focus();
		return false;
	}
	// before response
	document.getElementById('load_garden_list').innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_garden_list_data'); ?>",
            dataType : "JSON",
            data: { season: season, sale_no: sale_no, auction_center: auction_center },
            success: function(data) {
                document.getElementById('load_garden_list').innerHTML = data;
                $("#garden_id").chosen();
            }
        });
    return false;
}
// end

// Report :: Garden list sale to sale
function get_garden_list_sale_to_sale(){
    // variable define
	var season = $("#season").val();
	var auction_center = $("#auction_center").val();
	var sale_from = $("#sale_from").val();
	var sale_to = $("#sale_to").val();
	// form validation
	if(season==''){
		alert("Please Select Season.");
		$("#season").focus();
		return false;
	}
	if(auction_center==''){
		alert("Please Select Auction Center.");
		$("#auction_center").focus();
		return false;
	}
	if(sale_from==''){
		alert("Please Enter Sale From.");
		$("#sale_from").focus();
		return false;
	}
    if(sale_to==''){
		alert("Please Enter Sale To.");
		$("#sale_to").focus();
		return false;
	}
	// before response
	document.getElementById('load_garden_list').innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_garden_list_sale_to_sale_data'); ?>",
            dataType : "JSON",
            data: { season: season, sale_from: sale_from, sale_to: sale_to, auction_center: auction_center },
            success: function(data) {
                document.getElementById('load_garden_list').innerHTML = data;
                $("#garden_id").chosen();
            }
        });
    return false;
}
// end

// Report :: warehouse list
function get_warehouse_list(){
    // variable define
	var season = $("#season").val();
	var auction_center = $("#auction_center").val();
	var sale_no = $("#sale_no").val();
	// form validation
	if(season==''){
		alert("Please Select Season.");
		$("#season").focus();
		return false;
	}
	if(auction_center==''){
		alert("Please Select Auction Center.");
		$("#auction_center").focus();
		return false;
	}
	if(sale_no==''){
		alert("Please Enter Sale No.");
		$("#sale_no").focus();
		return false;
	}
	// before response
	document.getElementById('load_warehouse_list').innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_warehouse_list_data'); ?>",
            dataType : "JSON",
            data: { season: season,sale_no: sale_no, auction_center: auction_center },
            success: function(data) {
                document.getElementById('load_warehouse_list').innerHTML = data;
                $("#warehouse_id").chosen();
            }
        });
    return false;
}

// Report :: warehouse list sale to sale
function get_warehouse_list2(){
    // variable define
	var season = $("#season2").val();
	var auction_center = $("#auction_center2").val();
	var sale_no = $("#sale_no2").val();
	var sale_to = $("#sale_to2").val();
	// form validation
	if(season==''){
		alert("Please Select Season.");
		$("#season2").focus();
		return false;
	}
	if(auction_center==''){
		alert("Please Select Auction Center.");
		$("#auction_center2").focus();
		return false;
	}
	if(sale_no==''){
		alert("Please Enter Sale No.");
		$("#sale_no2").focus();
		return false;
	}
    if(sale_to==''){
		alert("Please Enter Sale No.");
		$("#sale_to2").focus();
		return false;
	}
	// before response
	document.getElementById('load_warehouse_list2').innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_warehouse_list_sale_to_sale_data'); ?>",
            dataType : "JSON",
            data: { season: season, auction_center: auction_center, sale_no: sale_no, sale_to: sale_to },
            success: function(data) {
                document.getElementById('load_warehouse_list2').innerHTML = data;
                $("#warehouse_id2").chosen();
            }
        });
    return false;
}

// valuation save data
function lot_valuation_store(season,auction_center,sale_no,lot_no,net_kgs,valuation){
	
	// before response
	$("#msg"+lot_no).fadeIn();
	document.getElementById('msg'+lot_no).innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
	
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_lot_valuation'); ?>",
            dataType : "JSON",
            data: {season: season, auction_center: auction_center, sale_no: sale_no, lot_no: lot_no, net_kgs: net_kgs, valuation: valuation},
            success: function(data) {
				if(data==1){
					document.getElementById('msg'+lot_no).innerHTML = '<font color=green>saved</font>';
					$("#msg"+lot_no).fadeOut(3000);
				}else{
					document.getElementById('msg'+lot_no).innerHTML = '<font color=red>error !!</font>';
				}
            }
        });
    return false;
}

// pre-auction :: my bid
function my_bid_store(lot_id,my_bid,buy_share,my_rating,my_bid_log,msg_id,user_id){
	// before response
	//$("#"+msg_id).fadeIn();
	document.getElementById(msg_id).innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_my_bid'); ?>",
            dataType : "JSON",
            data: {lot_id: lot_id,my_bid: my_bid,buy_share: buy_share,my_rating: my_rating,my_bid_log: my_bid_log,user_id: user_id},
            success: function(data) {
				
				if(data==1){
					
					// success msg
					document.getElementById(msg_id).innerHTML = '<font color=green>saved</font>';
					//$("#"+msg_id).fadeOut(3000);
					
					// reset value
					if(my_rating != ''){
						document.getElementById(msg_id).innerHTML = my_rating;
					}else if(buy_share != ''){
						document.getElementById(msg_id).innerHTML = buy_share;
					}else if(my_bid != ''){
						document.getElementById(msg_id).innerHTML = my_bid;
					}
					// end
					
				}else{
					document.getElementById(msg_id).innerHTML = '<font color=red>error !!</font>';
				}
            }
        });
    return false;
}
// end

// pre-auction :: reserve price
function reserve_price_store(lot_id,reserve_price,last_sale,osp,my_rating,comments,reserve_price_log,msg_id,user_id){
	
	// before response
	$("#"+msg_id).fadeIn();
	document.getElementById(msg_id).innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_reserve_price'); ?>",
            dataType : "JSON",
            data: {lot_id: lot_id,reserve_price: reserve_price,last_sale: last_sale,osp: osp,my_rating: my_rating,comments: comments,reserve_price_log: reserve_price_log,user_id: user_id},
            success: function(data) {
				
				if(data==1){
					
					// success msg
					document.getElementById(msg_id).innerHTML = '<font color=green>ok</font>';
					$("#"+msg_id).fadeOut(3000);
				
				}else{
					document.getElementById(msg_id).innerHTML = '<font color=red>error !!</font>';
				}
            }
        });
    return false;
}
// end


// auction :: sold / withdraw
function fn_sold_wd(lot_id,sold_wd,user_log,msg_id){
	
	// before response
	$("#"+msg_id).fadeIn();
	document.getElementById(msg_id).innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_sold_wd'); ?>",
            dataType : "JSON",
            data: {lot_id: lot_id,sold_wd: sold_wd,user_log: user_log},
            success: function(data) {
				
				if(data==1){
					
					// success msg
					document.getElementById(msg_id).innerHTML = '<font color=green>saved</font>';
					$("#"+msg_id).fadeOut(3000);
				
				}else{
					document.getElementById(msg_id).innerHTML = '<font color=red>error !!</font>';
				}
            }
        });
    return false;
}
// end


// cup testing :: reserve price
function tea_cup_testing_data(lot_id,testing_rating,testing_rating_log,testing_status){
	
	// before response
	$("#rating_msg").fadeIn();
	$("#rating_msg").html("saving...");
	
	//use ajax to run the check
	$.ajax({
			type: "POST",
			url: "<?php echo site_url('MY_Controller/save_cup_testing'); ?>",
			dataType : "JSON",
			data: { lot_id: lot_id, testing_rating: testing_rating, testing_rating_log: testing_rating_log, testing_status: testing_status },
			success: function(data) {
				
				if(data==1){
					
					$("#rating_msg").html("<font color=green>saved</font>");
					$("#rating_msg").fadeOut(3000);
				
				}else{
					$("#rating_msg").html("<font color=red>error !!</font>");
				}
			}
		});
	return false;	
}

function tea_cup_testing_data2(season,catalouge_name,sale_no,garden_id,testing_rating,testing_rating_log,testing_status){
	
	// before response
	$("#rating_msg").fadeIn();
	$("#rating_msg").html("saving...");
	
	//use ajax to run the check
	$.ajax({
			type: "POST",
			url: "<?php echo site_url('MY_Controller/save_cup_testing2'); ?>",
			dataType : "JSON",
			data: { season: season, catalouge_name: catalouge_name, sale_no: sale_no, garden_id: garden_id, testing_rating: testing_rating, testing_rating_log: testing_rating_log, testing_status: testing_status},
			success: function(data) {
				
				if(data==1){
					
					$("#rating_msg").html("<font color=green>saved</font>");
					$("#rating_msg").fadeOut(3000);
				
				}else{
					$("#rating_msg").html("<font color=red>error !!</font>");
				}
			}
		});
	return false;	
}
// end


// buyer auction :: buyer auction table
function buyer_bidding_price(lot_id,bidding_rate,bidding_log,user_id,sq,user_sc,bq_hbpma,share_id,chest_number,buyq,ab_value){
	
	// before response
	$("#bidding_msg").fadeIn();
	document.getElementById("bidding_msg").innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
	document.getElementById('bid_up'+lot_id).disabled=true;
	//use ajax to run the check
	$.ajax({
			type: "POST",
			url: "<?php echo site_url('MY_Controller/save_buyer_bidding'); ?>",
			dataType : "JSON",
			data: {lot_id: lot_id,bidding_rate: bidding_rate,bidding_log: bidding_log,user_id: user_id,sq: sq,user_sc: user_sc,bq_hbpma: bq_hbpma,bq_hbpma_id: share_id,bags: chest_number,ab_value: ab_value},
			success: function(data) {
				
				if(data==1){
					
					// success msg
					document.getElementById("bidding_msg").innerHTML = '<font color=green>saved</font>';
					$("#bidding_msg").fadeOut(3000);
					document.getElementById('bid_up'+lot_id).disabled=false;
				
				}else{
					
					document.getElementById("bidding_msg").innerHTML = '<font color=red>error !!</font>';
					document.getElementById('bid_up'+lot_id).disabled=false;
				}
			}
		});
	return false;	
}
// end


// catalogue prepared :: page lock
function fn_page_lock(lot_id,lot_no,last_lock_msg){
	
	var r = confirm("are you sure to " + last_lock_msg + " garden name in above of (LOT NO. " + lot_no + ") ?");
	if (r == true) {
		//use ajax to run the check
		$.ajax({
				type: "POST",
				url: "<?php echo site_url('MY_Controller/get_page_lock_data'); ?>",
				dataType : "JSON",
				data: { lot_id: lot_id },
				success: function(data) {
					if(data==1){
						window.location.reload();
					}
				}
			});
		return false;
	}else{
		alert("You pressed Cancel!");
	}
}

// update auction history
function fn_update_auction_history(){
	
	var runing_lots = document.getElementById("active_lots_id").value;
	
	//use ajax to run the check
	$.ajax({
			type: "POST",
			url: "<?php echo site_url('MY_Controller/update_auction_history'); ?>",
			dataType : "JSON",
			data: {runing_lots: runing_lots},
			success: function(data) {
				if(data==1){
					// success
				}
			}
		});
	return false;
}
// end

// pre catalogue sale no
function pre_catalogue_sale_no(){ 

    // html data
	var season = $("#season").val();
	var auction_center = $("#auction_center").val();
	
	// before response
	$('#load_sale_no').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>");

	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/pre_catalogue_sale_no_data'); ?>",
            dataType : "JSON",
            data: { season: season, auction_center: auction_center },
            success: function(data) {
				$('#load_sale_no').html(data);
            }
        });
    return false;
}

// pre valuation sale no
function pre_valuation_sale_no(){ 

    // html data
	var season = $("#season").val();
	var auction_center = $("#auction_center").val();
	
	// before response
	$('#load_sale_no').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>");

	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/pre_valuation_sale_no_data'); ?>",
            dataType : "JSON",
            data: { season: season, auction_center: auction_center },
            success: function(data) {
				$('#load_sale_no').html(data);
            }
        });
    return false;
}

// sale data save
function sale_entry_save(invoice_id,data_category){

    // html data
	var season = $("#season").val();
	var auction_center = $("#auction_center").val();
	var sale_no = $("#sale_no").val();
	var lot_no = $("#lot_no").val();
	var new_lot = parseFloat(lot_no)+1;
	var net_wt_per_bag = $("#net_wt_per_bag").val();
	var inv_lot_no = $("#inv_lot_no").val();
	var sale_date = $("#sale_date").val();
	var inv_season = $("#inv_season").val();
	var sample_kgs = $("#sample_kgs").val();
	var net_kgs = $("#net_kgs").val();
	var chest_number = $("#chest_number").val();
	var sold_price = $("#sold_price").val();
	var complete_price = $("#complete_price").val();
	var buyer1 = $("#buyer1").val();
	var buyer1_bag = $("#buyer1_bag").val();
	var buyer_type1 = $("#buyer_type1").val();
	var buyer_con1 = $("#buyer_con1").val();
	var buyer2 = $("#buyer2").val();
	var buyer2_bag = $("#buyer2_bag").val();
	var buyer_type2 = $("#buyer_type2").val();
	var buyer_con2 = $("#buyer_con2").val();
	var buyer3 = $("#buyer3").val();
	var buyer3_bag = $("#buyer3_bag").val();
	var buyer_type3 = $("#buyer_type3").val();
	var buyer_con3 = $("#buyer_con3").val();
	var user = $("#user").val();
	var last_update = $("#last_update").val();
	
	// validation
	if(season==''){
		alert("Select - Season");
		$("#season").focus();
		return false;
	}
	if(auction_center==''){
		alert("Select - Auction Center");
		$("#auction_center").focus();
		return false;
	}
	if(sale_no==''){
		alert("Select - Sale No");
		$("#sale_no").focus();
		return false;
	}
	if(invoice_id==''){
		alert("Sorry ! Lot number not available.");
		$("#lot_no").focus();
		return false;
	}
	if(sold_price==''){
		alert("Enter - Sold Price");
		$("#sold_price").focus();
		return false;
	}
    if(complete_price==''){
		alert("Enter - Complete Price");
		$("#complete_price").focus();
		return false;
	}
	if(parseFloat(sold_price) < 1){
		alert("Warning! Sold Price must be greater than zero (0).");
		$("#sold_price").focus();
		return false;
	}
	if(parseFloat(buyer1_bag) > 0){
		if(buyer1==''){
			alert("Select - Buyer 1");
			$("#buyer1").focus();
			return false;
		}
		if(buyer_type1==''){
			alert("Select - Buyer Type");
			$("#buyer_type1").focus();
			return false;
		}
        if(buyer_con1==''){
			alert("Select - Buyer Con.");
			$("#buyer_con1").focus();
			return false;
		}
	}
	if(parseFloat(buyer2_bag) > 0){
		if(buyer2==''){
			alert("Select - Buyer 2");
			$("#buyer2").focus();
			return false;
		}
		if(buyer_type2==''){
			alert("Select - Buyer Type");
			$("#buyer_type2").focus();
			return false;
		}	
        if(buyer_con2==''){
			alert("Select - Buyer Con.");
			$("#buyer_con2").focus();
			return false;
		}
	}
	if(parseFloat(buyer3_bag) > 0){
		if(buyer3==''){
			alert("Select - Buyer 3");
			$("#buyer3").focus();
			return false;
		}
		if(buyer_type3==''){
			alert("Select - Buyer Type");
			$("#buyer_type3").focus();
			return false;
		}
        if(buyer_con3==''){
			alert("Select - Buyer Con.");
			$("#buyer_con3").focus();
			return false;
		}
	}
	
	// check valid buyer sequence
	if(buyer1=='' && buyer2 !=''){
		alert("Sorry !! Buyer sequence is invalid!!. Buyer-1 is missing. ");
		$("#buyer1").focus();
		return false;
	}
	if(buyer1=='' && buyer2=='' && buyer3 !=''){
		alert("Sorry !! Buyer sequence is invalid!!. Buyer-1 and Buyer-2 is missing. ");
		$("#buyer1").focus();
		return false;
	}
	if(buyer1 !='' && buyer2 =='' && buyer3 !=''){
		alert("Sorry !! Buyer sequence is invalid!!. Buyer-2 is missing. ");
		$("#buyer2").focus();
		return false;
	}
	// end
	
	// check over chest number
	if(parseFloat(chest_number) != (parseFloat(buyer1_bag) + parseFloat(buyer2_bag) + parseFloat(buyer3_bag))){
		alert("Warning !! Sold bags must be equal to chest number.");
		return false;
	}
	
	// before response
	$('#Submit').val("Please Wait...");
	$('#Submit').prop('disabled', true);
	$('#Out_Submit').val("Please Wait...");
	$('#Out_Submit').prop('disabled', true);

	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/sale_entry_save_data'); ?>",
            dataType : "JSON",
            data: { invoice_id: invoice_id, inv_lot_no: inv_lot_no, sale_date: sale_date, inv_season: inv_season, chest_number: chest_number, sold_price: sold_price, complete_price: complete_price, buyer1: buyer1, buyer1_bag: buyer1_bag, buyer_type1: buyer_type1, buyer_con1: buyer_con1, buyer2: buyer2, buyer2_bag: buyer2_bag, buyer_type2: buyer_type2, buyer_con2: buyer_con2, buyer3: buyer3, buyer3_bag: buyer3_bag, buyer_type3: buyer_type3, buyer_con3: buyer_con3, user: user, last_update: last_update, sample_kgs: sample_kgs, net_kgs: net_kgs, net_wt_per_bag: net_wt_per_bag, data_category: data_category },
            success: function(data) {
				if(data==1){
					alert("Success!!");
					$('#lot_no').val(new_lot);
					fn_lot_parking();
					$("#lot_no").focus();
					// $("#complete_price").val('');
					//window.open("<?php echo site_url('MY_Controller/get_sale_entry_data?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&lot_no="+lot_no+"'); ?>","_blink");
					$('#Submit').val("Submit Data");
					$('#Submit').prop('disabled', false);
					$('#Out_Submit').val("Out Lot");
					$('#Out_Submit').prop('disabled', false);
				}else{
					alert(data);
					$('#Submit').val("Submit Data");
					$('#Submit').prop('disabled', false);
					$('#Out_Submit').val("Out Lot");
					$('#Out_Submit').prop('disabled', false);
				}
            }
        });
    return false;
}

// bulk sale data save
function bulk_sale_entry_save(invoice_id,data_category){

    // html data
    var season = $("#season"+invoice_id).val();
    var auction_center = $("#auction_center"+invoice_id).val();
    var sale_no = $("#sale_no"+invoice_id).val();
    var lot_no = $("#lot_no"+invoice_id).val();
    var net_wt_per_bag = $("#net_wt_per_bag"+invoice_id).val();
    var sample_kgs = $("#sample_kgs"+invoice_id).val();
    var net_kgs = $("#net_kgs"+invoice_id).val();
    var chest_number = $("#chest_number"+invoice_id).val();
    var sold_price = $("#sold_price"+invoice_id).val();
    var complete_price = $("#complete_price"+invoice_id).val();
    var buyer1 = $("#buyer1"+invoice_id).val();
    var buyer1_bag = $("#buyer1_bag"+invoice_id).val();
    var buyer_type1 = $("#buyer_type1"+invoice_id).val();
    var buyer_con1 = $("#buyer_con1"+invoice_id).val();
    var buyer2 = $("#buyer2"+invoice_id).val();
    var buyer2_bag = $("#buyer2_bag"+invoice_id).val();
    var buyer_type2 = $("#buyer_type2"+invoice_id).val();
    var buyer_con2 = $("#buyer_con2"+invoice_id).val();
    var buyer3 = $("#buyer3"+invoice_id).val();
    var buyer3_bag = $("#buyer3_bag"+invoice_id).val();
    var buyer_type3 = $("#buyer_type3"+invoice_id).val();
    var buyer_con3 = $("#buyer_con3"+invoice_id).val();
    var user = $("#user").val();
    var last_update = $("#last_update").val();

    // validation
    if(season==''){
        alert("Select - Season");
        return false;
    }
    if(auction_center==''){
        alert("Select - Auction Center");
        return false;
    }
    if(sale_no==''){
        alert("Select - Sale No");
        return false;
    }
    if(invoice_id==''){
        alert("Sorry ! Lot number not available.");
        return false;
    }
    if(sold_price==''){
        alert("Enter - Sold Price");
        return false;
    }
    if(complete_price==''){
        alert("Enter - Complete Price");
        return false;
    }
    if(parseFloat(sold_price) < 1){
        alert("Warning! Sold Price must be greater than zero (0).");
        return false;
    }
    if(parseFloat(buyer1_bag) > 0){
        if(buyer1==''){
            alert("Select - Buyer 1");
            return false;
        }
        if(buyer_type1==''){
            alert("Select - Buyer Type");
            return false;
        }
        if(buyer_con1==''){
            alert("Select - Buyer Con.");
            return false;
        }
    }
    if(parseFloat(buyer2_bag) > 0){
        if(buyer2==''){
            alert("Select - Buyer 2");
            return false;
        }
        if(buyer_type2==''){
            alert("Select - Buyer Type");
            return false;
        }	
        if(buyer_con2==''){
            alert("Select - Buyer Con.");
            return false;
        }
    }
    if(parseFloat(buyer3_bag) > 0){
        if(buyer3==''){
            alert("Select - Buyer 3");
            return false;
        }
        if(buyer_type3==''){
            alert("Select - Buyer Type");
            return false;
        }
        if(buyer_con3==''){
            alert("Select - Buyer Con.");
            return false;
        }
    }

    // check valid buyer sequence
    if(buyer1=='' && buyer2 !=''){
        alert("Sorry !! Buyer sequence is invalid!!. Buyer-1 is missing. ");
        return false;
    }
    if(buyer1=='' && buyer2=='' && buyer3 !=''){
        alert("Sorry !! Buyer sequence is invalid!!. Buyer-1 and Buyer-2 is missing. ");
        return false;
    }
    if(buyer1 !='' && buyer2 =='' && buyer3 !=''){
        alert("Sorry !! Buyer sequence is invalid!!. Buyer-2 is missing. ");
        return false;
    }
    // end

    // check over chest number
    if(parseFloat(chest_number) != (parseFloat(buyer1_bag) + parseFloat(buyer2_bag) + parseFloat(buyer3_bag))){
        alert("Warning !! Sold bags must be equal to chest number.");
        return false;
    }

    if (isNaN(parseFloat(lot_no)) || isNaN(parseFloat(net_wt_per_bag)) || isNaN(parseFloat(sample_kgs)) || isNaN(parseFloat(net_kgs))) {
        alert("Please focus on the lot number and then press Tab as some values are missing.");
        $("#lot_no"+invoice_id).focus();
        return false;
    }


    // before response
    $("#response_msg").fadeIn();
    $("#response_msg").html("Lot No. "+lot_no+" is saving...");
    $('#Submit'+invoice_id).val("Please Wait...");
    $('#Submit'+invoice_id).prop('disabled', true);
    $('#Out_Submit'+invoice_id).val("Please Wait...");
    $('#Out_Submit'+invoice_id).prop('disabled', true);

    //use ajax to run the check
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('MY_Controller/sale_entry_save_data'); ?>",
        dataType : "JSON",
        data: { invoice_id: invoice_id, chest_number: chest_number, sold_price: sold_price, complete_price: complete_price, buyer1: buyer1, buyer1_bag: buyer1_bag, buyer_type1: buyer_type1, buyer_con1: buyer_con1, buyer2: buyer2, buyer2_bag: buyer2_bag, buyer_type2: buyer_type2, buyer_con2: buyer_con2, buyer3: buyer3, buyer3_bag: buyer3_bag, buyer_type3: buyer_type3, buyer_con3: buyer_con3, user: user, last_update: last_update, sample_kgs: sample_kgs, net_kgs: net_kgs, net_wt_per_bag: net_wt_per_bag, data_category: data_category },
        success: function(data) {
            if(data==1){

                $("#response_msg").html("<font color=green>saved</font>");
                $("#response_msg").fadeOut(3000);
                // Get the current row
                var currentRow = $('#row_no'+invoice_id);
                // console.log(currentRow);
                // Find the next row
                var nextRow = currentRow.next('tr');

                // Find the first input box within the next row
                var nextInput = nextRow.find('input[type="text"]').first();

                // Focus on the next input box
                nextInput.focus();

                $('#Submit'+invoice_id).val("Submit");
                $('#Submit'+invoice_id).prop('disabled', false);
                $('#Out_Submit'+invoice_id).val("Out Lot");
                $('#Out_Submit'+invoice_id).prop('disabled', false);

                // callback
                fn_bulk_lot_parking(invoice_id);
            }else{

                $("#response_msg").html("<font color=red>error !!</font>");
                $('#Submit'+invoice_id).val("Submit");
                $('#Submit'+invoice_id).prop('disabled', false);
                $('#Out_Submit'+invoice_id).val("Out Lot");
                $('#Out_Submit'+invoice_id).prop('disabled', false);
            }
        }
    });
return false;
}

// allow number only in textbox
function isNumberKey(txt, evt) {
	var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode == 46) {
        //Check if the text already contains the . character
        if (txt.value.indexOf('.') === -1) {
          return true;
        } else {
          return false;
        }
      } else {
        if (charCode > 31 &&
          (charCode < 48 || charCode > 57))
          return false;
      }
      return true;
}

// sale no
function after_sale_sale_no(){ 

    // html data
	var season = $("#season").val();
	var auction_center = $("#auction_center").val();
	
	// before response
	$('#load_sale_no').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>");
	
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/after_sale_sale_no_data'); ?>",
            dataType : "JSON",
            data: { season: season, auction_center: auction_center },
            success: function(data) {
				
				// return data
				$('#load_sale_no').html(data);
				
				// Check if combine_center exists in jQuery
				if ($("#combine_center").length > 0){
					var combine_center = $("#combine_center").val();
					if (auction_center=='Chattogram') {
						$("#combine_center").val("Sreemangal");
					} else if(auction_center=='Sreemangal') {
						$("#combine_center").val("Chattogram");
					} else {
						$("#combine_center").val("");
					}
				}
				// end
            }
        });
    return false;
}

// Load buyer list between season range and sale range
function load_ait_buyer_list(){

	var auction_center = $("#auction_center").val();
	var season_from = $("#season_from").val();
	var season_to = $("#season_to").val();
	var sale_from = $("#sale_from").val();
	var sale_to = $("#sale_to").val();
	
	// validation 
	if(auction_center == ''){
		alert("Select - Auction Center");
		$("#auction_center").focus();
		return false;
	}
	if(season_from == ''){
		alert("Select - Season From");
		$("#season_from").focus();
		return false;
	}
	if(season_to == ''){
		alert("Select - Season To");
		$("#season_to").focus();
		return false;
	}
	if(sale_from == ''){
		alert("Enter - Sale From");
		$("#sale_from").focus();
		return false;
	}
	if(sale_to == ''){
		alert("Enter - Sale To");
		$("#sale_to").focus();
		return false;
	}
	
	$('#load_buyer_list').html("<img src='<?php echo base_url(); ?>assets/img/loading.gif' style='width:20px;'>");
	
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('MY_Controller/get_buyer_list_data'); ?>",
        dataType: "JSON",
        data: { 
			auction_center: auction_center, 
			season_from: season_from, 
			season_to: season_to,
			sale_from: sale_from, 
			sale_to: sale_to 
		},
        success: function(data) {
            $('#load_buyer_list').html(data);
            $("#buyer_id").chosen();
        }
    });
    return false;
}

// contract buyer list
function fn_contract_buyer_list(){

    // html data
	var season = $("#season").val();
	var auction_center = $("#auction_center").val();
	var sale_no = $("#sale_no").val();
	
	// validation 
	if(season == ''){
		alert("Select - Season Name");
		$("#season").focus();
		return false;
	}
	if(auction_center == ''){
		alert("Select - Auction Center");
		$("#auction_center").focus();
		return false;
	}
	if(sale_no == ''){
		alert("Select - Sale No");
		$("#sale_no").focus();
		return false;
	}
	
	// before response
	$('#load_buyer_list').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>");
	
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/contract_buyer_list_data'); ?>",
            dataType : "JSON",
            data: { season: season, auction_center: auction_center, sale_no: sale_no },
            success: function(data) {
				$('#load_buyer_list').html(data);
                $("#buyer_id").chosen();
            }
        });
    return false;
}

// ait buyer list
function fn_ait_buyer_list(){

    // html data
	var season = $("#season").val();
	var auction_center = $("#auction_center").val();
	var sale_from = $("#sale_from").val();
	var sale_to = $("#sale_to").val();
	
	// validation 
	if(season == ''){
		alert("Select - Season Name");
		$("#season").focus();
		return false;
	}
	if(auction_center == ''){
		alert("Select - Auction Center");
		$("#auction_center").focus();
		return false;
	}
	if(sale_from == ''){
		alert("Select - Sale From");
		$("#sale_from").focus();
		return false;
	}
	if(sale_to == ''){
		alert("Select - Sale To");
		$("#sale_to").focus();
		return false;
	}
	
	// before response
	$('#load_buyer_list').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>");
	
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/ait_buyer_list_data'); ?>",
            dataType : "JSON",
            data: { season: season, auction_center: auction_center, sale_from: sale_from, sale_to: sale_to },
            success: function(data) {
				$('#load_buyer_list').html(data);
                $("#buyer_id").chosen();
            }
        });
    return false;
}


function fn_buyer_type_hs(buyer_id,buyer_type){
	// send to server
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_buyer_type_data'); ?>",
            dataType : "JSON",
            data: { buyer_id: buyer_id }, success: function(data) {
				
				var buyer_info = data.split("@");
				
                if(buyer_type == 'Buyer1'){
					
					(buyer_info[0]=='Internal') ? $('.Internal_b1').show() + $('.Export_b1').hide() : '';
					(buyer_info[0]=='Export') ? $('.Export_b1').show() + $('.Internal_b1').hide() : '';
					(buyer_info[0]=='Both') ? $('.Internal_b1').show() + $('.Export_b1').show() : '';
					(buyer_info[0]=='Both') ? $('#buyer_type1').val('Internal') : $('#buyer_type1').val(buyer_info[0]);
					$('#buyer_full_name1').val(buyer_info[1]);
					
				}else if(buyer_type == 'Buyer2'){
					
					(buyer_info[0]=='Internal') ? $('.Internal_b2').show() + $('.Export_b2').hide() : '';
					(buyer_info[0]=='Export') ? $('.Export_b2').show() + $('.Internal_b2').hide() : '';
					(buyer_info[0]=='Both') ? $('.Internal_b2').show() + $('.Export_b2').show() : '';
					(buyer_info[0]=='Both') ? $('#buyer_type2').val('Internal') : $('#buyer_type2').val(buyer_info[0]);
					$('#buyer_full_name2').val(buyer_info[1]);
					
				}else if(buyer_type == 'Buyer3'){
					
					(buyer_info[0]=='Internal') ? $('.Internal_b3').show() + $('.Export_b3').hide() : '';
					(buyer_info[0]=='Export') ? $('.Export_b3').show() + $('.Internal_b3').hide() : '';
					(buyer_info[0]=='Both') ? $('.Internal_b3').show() + $('.Export_b3').show() : '';
					(buyer_info[0]=='Both') ? $('#buyer_type3').val('Internal') : $('#buyer_type3').val(buyer_info[0]);
					$('#buyer_full_name3').val(buyer_info[1]);
				}
            }
        });
    return false;
}


function fn_bulk_buyer_type_hs(row_id,buyer_id,buyer_type){
	// send to server
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_buyer_type_data'); ?>",
            dataType : "JSON",
            data: { buyer_id: buyer_id }, success: function(data) {
				
				var buyer_info = data.split("@");
				
                if(buyer_type == 'Buyer1'){
					
					(buyer_info[0]=='Internal') ? $('#Internal_b1'+row_id).show() + $('#Export_b1'+row_id).hide() : '';
					(buyer_info[0]=='Export') ? $('#Export_b1'+row_id).show() + $('#Internal_b1'+row_id).hide() : '';
					(buyer_info[0]=='Both') ? $('#Internal_b1'+row_id).show() + $('#Export_b1'+row_id).show() : '';
					(buyer_info[0]=='Both') ? $('#buyer_type1'+row_id).val('Internal') : $('#buyer_type1'+row_id).val(buyer_info[0]);
					
				}else if(buyer_type == 'Buyer2'){
					
					(buyer_info[0]=='Internal') ? $('#Internal_b2'+row_id).show() + $('#Export_b2'+row_id).hide() : '';
					(buyer_info[0]=='Export') ? $('#Export_b2'+row_id).show() + $('#Internal_b2'+row_id).hide() : '';
					(buyer_info[0]=='Both') ? $('#Internal_b2'+row_id).show() + $('#Export_b2'+row_id).show() : '';
					(buyer_info[0]=='Both') ? $('#buyer_type2'+row_id).val('Internal') : $('#buyer_type2'+row_id).val(buyer_info[0]);
					
				}else if(buyer_type == 'Buyer3'){
					
					(buyer_info[0]=='Internal') ? $('#Internal_b3'+row_id).show() + $('#Export_b3'+row_id).hide() : '';
					(buyer_info[0]=='Export') ? $('#Export_b3'+row_id).show() + $('#Internal_b3'+row_id).hide() : '';
					(buyer_info[0]=='Both') ? $('#Internal_b3'+row_id).show() + $('#Export_b3'+row_id).show() : '';
					(buyer_info[0]=='Both') ? $('#buyer_type3'+row_id).val('Internal') : $('#buyer_type3'+row_id).val(buyer_info[0]);
				}
            }
        });
    return false;
}

// garden adjust data save
function garden_adjust_entry_save(){

    // html data
	var setup_id = $("#setup_id").val();
	var season = $("#season").val();
	var old_season = $("#old_season").val();
	var auction_center = $("#auction_center").val();
	var sale_no = $("#sale_no").val();
	var garden_id = $("#garden_id").val();
	var charges_name = $("#charges_name").val();
	var charge_tk = $("#charge_tk").val();
	var particular = $("#particular").val();
	var user = $("#user").val();
	var last_update = $("#last_update").val();
	
	// validation
	if(season==''){
		alert("Select - Season");
		$("#season").focus();
		return false;
	}
	if(old_season==''){
		alert("Select - Old Season");
		$("#old_season").focus();
		return false;
	}
	if(auction_center==''){
		alert("Select - Auction Center");
		$("#auction_center").focus();
		return false;
	}
	if(sale_no==''){
		alert("Select - Sale No");
		$("#sale_no").focus();
		return false;
	}
	if(garden_id==''){
		alert("Select - Garden Name");
		$("#garden_id").focus();
		return false;
	}
	if(charges_name==''){
		alert("Enter - Charges Name");
		$("#charges_name").focus();
		return false;
	}
	if(parseFloat(charge_tk) < 1){
		alert("Warning! Charges Amount must be greater than zero (0).");
		$("#charge_tk").focus();
		return false;
	}

	// before response
	$('#Submit').val("Please Wait...");
	$('#Submit').prop('disabled', true);

	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/garden_adjust_entry_save'); ?>",
            dataType : "JSON",
            data: { setup_id: setup_id, season: season, old_season: old_season, auction_center: auction_center, sale_no: sale_no, garden_id: garden_id, charges_name: charges_name, charge_tk: charge_tk, particular: particular, user: user, last_update: last_update },
            success: function(data) {
				if(data==1){
					alert("Form Submitted: Success");
					$("#charges_name").val('');
					$("#charge_tk").val('');
					$("#particular").val('');
					$('#Submit').val("Submit Data");
					$('#Submit').prop('disabled', false);
					fn_g_charges_list();
				}else if(data==2){
					alert("Form Updated: Success");
                    $('#form_mode').html('');
					$("#setup_id").val('');
					$("#charges_name").val('');
					$("#charge_tk").val('');
					$("#particular").val('');
					$('#Submit').val("Submit Data");
					$('#Submit').prop('disabled', false);
					fn_g_charges_list();
				}else{
					alert(data);
					$('#Submit').val("Submit Data");
					$('#Submit').prop('disabled', false);
				}

                

            }
        });
    return false;
}

// garden adjust entry cart list
function get_g_charges_list(season,auction_center,sale_no,garden_id){
	$('#load_charges_list').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>");
	$('#load_charges_list').load("<?php echo site_url('MY_Controller/get_g_charges_list'); ?>/"+season+"/"+auction_center+"/"+sale_no+"/"+garden_id, 
	function(){ 
		// do nothing
	});
}


function fn_delete_garden_adjust(delete_id,season,auction_center,sale_no,garden_id){
	var r = confirm("Are you sure to delete it?");
	if (r == true) {
		$.ajax({
				type: "POST",
				url: "<?php echo site_url('MY_Controller/delete_garden_adjust'); ?>",
				dataType : "JSON",
				data: { delete_id: delete_id },
				success: function(data) {
					if(data==1){
						alert("Delete is success!!");
						get_g_charges_list(season,auction_center,sale_no,garden_id);
					}else{
						alert(data);
					}
				}
			});
		return false;
	}else{
		// do nothing
	}	
}

function fn_cc_by_grade(setup_id){
	var grade_catalog = $('#grade_id').find("option:selected").attr("title");
	$('#supplement_tea').val(grade_catalog);
    if(setup_id ==''){
        fn_e_auction_sk(grade_catalog);
    }
}

function fn_delete_lot_data(delete_id, pre_invoice_id){
	var r = confirm("Are you sure to delete it?");
	if (r == true) {
		$.ajax({
				type: "POST",
				url: "<?php echo site_url('MY_Controller/delete_lot_list'); ?>",
				dataType : "JSON",
				data: { delete_id: delete_id, pre_invoice_id: pre_invoice_id },
				success: function(data) {
					if(data==1){
						alert("Delete is success!!");
						$("#row_id"+delete_id).hide(500);
					}else{
						alert(data);
					}
				}
			});
		return false;
	}else{
		// do nothing
	}
}

// pre invoice delete
function delete_pre_inv_cart(delete_id){
	var r = confirm("Are you sure to delete it?");
	if (r == true) {
		$.ajax({
				type: "POST",
				url: "<?php echo site_url('MY_Controller/delete_pre_inv_list'); ?>",
				dataType : "JSON",
				data: { delete_id: delete_id },
				success: function(data) {
					if(data==1){
						alert("Delete is success!!");
                        // callback
                        show_pre_invoice_list();
					}else{
						alert(data);
					}
				}
			});
		return false;
	}else{
		// do nothing
	}
}

// Market average delete
function delete_mavg_cart(delete_id){
	var r = confirm("Are you sure to delete it?");
	if (r == true) {
		$.ajax({
				type: "POST",
				url: "<?php echo site_url('MY_Controller/delete_mavg_list'); ?>",
				dataType : "JSON",
				data: { delete_id: delete_id },
				success: function(data) {
					if(data==1){
						alert("Delete is success!!");
                        // callback
                        show_market_average_list();
					}else{
						alert(data);
					}
				}
			});
		return false;
	}else{
		// do nothing
	}
}

// user list
function fn_user_list(){
	$('#load_user_list').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>");
	$('#load_user_list').load("<?php echo site_url('MY_Controller/user_list'); ?>");
}

// edit user data
function fn_edit_user(edit_user_id,username,password,edit_privilege,delete_privilege){
	$("#edit_user_id").val(edit_user_id);
	$("#username").val(username);
	$("#password").val(password);
	$("#edit_privilege").val(edit_privilege);
	$("#delete_privilege").val(delete_privilege);
}

// save new user data
function fn_save_user(){
	
	// html value
	var edit_user_id = $("#edit_user_id").val();
	var username = $("#username").val();
	var username_length = $("#username").val().length;
	var password = $("#password").val();
	var password_length = $("#password").val().length;
	var edit_privilege = $("#edit_privilege").val();
	var delete_privilege = $("#delete_privilege").val();
	
	// validation
	if(username==''){
		alert("Enter - User Name");
		$("#username").focus();
		return false;
	}
	
	if(parseFloat(username_length) < 5){
		alert("The Username field must be at least 5 characters in length.");
		$("#username").focus();
		return false;
	}
	
	if(password==''){
		alert("Enter - Password");
		$("#password").focus();
		return false;
	}
	
	if(parseFloat(password_length) < 5){
		alert("The Password field must be at least 5 characters in length.");
		$("#username").focus();
		return false;
	}
	
	if(edit_privilege==''){
		alert("Select - Edit Privilege");
		$("#edit_privilege").focus();
		return false;
	}
	
	if(delete_privilege==''){
		alert("Select - Delete Privilege");
		$("#delete_privilege").focus();
		return false;
	}
	
	// before response
	$("#Submit").prop("disabled", true);
	
	// send to server
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('MY_Controller/save_user'); ?>",
		dataType : "JSON",
		data: { edit_user_id: edit_user_id, username: username, password: password, edit_privilege: edit_privilege, delete_privilege: delete_privilege },
		success: function(data) {
			
			if(data==1){
				
				alert("Success!");
				fn_user_list();
				$("#Submit").prop("disabled", false);
				
			}else if(data==2){
				
				alert("Sorry! User name is duplicate. Try to another user name.");
				$("#username").focus();
				$("#Submit").prop("disabled", false);
				
			}else{
				alert(data);
				$("#Submit").prop("disabled", false);
			}
			
		}
	});
	return false;
}

// password hide/show
function fn_pass_hide_show(){
	var input = $("#password");
	if (input.attr("type") === "password") {
		input.attr("type", "text");
		$("#pass_icon").removeClass();
		$("#pass_icon").addClass("glyphicon glyphicon-eye-close");
	} else {
		input.attr("type", "password");
		$("#pass_icon").removeClass();
		$("#pass_icon").addClass("glyphicon glyphicon-eye-open");
	}
}

function fn_change_password() {
    var old_password = $("#old_password").val();
    var new_password = $("#new_password").val();
    var confirm_password = $("#confirm_password").val();
    
    // Validation
    if(old_password == ''){
        alert("Enter - Old Password");
        $("#old_password").focus();
        return false;
    }
    
    if(new_password == ''){
        alert("Enter - New Password");
        $("#new_password").focus();
        return false;
    }
    
    if(new_password.length < 5){
        alert("The New Password field must be at least 5 characters in length.");
        $("#new_password").focus();
        return false;
    }
    
    if(confirm_password == ''){
        alert("Enter - Confirm New Password");
        $("#confirm_password").focus();
        return false;
    }
    
    if(new_password != confirm_password){
        alert("New Password and Confirm Password do not match.");
        $("#confirm_password").focus();
        return false;
    }
    
    // Before response
    $("#Submit").prop("disabled", true);
    $("#Submit").val("Processing...");
    
    // Send to server
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('MY_Controller/change_password'); ?>",
        dataType: "JSON",
        data: { 
            old_password: old_password, 
            new_password: new_password 
        },
        success: function(data) {
            if(data == 1){
                alert("Password changed successfully!");
                $("#old_password").val('');
                $("#new_password").val('');
                $("#confirm_password").val('');
            }else if(data == 0){
                alert("Old password is incorrect.");
                $("#old_password").focus();
            }else{
                alert(data);
            }
            $("#Submit").prop("disabled", false);
            $("#Submit").val("Change Password");
        },
        error: function() {
            alert("An error occurred. Please try again.");
            $("#Submit").prop("disabled", false);
            $("#Submit").val("Change Password");
        }
    });
    return false;
}

// set auction house data
function fn_ac_house(){
	
	// html value
	var auction_center = $("#auction_center").val();
	var auction_house = (auction_center=='Chattogram') ? "Progressive Tower" : "Khan Tower";
	var auction_address = (auction_center=='Chattogram') ? "837 Sheikh Mujib Road, Badamtoly, Chattogram." : "Moulvibazar Road";
	var auction_time = (auction_center=='Chattogram') ? "08:30:00" : "10:00:00";
	
	$("#auction_house").val(auction_house);
	$("#auction_address").val(auction_address);
	$("#auction_time").val(auction_time);
}

// origin list
function fn_garden_origin_list(){
	$('#load_origin_list').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>");
	$('#load_origin_list').load("<?php echo site_url('MY_Controller/garden_origin_list'); ?>");
}

// origin data save
function fn_origin_save(){
	
	// html value
	var origin_edit_id = $("#origin_edit_id").val();
	var origin_name = $("#origin_name").val();
	var last_update = $("#last_update").val();
	
	// validation
	if(origin_name==''){
		alert("Enter - Origin Name");
		$("#origin_name").focus();
		return false;
	}
	
	// before response
	$("#modal_Submit").prop("disabled", true);
	
	// send to server
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('MY_Controller/garden_origin_save'); ?>",
		dataType : "JSON",
		data: { origin_edit_id: origin_edit_id, origin_name: origin_name, last_update: last_update },
		success: function(data) {
			
			if(data==1){
				
				alert("Success!");
				fn_garden_origin_list(); // select box dropdown
				fn_garden_origin_table(); // table formated data
				// reset data
				$("#origin_edit_id").val('');
				$("#origin_name").val('');
				$("#modal_Submit").prop("disabled", false);
				$('#myModal').modal('hide');
				
			}else if(data==2){
				
				alert("Sorry! # Origin Name # is duplicate. Try to another # Origin Name #.");
				$("#origin_name").focus();
				$("#modal_Submit").prop("disabled", false);
				
			}else{
				alert(data);
				$("#modal_Submit").prop("disabled", false);
			}
		}
	});
	return false;
}

// origin list in table
function fn_garden_origin_table(){
	$('#load_origin_table').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>");
	$('#load_origin_table').load("<?php echo site_url('MY_Controller/garden_origin_table'); ?>");
}

// edit garden origin data
function fn_edit_origin(origin_edit_id,origin_name){
	$("#origin_edit_id").val(origin_edit_id);
	$("#origin_name").val(origin_name);
}
</script>