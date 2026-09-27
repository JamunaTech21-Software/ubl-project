<?php
## reset value
$lots_id = 0;
$lots_arr = 0;

## check result data
if(count($resultq1) > 0){
	
	foreach($resultq1 as $result){

		## fixed value
		@$lots_id 		= "$lots_id,$result[id]";
		$lots_arr 		= $lots_id;
		?>

		<tr id="tr<?php print $result['id']; ?>">

			<td style="background-color:red; color:white;" title="<?php print $result['brokers_name']; ?>"><?php print $result['short_key_code']; ?></td>
			<td style="background-color:red; color:white;"><?php print $result['lot_no']; ?></td>
			<td style="background-color:red; color:white;"><?php print $result['garden_name']; ?></td>
			<td style="background-color:red; color:white;"><?php print $result['grade_name']; ?></td>
			<td style="text-align:right; background-color:red; color:white;"><?php print number_format($result['net_kgs'],2,'.',''); ?></td>
			<td style="text-align:right; background-color:red; color:white;" id="bag<?php print $result['id']; ?>"><?php print round($result['chest_number']); ?></td>
			<td style="text-align:right; background-color:red; color:white;" id="bp<?php print $result['id']; ?>"><?php print round($result['valuation']); ?></td>
			
			<td style="background-color:#ADD8E6;" id="buyq<?php print $result['id']; ?>">0</td>
			<td style="background-color:#ADD8E6;" id="bqq<?php print $result['id']; ?>">0</td>
			<td style="background-color:#ADD8E6;" id="sqq<?php print $result['id']; ?>">0</td>
			<td style="background-color:#ADD8E6;" id="sqqs<?php print $result['id']; ?>">0</td>
			<td style="background-color:#ADD8E6;" id="hbp<?php print $result['id']; ?>">0</td>
			<td style="background-color:#ADD8E6;" id="mab<?php print $result['id']; ?>">0</td>
			<td style="background-color:#ADD8E6;" id="sq_mab<?php print $result['id']; ?>">0</td>
			
			<td style="text-align:right; background-color:#ADD8E6;" id="rp<?php print $result['id']; ?>"><?php print round($result['reserve_price']); ?></td>
			<td style="background-color:#ADD8E6; font-size:9px; color:green;" id="aa<?php print $result['id']; ?>"></td>
			<td style="background-color:#ADD8E6;" id="bidder<?php print $result['id']; ?>"></td>
			
			<?php
			/*
			<td style="background-color:red; color:white;"><?php print $result['origin']; ?></td>
			<td style="background-color:red; color:white;"></td>
			*/
			?>
			
			<td style="background-color:red; color:white;"><?php print $result['print']; ?></td>
			<td style="background-color:red; color:white;"><?php print $result['warehouse_name']; ?></td>
			
			<td style="text-align:center;">
				<input 
				type="button" 
				id="knock<?php print $result['id']; ?>" 
				value="Knock" 
				style="font-size:9px;" 
				onclick="knock_me('<?php print $result['id']; ?>',rp<?php print $result['id']; ?>.innerHTML,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','<?php print $ubl_web_user_id; ?>',bag<?php print $result['id']; ?>.innerHTML,'<?php print $web_user_sc; ?>','<?php print $result['chest_number']; ?>',buyq<?php print $result['id']; ?>.innerHTML);">
			</td>
			
			<td style="text-align:right;">
				<input 
				type="text" 
				maxlength="2"
				id="sq<?php print $result['id']; ?>" 
				oninput="numberOnly(this.id);"
				onkeyup="if(this.value > 0){bq_hbpma<?php print $result['id']; ?>.readOnly=true;}else{bq_hbpma<?php print $result['id']; ?>.readOnly=false;}"
				onfocus="if(this.value == '0') { this.value = ''; } this.select();" 
				onblur="if(this.value == '') { this.value = '0'; }"
				<?php if($result['chest_number'] <= 19){ ?> value="<?php print $result['chest_number']; ?>" readonly <?php }else{ ?> value="0" <?php } ?>
				style="width:50px;">
			</td>
			
			<td style="text-align:right;">
				<input 
				type="text" 
				maxlength="2"
				id="bq_hbpma<?php print $result['id']; ?>" 
				oninput="numberOnly(this.id);"
				onkeyup="if(this.value > 0){sq<?php print $result['id']; ?>.readOnly=true; bidding_rate<?php print $result['id']; ?>.readOnly=true; bid_up<?php print $result['id']; ?>.disabled=true;}else{sq<?php print $result['id']; ?>.readOnly=false; bidding_rate<?php print $result['id']; ?>.readOnly=false; bid_up<?php print $result['id']; ?>.disabled=false;}"
				onfocus="this.select();" 
				onblur="if(this.value!=this.defaultValue){fn_bq_hbpma('<?php print $result['id']; ?>',hbp<?php print $result['id']; ?>.innerHTML,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','<?php print $ubl_web_user_id; ?>',bq_hbpma<?php print $result['id']; ?>.value,'<?php print $web_user_sc; ?>',buyq<?php print $result['id']; ?>.innerHTML,'<?php print $result['chest_number']; ?>',0,'0');}"
				<?php if($result['chest_number'] <= 19){ ?> value="" readonly <?php } ?>
				style="width:50px;">
			</td>
			
			<td style="text-align:right;">
				<input 
				type="text" 
				maxlength="3"
				id="bidding_rate<?php print $result['id']; ?>" 
				oninput="numberOnly(this.id);"
				onfocus="this.select();" 
				onblur="if(this.value!=this.defaultValue){fn_my_bid('<?php print $result['id']; ?>',bidding_rate<?php print $result['id']; ?>.value,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','<?php print $ubl_web_user_id; ?>',sq<?php print $result['id']; ?>.value,'<?php print $web_user_sc; ?>','0','0','<?php print $result['chest_number']; ?>',buyq<?php print $result['id']; ?>.innerHTML,hbp<?php print $result['id']; ?>.innerHTML);}"
				style="width:50px;">
			</td>
			
			<td style="text-align:center;">
				<input 
				type="button" 
				id="bid_up<?php print $result['id']; ?>" 
				value="Bid Up" 
				style="font-size:9px;" 
				onclick="bid_up_count('<?php print $result['id']; ?>',hbp<?php print $result['id']; ?>.innerHTML,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','<?php print $ubl_web_user_id; ?>',sq<?php print $result['id']; ?>.value,'<?php print $web_user_sc; ?>','1','<?php print $result['chest_number']; ?>',buyq<?php print $result['id']; ?>.innerHTML);">
			</td>
			
			<td style="text-align:right;">
				<input 
				type="text" 
				maxlength="3"
				id="auto_bid<?php print $result['id']; ?>" 
				oninput="numberOnly(this.id);"
				onfocus="this.select();" 
				onblur="auto_bid_count('<?php print $result['id']; ?>');"
				value=""
				style="width:50px;">
			</td>
			
			<td style="background-color:red; color:white; text-align:center;"><?php print $result['w_inv_no']; ?></td>
			<td style="text-align:right; background-color:red; color:white;"><?php print $result['net_wt_per_bag']; ?></td>
			
			<td style="text-align:center; font-weight:bold; text-transform: uppercase; background-color:#ADD8E6;" id="status<?php print $result['id']; ?>"></td>
			
			<td style="background-color:red;"><?php print $result['my_rating']; ?></td>
		</tr>			
			
<?php 
	} //foreach 

}else{
	print "<tr><td colspan=30 style=font-size:12px; id=\"session_start_td\">No matching record found.</td></tr>";
}
?>

<tr style="display:none;">
	<td colspan="50">
		<input type="text" id="active_lots_id" value="<?php print $lots_arr; ?>">
		<input type="text" id="current_time" value="<?php print time(); ?>">
	</td>
</tr>
