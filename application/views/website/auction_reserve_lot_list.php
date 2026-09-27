<?php
if(count($resultq2) > 0){
	
	foreach($resultq2 as $result2){

		if($result2['sale_date'] == date("Y-m-d")){
		
			## fixed value
			$season 		= $result2['season'];
			$sale_no 		= $result2['sale_no'];
			$catalouge_name = $result2['catalouge_name'];
			$sale_date 		= date("d-m-Y",strtotime($result2['sale_date']));
			$session_time	= $result2['session'];	
			$time_from		= $result2['time_from'];
			$time_to		= $result2['time_to'];
			?>

			<tr id="r_lots<?php print $result2['id']; ?>">

				<td title="<?php print $result2['brokers_name']; ?>"><?php print $result2['short_key_code']; ?></td>
				<td><?php print $result2['lot_no']; ?></td>
				<td><?php print $result2['garden_name']; ?></td>
				<td><?php print $result2['origin']; ?></td>
				<td></td>
				<td><?php print $result2['grade_name']; ?></td>
				<td><?php print $result2['warehouse_name']; ?></td>
				<td><?php print $result2['w_inv_no']; ?></td>
				<td style="text-align:right;"><?php print $result2['net_wt_per_bag']; ?></td>
				<td style="text-align:right;"><?php print number_format($result2['net_kgs'],2,'.',''); ?></td>
				<td><?php print $result2['print']; ?></td>
				<td style="text-align:right;"><?php print number_format($result2['chest_number'],2,'.',''); ?></td>
				<td style="text-align:right;"><?php print number_format($result2['valuation'],2,'.',''); ?></td>
				
				<td style="text-align:right;"><?php print $result2['my_bid']; ?></td>
				<td style="text-align:right;"><?php print $result2['buy_share']; ?></td>
				<td><?php print $result2['my_rating']; ?></td>
			</tr>
		
<?php 
		} // check today lots

	} //foreach 
?>

<?php
}else{
	print "<tr><td colspan=30 style=font-size:12px;>No matching record found.</td></tr>";
}
?>

<tr style="display:none;">
	<td colspan="50">
		<input type="text" id="season" value="<?php isset($season) ? print $season : print 0; ?>">
		<input type="text" id="sale_no" value="<?php isset($sale_no) ? print $sale_no : print 0; ?>">	
		<input type="text" id="catalouge_name" value="<?php isset($catalouge_name) ? print $catalouge_name : print 0; ?>">
		<input type="text" id="sale_date" value="<?php isset($sale_date) ? print $sale_date : print 0; ?>">
		<input type="text" id="session_time" value="<?php isset($session_time) ? print $session_time : print 0; ?>">
		<input type="text" id="target_to" value="<?php isset($time_from) ? print strtotime($time_from) : print 0; ?>">
		<input type="text" id="target_end" value="<?php isset($time_to) ? print strtotime($time_to) : print 0; ?>">
		<input type="text" id="start_from" value="<?php echo time(); ?>">
	</td>
</tr>