<?php
/**
 * @author Shivaraj <mrshivaraj123@gmail.com> date
 * @tutorial Sample file
 */

	if( strtotime('-3 day' ) <= $tmstmp )
	{
		$data_array['day_before_yesterday'][$i]['day_before_yesterday']=$month;
		$data_array['day_before_yesterday'][$i]['sno']=$pg+$i;
		$data_array['day_before_yesterday'][$i]['money_id']=$row['money_id'];
		$data_array['day_before_yesterday'][$i]['uid']=$row['uid'];
//		    $data_array['day_before_yesterday'][$i]['lat']=$row['lat'];
//		    $data_array['day_before_yesterday'][$i]['long']=$row['long'];
		$data_array['day_before_yesterday'][$i]['visibility']=$row['visibility'];
		$data_array['day_before_yesterday'][$i]['money_title']= $this->format_text($row['money_title']);
		$data_array['day_before_yesterday'][$i]['money_amount']=$row['money_amount'];
//		    $data_array['day_before_yesterday'][$i]['item_unit_price']=$row['item_unit_price'];
//		    $data_array['day_before_yesterday'][$i]['item_units']=$row['item_units'];
//		    $data_array['day_before_yesterday'][$i]['item_qty']=$row['item_qty'];
//		    $data_array['day_before_yesterday'][$i]['total_price']=$row['total_price'];
		$data_array['day_before_yesterday'][$i]['money_flow_direction']=$row['money_flow_direction'];
//		    $data_array['day_before_yesterday'][$i]['file_id']=$row['file_id'];
//		    $data_array['day_before_yesterday'][$i]['category_id']=$row['category_id'];
//		    $data_array['day_before_yesterday'][$i]['modified_on']=$row['modified_on'];
		$data_array['day_before_yesterday'][$i]['timestamp']=$row['timestamp'];
	}
?>
