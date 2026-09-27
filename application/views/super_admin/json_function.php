<script>
$(function () {
	
    // Sending data: - Vat / Tax And Sample Information
    $('#vat_tax_sample_details').submit(function(e){
        e.preventDefault(); // for prevent form submit

        // BEFORE RESPONSE
        $('#Submit').val('Please Wait...');
        document.getElementById('Submit').disabled=true;
        
        //use ajax to run the check
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_vat_tax_sample_setup'); ?>",
            dataType : "JSON",
            data: $('#vat_tax_sample_details').serialize(),
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
    // END Sending data: - Vat / Tax And Sample Information

});

// Profile data
function profile_data_return(profile_name,profile_id){
    //use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_profile_data'); ?>",
            dataType : "JSON",
            data: {profile_name: profile_name,profile_id: profile_id},
            success: function(data) {
                document.getElementById('profile_view').innerHTML = data;
            }
        });
        return false;
}
// end Profile data

// sale order :: sale no. return
function get_cp_sale_no_admin(){
    // variable define
	var season = $("#season").val();
	// before response
	document.getElementById('load_sale_no').innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
	//use ajax to run the check
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_sale_no_admin'); ?>",
            dataType : "JSON",
            data: {season: season},
            success: function(data) {
                document.getElementById('load_sale_no').innerHTML = data;
            }
        });
    return false;
}
// end

function fn_delete_garden(delete_id,data_status){
	var r = confirm("Are you sure to " + data_status + " it?");
	if (r == true) {
		$.ajax({
				type: "POST",
				url: "<?php echo site_url('MY_Controller/delete_setup_data'); ?>",
				dataType : "JSON",
				data: { delete_id: delete_id, delete_name: 'garden_list', data_status: data_status },
				success: function(data) {
					if(data==1){
						alert("Success!!");
						window.location.reload();
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

/*
// set timer
function set_timer(){
    // html value
    var timer_content = $("#count").val();
    // validation
    if(timer_content==''){
        alert("Please enter countdown value.");
        $("#count").focus();
        return false;
    }
    if(isNaN(timer_content)){
        alert("Please enter countdown value in number format(Ex:60,120).");
        $("#count").focus();
        return false;
    }
    // send data
    $.post("<?php echo site_url('MY_Controller/auction_role_timer_txt'); ?>", {timer_content: timer_content}, function(data, status){
        if(status=='success'){
            alert("Timer has been set successfully. your " + timer_content + " seconds countdown start now.");
            auctioner_time_count();
        } 
    });
}
// end

// auctionar timer count control
function auctioner_time_count(){
    var runing_lots = $("#active_lots").val();

    // start timer
    var a_interval = setInterval(function(){
      $.get("<?php echo base_url().'assets/timer.txt'; ?>", function(data) {
        count = parseInt(data);
        if(count > 1){ 
            document.getElementById('count').value = count;
            // Write data in 'auction_role.txt' . 
            $.post("<?php echo site_url('MY_Controller/auction_role_timer_txt'); ?>", {timer_content: count-1}, function(result){
                // message goes to here
            });
            // end file write
        }else if(count == 1){
            clearInterval(a_interval);
            document.getElementById('count').value = "Time Expired";
            // Write data in 'auction_role.txt' . 
            $.post("<?php echo site_url('MY_Controller/auction_role_timer_txt'); ?>", {runing_lots: runing_lots,timer_content: 'stop'}, function(result){
                setTimeout(function(){
                    
                    // before response
                    document.getElementById('auction_lot_data').innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
                    // load cart list
                    $("#auction_lot_data").load("<?php echo site_url('MY_Controller/admin_auction_lot_list'); ?>", function(){
                        document.getElementById('sale_season').innerHTML = 'Season: ' + document.getElementById('season').value + ' / Sale Number: ' + document.getElementById('sale_no').value;
                    });

                }, 2000);
            });
            // end file write
        }
      });

    }, 1000);
    // end timer
}
// end
*/
</script>