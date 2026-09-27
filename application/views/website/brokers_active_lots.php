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

<tr>
	<td style="background-color:red; color:white;" title="<?php print $result['brokers_name']; ?>"><?php print $result['short_key_code']; ?></td>
	<td style="background-color:red; color:white;"><?php print $result['lot_no']; ?></td>
	<td style="background-color:red; color:white;"><?php print $result['garden_name']; ?></td>
	<td style="background-color:red; color:white;"><?php print $result['grade_name']; ?></td>
	<td style="background-color:red; color:white;"><?php print $result['print']; ?></td>
	<td style="text-align:right; background-color:red; color:white;"><?php print round($result['chest_number']); ?></td>
	<td style="text-align:right; background-color:red; color:white;" id="bp<?php print $result['id']; ?>"><?php print round($result['valuation']); ?></td>
	<td style="background-color:#ADD8E6;" id="buyq<?php print $result['id']; ?>">0</td>
	<td style="background-color:#ADD8E6;" id="lp<?php print $result['id']; ?>">0</td>
	<td style="background-color:#ADD8E6;" id="hbp<?php print $result['id']; ?>">0</td>
	<td style="background-color:#ADD8E6;" id="bidder<?php print $result['id']; ?>"></td>
	
	<td>
		<input 
		type="text" 
		maxlength="9"
		id="rp<?php print $result['id']; ?>"
		oninput="numberOnly(this.id);" 
		onblur="if(this.value!=this.defaultValue){reserve_price_store('<?php print $result['id']; ?>',this.value,'','','','','<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','rp_msg','0')}"
		value="<?php print round($result['reserve_price']); ?>"
		style="width:50px;">
	</td>
	
	<td style="text-align:right; background-color:#ADD8E6;" id="rp_minus<?php print $result['id']; ?>"></td>
	
	<td style="text-align:right; background-color:#ADD8E6;" id="bp_minus<?php print $result['id']; ?>"></td>
	
	<td style="text-align:center;"><input type="button" value="Active" id="ma_aa<?php print $result['id']; ?>" disabled onclick="reserve_price_store('<?php print $result['id']; ?>',lp<?php print $result['id']; ?>.innerHTML,'','','','','<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','rp_msg','0')"></td>
	
	<td style="text-align:center;"><input type="button" value="Sold" id="sold<?php print $result['id']; ?>" disabled onclick="fn_sold_wd('<?php print $result['id']; ?>','sold','<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','sold_msg')"></td>
	
	<td style="text-align:center;"><input type="button" value="W/D" id="wd<?php print $result['id']; ?>" disabled onclick="fn_sold_wd('<?php print $result['id']; ?>','wd','<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','wd_msg')"></td>
	
	<td style="text-align:center;"><input type="button" value="out" id="out<?php print $result['id']; ?>" disabled onclick="fn_sold_wd('<?php print $result['id']; ?>','out','<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','out_msg')"></td>
	
	<td><?php print $result['last_sale']; ?></td>
	
	<td title="<?php if(!empty($result['brokers_rating'])){ print $result['brokers_rating']; }else{ print $result['testing_rating']; } ?>"><?php if(!empty($result['brokers_rating'])){ print mb_strimwidth($result['brokers_rating'],0,12,".."); }else{ print mb_strimwidth($result['testing_rating'],0,12,".."); } ?></td>
	
	<td title="<?php print $result['comments']; ?>"><?php if(!empty($result['comments'])){ print mb_strimwidth($result['comments'],0,12,".."); }else{ print ""; } ?></td>
	
	<td style="text-align:center; font-weight:bold; text-transform: uppercase; background-color:#ADD8E6;" id="status<?php print $result['id']; ?>"></td>
	
	<td style="background-color:red; color:white;"><?php print $result['warehouse_name']; ?></td>
	<td style="background-color:red; color:white;"><?php print $result['origin']; ?></td>
	<td style="background-color:red; color:white;"></td>
	<td style="background-color:red; color:white;"><?php print $result['w_inv_no']; ?></td>
	<td style="background-color:red; color:white; text-align:right;"><?php print $result['net_wt_per_bag']; ?></td>
	<td style="background-color:red; color:white; text-align:right;"><?php print number_format($result['net_kgs'],2,'.',''); ?></td>
</tr>

<?php
	} // foreach

}else{
	print "<tr><td colspan=30>Sorry !! upcoming sale schedule not found.</td></tr>";
}
?>

<tr style="display:none;">
	<td colspan="50">
		<input type="text" id="active_lots_id" value="<?php print $lots_arr; ?>">
		<input type="text" id="current_time" value="<?php print time(); ?>">
	</td>
</tr>