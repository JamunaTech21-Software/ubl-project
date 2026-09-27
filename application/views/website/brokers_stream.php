<?php
//header("Access-Control-Allow-Origin: *");
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

if(count($resultq1) > 0){
	
	foreach($resultq1 as $stream_row){
		
		## prepared auction data
		$balance_sq = round($stream_row['bags'] - $stream_row['bq']);
		$sq1 		= ($stream_row['sq1'] > $balance_sq) ? $balance_sq : $stream_row['sq1'];
		
		$balance_sq = round($stream_row['bags'] - ($stream_row['bq'] + $sq1));
		$sq2 		= ($stream_row['bags'] > 30 && $stream_row['sq2'] > $balance_sq) ? $balance_sq : $stream_row['sq2'];
		
		$buy_qty 	= $stream_row['bq'] + $sq1 + $sq2;
		
		$bidder  = $stream_row['bq_bidder'];
		$bidder .= ($sq1 > 0) ? '/'. $stream_row['sq1_bidder'] : '';
		$bidder .= ($sq2 > 0) ? '/'. $stream_row['sq2_bidder'] : '';
		## end prepared data
		
		// live data
		$live_data = "$stream_row[lot_id]:$stream_row[my_bid]:$stream_row[bq]:$sq1:$sq2:$stream_row[bq_mab]:$stream_row[sq_mab]:$bidder:$buy_qty:$stream_row[reserve_price]:$stream_row[sold_wd]:$stream_row[bq_bidder]";
		echo "data: {$live_data}\n\n";

	}

}else{
	// do nothing
}	

flush();
?>