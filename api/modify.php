<?php
require_once 'google/appengine/api/taskqueue/PushTask.php';
use \google\appengine\api\taskqueue\PushTask;

//include "blocks/paths.php";
//include $myclass_url;

//$get = ($_REQUEST);
//print_r($get);die();

class Modify extends Baseclass
{
    function modify_single_content_info($get)
    {
        $linkid=$this->db_conn();
        $cond='';
        $output=array();
//        $uid=mysql_real_escape_string(urldecode($get['uid']));
        $module=mysql_real_escape_string(urldecode($get['module']));
	
	if($module=='profile')
	{
                    if(!isset($get['uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
                    if(!isset($get['name'])) $ob->print_error(array("status"=>"error","response"=>"Undefined name."));
                    if(!isset($get['email'])) $ob->print_error(array("status"=>"error","response"=>"Undefined email."));
                    $output = $this->modify_profile($get);
			//session
//			$get['uid']=$output['uid'];
//			$session_id=$this->create_session($get);
//		    $output['session_id']=$session_id;
	}
	elseif($module=='group')
	{
		$output=$this->modify_group($get);
	}
	elseif($module=='member')
	{
		$output=$this->modify_member($get);
	}
	else
	{
	    $output=$this->modify_stream_content($get);
//	    $content_id=mysql_real_escape_string(urldecode($get['content_id']));
	    
	    /*if(!isset($get['uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
//	    if(!isset($get['timestamp'])) $ob->print_error(array("status"=>"error","response"=>"Undefined timestamp."));
	    
	    $lat=(!isset($get['lat']))? '' : mysql_real_escape_string(urldecode($get['lat']));
	    $long=(!isset($get['long']))? '' : mysql_real_escape_string(urldecode($get['long']));

	    $timestamp=(!isset($get['timestamp']))? date('Y-m-d H:i:s',time()) : (mysql_real_escape_string(urldecode($get['timestamp'])));//Unix timestamp

	    $rslt = mysql_query("select `uid` from generic_profile where `uid`='$uid'",$linkid) or $this->print_error(mysql_error($linkid));
	    $row = mysql_fetch_array($rslt);
	    if($row['uid']=='') { $this->print_error("User/uid does not exits."); }

	    $rslt = mysql_query("select `content_id` from `tbl_content` where `content_id`=$content_id",$linkid) or print_error(mysql_error($linkid));
	    $row = mysql_fetch_array($rslt);
	    if($row['content_id']=='') { $this->print_error("Content id does not exits in contents record."); }

	    $modified_text =(!isset($get['modified_text']))? '' : mysql_real_escape_string(urldecode($get['modified_text']));

	    if($module == 'note') {

		$rslt = mysql_query("select `content_id` from `tbl_notes` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
		$row = mysql_fetch_array($rslt);
		if($row['content_id']=='') { $this->print_error("Content id does not exits in notes record."); }

		if($modified_text != '') {    if($cond!='') $cond .= ",";    $cond .= "n.note_text='".$modified_text."'";        }
		if($lat != '') {     if($cond!='') $cond .= ",";         $cond .= "c.lat=".$lat;        }
		if($long != '') {     if($cond!='') $cond .= ",";        $cond .= "c.long=".$long;        }
		if($timestamp != '') {     if($cond!='') $cond .= ",";        $cond .= "c.timestamp=".$timestamp;        }

		$sql = "UPDATE 
		    `tbl_notes` n
		    JOIN `tbl_content` c on c.content_id=n.content_id
		    SET $cond
		    WHERE c.content_id =$content_id";
		//echo '<pre>';die($sql);
		$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

		    if(mysql_errno($linkid)) {
			$this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		    }
		    else {
			$output = array("status"=>"success","result"=>"Content is Updated.");
			$taskname = $this->createTaskQueue($content_id,$module,$uid);
		    }
	    }
	    elseif($module == 'money') {
    //            $title =(!isset($get['title']))? '' : mysql_real_escape_string(urldecode($get['title']));
    //            $desc =(!isset($get['desc']))? '' : mysql_real_escape_string(urldecode($get['desc']));
    //            $amount =(!isset($get['amount']))? '' : mysql_real_escape_string(urldecode($get['amount']));
//		$rslt = mysql_query("select `content_id` from `tbl_expenses` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
//		$row = mysql_fetch_array($rslt);
//		if($row['content_id']=='') { $this->print_error("Content id does not exits in expenses record."); }
		if($modified_text != '') {   if($cond!='') $cond .= ",";     $cond .= "e.title='".$modified_text."'";        }
		if($modified_text != '') {       if($cond!='') $cond .= ",";       $cond .= "e.desc='".$modified_text."'";        }
    //            if($amount != '') {      if($cond!='') $cond .= ",";       $cond .= "e.amount='".$amount."'";        }
		if($lat != '') {     if($cond!='') $cond .= ",";         $cond .= "c.lat=".$lat;        }
		if($long != '') {     if($cond!='') $cond .= ",";        $cond .= "c.long=".$long;        }
		if($timestamp != '') {  if($cond!='') $cond .= ",";  $cond .= "c.timestamp='".$timestamp."'";        }

		$sql = "UPDATE 
		    `tbl_expenses` e
		    JOIN `tbl_content` c on c.content_id=e.content_id
		    SET $cond
		    WHERE c.content_id =$content_id";
		    //echo '<pre>';die($sql);
		    $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

		    if(mysql_errno($linkid)) {
			$this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		    }
		    else {
			$output = array("status"=>"success","result"=>"Content is Updated.");
			$taskname = $this->createTaskQueue($content_id,$module,$uid);
		    }
	    }
	    elseif($module == 'reminder') {
		$remind_time =(!isset($get['remind_time'])) ? '' : mysql_real_escape_string(urldecode($get['remind_time']));
    //            $reminder_name =(!isset($get['reminder_name'])) ? '' : mysql_real_escape_string(urldecode($get['reminder_name']));

		$rslt = mysql_query("select `content_id` from `tbl_reminders` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
		$row = mysql_fetch_array($rslt);
		if($row['content_id']=='') { $this->print_error("Content id does not exits in reminders record."); }
		if($remind_time != '') {      $cond .= "r.remind_time='".strtotime($remind_time)."'";        }
		if($modified_text != '') {  if($cond!='') $cond .= ",";  $cond .= "r.reminder_name='".$modified_text."'";  }
		//if($amount != '') {   if($cond!='') $cond .= ",";  $cond .= "r.amount='".$amount."'";        }
		if($lat != '') {     if($cond!='') $cond .= ",";         $cond .= "c.lat=".$lat;        }
		if($long != '') {     if($cond!='') $cond .= ",";        $cond .= "c.long=".$long;        }
		if($timestamp != '') {  if($cond!='') $cond .= ",";  $cond .= "c.timestamp='".$timestamp."'";        }
		$sql = "UPDATE
		    `tbl_reminders` r
		    JOIN `tbl_content` c on c.content_id=r.content_id
		    SET $cond
		    WHERE c.content_id = $content_id";
		//echo '<pre>';die($sql);
		$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

		if(mysql_errno($linkid)) {
		    $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		}
		else {
		    $output = array("status"=>"success","result"=>"Content is Updated.");
		}
		$taskname = $this->createTaskQueue($content_id,$module,$uid);
	    }
	    elseif($module == 'shoppinglist') {
		//$remind_time =(!isset($get['remind_time'])) ? '' : mysql_real_escape_string(urldecode($get['remind_time']));
	       // $reminder_name =(!isset($get['reminder_name'])) ? '' : mysql_real_escape_string(urldecode($get['reminder_name']));

		$rslt = mysql_query("select `content_id` from `tbl_shoppinglist` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
		$row = mysql_fetch_array($rslt);
		if($row['content_id']=='') { $this->print_error("Content id does not exits in reminders record."); }
		/*$item_name=mysql_real_escape_string(urldecode($get['item_name']));
		$file_id=(!isset($get['file_id']) )? '0' : mysql_real_escape_string(urldecode($get['file_id']));
		$shared_with=(!isset($get['shared_with']) )? '1' : mysql_real_escape_string(urldecode($get['shared_with']));
		$qty=(!isset($get['qty']) )? '' : mysql_real_escape_string(urldecode($get['qty']));* /
		$item_qty=(!isset($get['item_qty']) )? '1' : mysql_real_escape_string(urldecode($get['item_qty']));
		$units=(!isset($get['units']) )? '' : mysql_real_escape_string(urldecode($get['units']));
		$shopping_status=(!isset($get['shopping_status']) )? '1' : mysql_real_escape_string(urldecode($get['shopping_status']));

		if($modified_text != '') {      $cond .= "sl.item_name='".$modified_text."',sl.item_description='".$modified_text."',item_qty='".$item_qty."',units='".$units."',shopping_status='".$shopping_status."' ";        }
		/*if($reminder_name != '') {     
		    if($cond!='') $cond .= ",";
		    $cond .= "r.reminder_name='".$reminder_name."'";
		}
		if($amount != '') {   if($cond!='') $cond .= ",";  $cond .= "r.amount='".$amount."'";        }
		* /
		if($lat != '') {     if($cond!='') $cond .= ",";         $cond .= "c.lat=".$lat;        }
		if($long != '') {     if($cond!='') $cond .= ",";        $cond .= "c.long=".$long;        }
		if($timestamp != '') {  if($cond!='') $cond .= ",";  $cond .= "c.timestamp='".$timestamp."'";        }

		$sql = "UPDATE
		    `tbl_shoppinglist` sl
		    JOIN `tbl_content` c on c.content_id=sl.content_id
		    SET $cond
		    WHERE c.content_id = $content_id";
    //            echo '<pre>';die($sql);
		$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));


		if(mysql_errno($linkid)) {
		    $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		}
		else {
		    $output = array("status"=>"success","result"=>"Content is Updated.");
		}

		$taskname = $this->createTaskQueue($content_id,$module,$uid);

	    }
	    else { $output = $this->unknown(); }*/
	}
        return $output;
    }
    
    function modify_stream_content($get)
    {
	   $linkid=$this->db_conn();
    	    
	    if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Undefined uid."));
	    if(!isset($get['content_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined Content id."));
//	    if(!isset($get['timestamp'])) $ob->print_error(array("status"=>"error","response"=>"Undefined timestamp."));
	    
	    $content_id=mysql_real_escape_string(urldecode($get['content_id']));
	    $uid=mysql_real_escape_string(urldecode($get['uid']));
	    $module=mysql_real_escape_string(urldecode($get['module']));
	    $lat=(!isset($get['lat']))? '' : mysql_real_escape_string(urldecode($get['lat']));
	    $long=(!isset($get['long']))? '' : mysql_real_escape_string(urldecode($get['long']));
	    $timestamp=(!isset($get['timestamp']))? date('Y-m-d H:i:s',time()) : (mysql_real_escape_string(urldecode($get['timestamp'])));//Unix timestamp
	    
	    //==== check is uid valid ==========
	    $rslt = mysql_query("select `uid` from m_profile where `uid`='$uid'",$linkid) or $this->print_error(mysql_error($linkid));
	    $row = mysql_fetch_array($rslt);
	    if($row['uid']=='') { $this->print_error("User/uid does not exits."); }
	    //========= check is valid content id==============
	    $rslt = mysql_query("select `content_id` from `m_content` where `content_id`='$content_id' AND `uid`='$uid'",$linkid) or print_error(mysql_error($linkid));
	    $row = mysql_fetch_array($rslt);
	    if($row['content_id']=='') { $this->print_error("Content id does not exits OR content id is not belongs to you."); }

//	    $modified_text =(!isset($get['modified_text']))? '' : mysql_real_escape_string(urldecode($get['modified_text']));
	    if($module == 'note')
	    {
		$rslt = mysql_query("select `content_id` from `tbl_notes` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
		$row = mysql_fetch_array($rslt);
		if($row['content_id']=='') { $this->print_error("Content id does not exits in notes record."); }

		if($modified_text != '') {    if($cond!='') $cond .= ",";    $cond .= "n.note_text='".$modified_text."'";        }
		if($lat != '') {     if($cond!='') $cond .= ",";         $cond .= "c.lat=".$lat;        }
		if($long != '') {     if($cond!='') $cond .= ",";        $cond .= "c.long=".$long;        }
		if($timestamp != '') {     if($cond!='') $cond .= ",";        $cond .= "c.timestamp=".$timestamp;        }

		$sql = "UPDATE 
		    `tbl_notes` n
		    JOIN `tbl_content` c on c.content_id=n.content_id
		    SET $cond
		    WHERE c.content_id =$content_id";
		//echo '<pre>';die($sql);
		$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

		    if(mysql_errno($linkid)) {
			$this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		    }
		    else {
			$output = array("status"=>"success","result"=>"Content is Updated.");
			$taskname = $this->createTaskQueue($content_id,$module,$uid);
		    }
	    }
	    elseif($module == 'money')
	    {
		    if(!isset($get['money_id'])) $this->print_error(array("status"=>"error","response"=>"Please specify money id."));
		    if(!isset($get['money_title'])) $this->print_error(array("status"=>"error","response"=>"Please specify money title."));
		    if(!isset($get['money_amount'])) $this->print_error(array("status"=>"error","response"=>"Please specify amount."));
		    if(!isset($get['item_unit_price'])) $this->print_error(array("status"=>"error","response"=>"Please specify item price."));
		    if(!isset($get['item_units'])) $this->print_error(array("status"=>"error","response"=>"Please specify item units."));
		    if(!isset($get['item_qty'])) $this->print_error(array("status"=>"error","response"=>"Please specify item quantity."));
		    if(!isset($get['total_price'])) $this->print_error(array("status"=>"error","response"=>"Please specify item quantity."));
		    if(!isset($get['money_flow_direction'])) $this->print_error(array("status"=>"error","response"=>"Please specify money flow direction."));

		    $money_id=mysql_real_escape_string(urldecode($get['money_id']));
		    $money_title=mysql_real_escape_string(urldecode($get['money_title']));
		    $money_amount=mysql_real_escape_string(urldecode($get['money_amount']));
		    $item_unit_price=(!isset($get['item_unit_price']) )? '' : mysql_real_escape_string(urldecode($get['item_unit_price']));
		    $item_units=(!isset($get['item_units']) )? '' : mysql_real_escape_string(urldecode($get['item_units']));
		    $item_qty=(!isset($get['item_qty']) )? '' : mysql_real_escape_string(urldecode($get['item_qty']));
		    $total_price=(!isset($get['total_price']) )? '' : mysql_real_escape_string(urldecode($get['total_price']));
		    $money_flow_direction=(!isset($get['money_flow_direction']) )? 1 : mysql_real_escape_string(urldecode($get['money_flow_direction']));

		    $tbl_name='m_money';
		    $content_tbl='m_content';
		    
		    mysql_query("UPDATE `$tbl_name` SET `uid`='".$uid."',`modified_on`='".$timestamp."',`lat`='".$lat."',`long`='".$long."',`visibility`='".$visibility."',`money_title`='".$money_title."'
				    ,`money_amount`='".$money_amount."',`item_unit_price`='".$item_unit_price."',`item_units`='".$item_units."',`item_qty`='".$item_qty."'
				    ,`total_price`='".$total_price."',`money_flow_direction`='".$money_flow_direction."',`file_id`='".$file_id."',`category_id`='".$category_id."'
				    WHERE `money_id`='".$money_id."' AND `uid`='".$uid."'
				",$linkid) or $this->print_error(mysql_error($linkid));
		    
		    if(mysql_errno($linkid)) {
			$this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		    }
		    else {

			//==== content table update=========
			$col_prim_key_name='money_id';
			$col_prim_key_value=$money_id;
			$tbl_path=$tbl_name;
			$sql="UPDATE `$content_tbl` SET lat='".$lat."',`long`='".$long."',col_prim_key_name='".$col_prim_key_name."',tbl_name='".$tbl_name."',col_prim_key_value='".$col_prim_key_value."',tbl_path='".$tbl_path."' WHERE `content_id`='".$content_id."' ";
			mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
			//==== END content table update=========

			$output = array("status"=>"success",'message'=>"Money table has been updated.");
		    }
		/*$title =(!isset($get['title']))? '' : mysql_real_escape_string(urldecode($get['title']));
    //            $desc =(!isset($get['desc']))? '' : mysql_real_escape_string(urldecode($get['desc']));
    //            $amount =(!isset($get['amount']))? '' : mysql_real_escape_string(urldecode($get['amount']));
//		$rslt = mysql_query("select `content_id` from `tbl_expenses` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
//		$row = mysql_fetch_array($rslt);
//		if($row['content_id']=='') { $this->print_error("Content id does not exits in expenses record."); }
		if($modified_text != '') {   if($cond!='') $cond .= ",";     $cond .= "e.title='".$modified_text."'";        }
		if($modified_text != '') {       if($cond!='') $cond .= ",";       $cond .= "e.desc='".$modified_text."'";        }
    //            if($amount != '') {      if($cond!='') $cond .= ",";       $cond .= "e.amount='".$amount."'";        }
		if($lat != '') {     if($cond!='') $cond .= ",";         $cond .= "c.lat=".$lat;        }
		if($long != '') {     if($cond!='') $cond .= ",";        $cond .= "c.long=".$long;        }
		if($timestamp != '') {  if($cond!='') $cond .= ",";  $cond .= "c.timestamp='".$timestamp."'";        }
		$sql = "UPDATE 
		    `tbl_expenses` e
		    JOIN `tbl_content` c on c.content_id=e.content_id
		    SET $cond
		    WHERE c.content_id =$content_id";
		    //echo '<pre>';die($sql);
		    $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
		    if(mysql_errno($linkid)) {
			$this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		    }
		    else {
			$output = array("status"=>"success","result"=>"Content is Updated.");
			$taskname = $this->createTaskQueue($content_id,$module,$uid);
		    }*/
	    }
	    elseif($module == 'reminder') {
		$remind_time =(!isset($get['remind_time'])) ? '' : mysql_real_escape_string(urldecode($get['remind_time']));
    //            $reminder_name =(!isset($get['reminder_name'])) ? '' : mysql_real_escape_string(urldecode($get['reminder_name']));

		$rslt = mysql_query("select `content_id` from `tbl_reminders` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
		$row = mysql_fetch_array($rslt);
		if($row['content_id']=='') { $this->print_error("Content id does not exits in reminders record."); }

		if($remind_time != '') {      $cond .= "r.remind_time='".strtotime($remind_time)."'";        }
		if($modified_text != '') {  if($cond!='') $cond .= ",";  $cond .= "r.reminder_name='".$modified_text."'";  }
		//if($amount != '') {   if($cond!='') $cond .= ",";  $cond .= "r.amount='".$amount."'";        }

		if($lat != '') {     if($cond!='') $cond .= ",";         $cond .= "c.lat=".$lat;        }
		if($long != '') {     if($cond!='') $cond .= ",";        $cond .= "c.long=".$long;        }
		if($timestamp != '') {  if($cond!='') $cond .= ",";  $cond .= "c.timestamp='".$timestamp."'";        }

		$sql = "UPDATE
		    `tbl_reminders` r
		    JOIN `tbl_content` c on c.content_id=r.content_id
		    SET $cond
		    WHERE c.content_id = $content_id";
		//echo '<pre>';die($sql);
		$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));


		if(mysql_errno($linkid)) {
		    $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		}
		else {
		    $output = array("status"=>"success","result"=>"Content is Updated.");
		}

		$taskname = $this->createTaskQueue($content_id,$module,$uid);

	    }
	    elseif($module == 'shoppinglist') {
		//$remind_time =(!isset($get['remind_time'])) ? '' : mysql_real_escape_string(urldecode($get['remind_time']));
	       // $reminder_name =(!isset($get['reminder_name'])) ? '' : mysql_real_escape_string(urldecode($get['reminder_name']));

		$rslt = mysql_query("select `content_id` from `tbl_shoppinglist` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
		$row = mysql_fetch_array($rslt);
		if($row['content_id']=='') { $this->print_error("Content id does not exits in reminders record."); }

		/*$item_name=mysql_real_escape_string(urldecode($get['item_name']));

		$file_id=(!isset($get['file_id']) )? '0' : mysql_real_escape_string(urldecode($get['file_id']));
		$shared_with=(!isset($get['shared_with']) )? '1' : mysql_real_escape_string(urldecode($get['shared_with']));

		$qty=(!isset($get['qty']) )? '' : mysql_real_escape_string(urldecode($get['qty']));*/

		$item_qty=(!isset($get['item_qty']) )? '1' : mysql_real_escape_string(urldecode($get['item_qty']));
		$units=(!isset($get['units']) )? '' : mysql_real_escape_string(urldecode($get['units']));
		$shopping_status=(!isset($get['shopping_status']) )? '1' : mysql_real_escape_string(urldecode($get['shopping_status']));

		if($modified_text != '') {      $cond .= "sl.item_name='".$modified_text."',sl.item_description='".$modified_text."',item_qty='".$item_qty."',units='".$units."',shopping_status='".$shopping_status."' ";        }
		/*if($reminder_name != '') {     
		    if($cond!='') $cond .= ",";
		    $cond .= "r.reminder_name='".$reminder_name."'";
		}
		if($amount != '') {   if($cond!='') $cond .= ",";  $cond .= "r.amount='".$amount."'";        }
		*/
		if($lat != '') {     if($cond!='') $cond .= ",";         $cond .= "c.lat=".$lat;        }
		if($long != '') {     if($cond!='') $cond .= ",";        $cond .= "c.long=".$long;        }
		if($timestamp != '') {  if($cond!='') $cond .= ",";  $cond .= "c.timestamp='".$timestamp."'";        }

		$sql = "UPDATE
		    `tbl_shoppinglist` sl
		    JOIN `tbl_content` c on c.content_id=sl.content_id
		    SET $cond
		    WHERE c.content_id = $content_id";
    //            echo '<pre>';die($sql);
		$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));


		if(mysql_errno($linkid)) {
		    $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		}
		else {
		    $output = array("status"=>"success","result"=>"Content is Updated.");
		}

		$taskname = $this->createTaskQueue($content_id,$module,$uid);

	    }
	    else { $output = $this->unknown(); }
	    return $output;
    }
    
    function modify_group($get)
    {
	$linkid=$this->db_conn();
	
	if(!isset($get['group_id'])) $this->print_error(array("status"=>"error","response"=>"Please enter Group ID."));//group_owner_uid
	if(!isset($get['group_owner_uid'])) $this->print_error(array("status"=>"error","response"=>"Please enter Group Owner UID."));
	if(!isset($get['group_type'])) $this->print_error(array("status"=>"error","response"=>"Please enter group type."));
        if(!isset($get['group_name'])) $this->print_error(array("status"=>"error","response"=>"Please enter group name."));
        if(!isset($get['group_description'])) $this->print_error(array("status"=>"error","response"=>"Please enter group description.")); 
	
	$datetime = date("Y-m-d H:i:s",time());
        $group_id = mysql_real_escape_string(urldecode($get["group_id"]));
        $group_owner_uid = mysql_real_escape_string(urldecode($get["group_owner_uid"]));
        $group_type = mysql_real_escape_string(urldecode($get['group_type']));
        $group_name = mysql_real_escape_string(urldecode($get["group_name"]));
        $group_description = mysql_real_escape_string(urldecode($get["group_description"]));
	
        $group_member_ctr = (!isset($get['group_member_ctr']))? '' : mysql_real_escape_string(urldecode($get["group_member_ctr"]));
        $group_addr_line_1 = (!isset($get['group_addr_line_1']))? '' : mysql_real_escape_string(urldecode($get["group_addr_line_1"]));
        $group_addr_line_2 = (!isset($get['group_addr_line_2']))? '' : mysql_real_escape_string(urldecode($get["group_addr_line_2"]));
        $group_addr_line_3 = (!isset($get['group_addr_line_3']))? '' : mysql_real_escape_string(urldecode($get["group_addr_line_3"]));
        $group_addr_city = (!isset($get['group_addr_city']))? '' : mysql_real_escape_string(urldecode($get["group_addr_city"]));
        $group_addr_state = (!isset($get['group_addr_state']))? '' : mysql_real_escape_string(urldecode($get["group_addr_state"]));
        $group_addr_country = (!isset($get['group_addr_country']))? '' : mysql_real_escape_string(urldecode($get["group_addr_country"]));
        $group_addr_zip = (!isset($get['group_addr_zip']))? '' : mysql_real_escape_string(urldecode($get["group_addr_zip"]));
	
	$rslt = mysql_query("select `uid` from m_profile where `uid`='$group_owner_uid'",$linkid) or $this->print_error(mysql_error($linkid));
        $row = mysql_fetch_array($rslt);
        if($row['uid']=='') { $this->print_error("User/uid does not exits."); }
	
	//====
	$file_id='';
        $sql="UPDATE `m_groups` 
		SET `group_type`='".$group_type."',`group_name`='".$group_name."',`group_description`='".$group_description."',`group_owner_uid`='".$group_owner_uid."',`group_member_ctr`='".$group_member_ctr."',`file_id`='".$file_id."',`group_addr_line_1`='".$group_addr_line_1."'
		    ,`group_addr_line_2`='".$group_addr_line_2."',`group_addr_line_3`='".$group_addr_line_3."',`group_addr_city`='".$group_addr_city."',`group_addr_state`='".$group_addr_state."',`group_addr_country`='".$group_addr_country."',`group_addr_zip`='".$group_addr_zip."',`modified_on`='".$datetime."'
		WHERE `group_id`='".$group_id."'
		";
	mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid)."-".$sql);
        //====
        
	if(mysql_errno($linkid)) {
	    $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
	}
	else {
	    $rslt_arr = array("status"=>"success","response"=>"Group Updated.");

	}
	return $rslt_arr;
    }
    
    function modify_member($get)
    {
	$linkid=$this->db_conn();
	//m_member: member_id,uid,member_name,member_img_file_id,member_email,member_phone,member_role,managed_by_uid_1,managed_by_uid_2,group_id
	
        if(!isset($get['member_id'])) $this->print_error(array("status"=>"error","response"=>"Please enter Member Id."));
        if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Please enter UID."));
        if(!isset($get['group_id'])) $this->print_error(array("status"=>"error","response"=>"Please enter Group ID."));
        if(!isset($get['member_name'])) $this->print_error(array("status"=>"error","response"=>"Please enter member name."));
        if(!isset($get['member_email'])) $this->print_error(array("status"=>"error","response"=>"Please enter member email."));
        if(!isset($get['member_role'])) $this->print_error(array("status"=>"error","response"=>"Please enter member role."));
        if(!isset($get['member_phone'])) $this->print_error(array("status"=>"error","response"=>"Please enter member phone."));

        $uid = mysql_real_escape_string(urldecode($get["uid"]));
	$member_id = mysql_real_escape_string(urldecode($get['member_id']));
	$group_id = mysql_real_escape_string(urldecode($get['group_id']));
        $member_name = mysql_real_escape_string(urldecode($get["member_name"]));
        $member_email = mysql_real_escape_string(urldecode($get["member_email"]));
        $member_phone = mysql_real_escape_string(urldecode($get["member_phone"]));
        $member_role = mysql_real_escape_string(urldecode($get["member_role"]));
	
	$managed_by_uid_1 = (!isset($get['managed_by_uid_1']))? '' : mysql_real_escape_string(urldecode($get["managed_by_uid_1"]));
	$managed_by_uid_2 = (!isset($get['managed_by_uid_2']))? '' : mysql_real_escape_string(urldecode($get["managed_by_uid_2"]));
	$created_on = date("Y-m-d H:i:s",time());

	$member_img_file_id='';
        $sql="UPDATE `m_member` 
			SET `uid`='".$uid."',`member_name`='".$member_name."',`member_img_file_id`='".$member_img_file_id."',`member_email`='".$member_email."',`member_phone`='".$member_phone."'
			    ,`member_role`='".$member_role."',`managed_by_uid_1`='".$managed_by_uid_1."',`managed_by_uid_2`='".$managed_by_uid_2."',`group_id`='".$group_id."',`created_on`='".$created_on."'
			WHERE member_id='".$member_id."' ";
	
	mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

	if(mysql_errno($linkid)) {
	    $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
	}
	else {
	    $rslt_arr = array("status"=>"success","response"=>"Member updated successfully.");
	}
	return $rslt_arr;
    }
    
    /*function createTaskQueue($content_id,$module,$uid) {
        $task = new PushTask('/worker/tagmodifier/', ['content_id' => $content_id, 'module' => $module,"uid"=>$uid]);
        $task_name = $task->add();
        return $task_name;
    }*/
    
    /*function modify_groups_content_info($get)	{
        $linkid=$this->db_conn();
        $cond='';
        $output=array();
        $uid=mysql_real_escape_string(urldecode($get['uid']));
        $module=mysql_real_escape_string(urldecode($get['module']));
        $timestamp=(!isset($get['timestamp']))? '' : strtotime(mysql_real_escape_string(urldecode($get['timestamp'])));//Unix timestamp
        $rslt = mysql_query("select `uid` from generic_profile where `uid`='$uid'",$linkid) or $this->print_error(mysql_error($linkid));
        $row = mysql_fetch_array($rslt);
        if($row['uid']=='') { $this->print_error("User/uid does not exits."); }
      
        if($module == 'update_member') {
            if(!isset($get['membername'])) $this->print_error(array("status"=>"error","response"=>"Please enter member name."));
            if(!isset($get['memberemail'])) $this->print_error(array("status"=>"error","response"=>"Please enter member email."));
            if(!isset($get['memberrole'])) $this->print_error(array("status"=>"error","response"=>"Please enter member role."));
            if(!isset($get['memberphone'])) $this->print_error(array("status"=>"error","response"=>"Please enter member phone."));
            $uid = mysql_real_escape_string(urldecode($get["uid"]));
            $membername = mysql_real_escape_string(urldecode($get["membername"]));
            $memberemail = mysql_real_escape_string(urldecode($get["memberemail"]));
            $memberphone = mysql_real_escape_string(urldecode($get["memberphone"]));
            $memberrole = mysql_real_escape_string(urldecode($get["memberrole"]));

            $permission_group = mysql_real_escape_string(urldecode($get["permission_group"]));
            $permission_branch = mysql_real_escape_string(urldecode($get["permission_branch"]));
            $permission_money = mysql_real_escape_string(urldecode($get["permission_money"]));
            $permission_content = mysql_real_escape_string(urldecode($get["permission_content"]));
            $permission_team = mysql_real_escape_string(urldecode($get["permission_team"]));

            $member_id = mysql_real_escape_string(urldecode($get['member_id']));
            $group_id = mysql_real_escape_string(urldecode($get['group_id']));
            $branch_id = mysql_real_escape_string(urldecode($get["branch_id"]));
            
            $managed_by_uid_1='';
            $managed_by_uid_2='';
            $member_img_file_id = '';
            $sql="update `tbl_members` set member_name='".$membername."',member_img_file_id='".$member_img_file_id."',`member_email`='".$memberemail."',`member_phone`='".$memberphone."',`member_role`='".$memberrole."'
                ,`managed_by_uid_1`='".$managed_by_uid_1."',`managed_by_uid_2`='".$managed_by_uid_2."'
                ,`permission_team`='".$permission_team."',`approval_team_req`='',`permission_content`='".$permission_content."',`approval_content_req`=''
                ,`permission_branch`='".$permission_branch."',`approval_branch_req`=''
                ,`permission_group`='".$permission_group."',`approval_group_req`='',`permission_money`='".$permission_money."',`approval_money_req`=''
                where  member_id='".$member_id."' and `group_id`='".$group_id."' "; //uid='".$uid."' and,`branch_id`,
            
            mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
            
            if(mysql_errno($linkid)) {
                $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
            }
            else {
                $rslt_arr = array("status"=>"success","response"=>"Member updated successfully.");
            }
        }
        elseif($module == 'update_branch') {
            
            if(!isset($get['group_id'])) $this->print_error(array("status"=>"error","response"=>"Please group id is missing."));
            if(!isset($get['branch_id'])) $this->print_error(array("status"=>"error","response"=>"Please branch id is missing."));
            if(!isset($get['branchname'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch Name."));
            if(!isset($get['branchdescr'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch Description."));
            if(!isset($get['branch_addr_line_1'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch Addressline1."));
            //if(!isset($get['branch_addr_line_2'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch Addressline2."));
            //if(!isset($get['branch_addr_line_3'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch Addressline3."));
//            if(!isset($get['branch_addr_city'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch City."));
//            if(!isset($get['branch_addr_state'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch State."));
//            if(!isset($get['branch_addr_country'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch Country."));
//            if(!isset($get['branch_addr_zip'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch Zip."));

            $branch_owner_uid = mysql_real_escape_string(urldecode($get["uid"]));
            $branch_id = mysql_real_escape_string(urldecode($get["branch_id"]));
            $group_id = mysql_real_escape_string(urldecode($get["group_id"]));
            $branchname = mysql_real_escape_string(urldecode($get["branchname"]));
            $branchdescr = mysql_real_escape_string(urldecode($get["branchdescr"]));
            $branch_addr_line_1 = mysql_real_escape_string(urldecode($get["branch_addr_line_1"]));
            $branch_addr_line_2 = mysql_real_escape_string(urldecode($get["branch_addr_line_2"]));
            $branch_addr_line_3 = mysql_real_escape_string(urldecode($get["branch_addr_line_3"]));
            $branch_addr_city = mysql_real_escape_string(urldecode($get["branch_addr_city"]));
            $branch_addr_state = mysql_real_escape_string(urldecode($get["branch_addr_state"]));
            $branch_addr_country = mysql_real_escape_string(urldecode($get["branch_addr_country"]));
            $branch_addr_zip = mysql_real_escape_string(urldecode($get["branch_addr_zip"]));
            $branch_billing_currency = mysql_real_escape_string(urldecode($get["branch_billing_currency"]));
            
            $sql = "update `tbl_branch` set `branch_name`='".$branchname."',`branch_desc`='".$branchdescr."',`branch_addr_line_1`='".$branch_addr_line_1."',`branch_addr_line_2`='".$branch_addr_line_2."'
                ,`branch_addr_line_3`='".$branch_addr_line_3."',`branch_addr_city`='".$branch_addr_city."',`branch_addr_state`='".$branch_addr_state."'
                ,`branch_addr_country`='".$branch_addr_country."',`branch_addr_zip`='".$branch_addr_zip."',`branch_billing_currency`='".$branch_billing_currency."'
                where `group_id`='".$group_id."' and `branch_id`='".$branch_id."' and `branch_owner_uid`='".$branch_owner_uid."' ";
            
            mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
           
            if(mysql_errno($linkid)) {
                $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
            }
            else {
                $rslt_arr = array("status"=>"success","response"=>"Branch updated sucessfully");
            }
        }
        elseif($module == 'update_group')
        {
            $linkid=$this->db_conn();
            if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Please enter UID."));
            if(!isset($get['group_id'])) $this->print_error(array("status"=>"error","response"=>"Please enter group_id."));
            if(!isset($get['grouptype'])) $this->print_error(array("status"=>"error","response"=>"Please enter group type."));
            if(!isset($get['groupname'])) $this->print_error(array("status"=>"error","response"=>"Please enter group name."));
            if(!isset($get['group_description'])) $this->print_error(array("status"=>"error","response"=>"Please enter group description."));
            if(!isset($get['common_prod_flag'])) $this->print_error(array("status"=>"error","response"=>"Common product flag is missing."));
            $created_on = time();
            $group_id = mysql_real_escape_string(urldecode($get['group_id']));
            $grouptype = mysql_real_escape_string(urldecode($get['grouptype']));
            $groupname = mysql_real_escape_string(urldecode($get["groupname"]));
            $group_description = mysql_real_escape_string(urldecode($get["group_description"]));
            $group_owner_uid = mysql_real_escape_string(urldecode($get["uid"]));
            $common_prod_flag = mysql_real_escape_string(urldecode($get["common_prod_flag"]));
            
            $sql="update `tbl_groups` set `group_type`='".$grouptype."',`group_name`='".$groupname."',`group_description`='".$group_description."'
                            ,`common_prod_flag` = '".$common_prod_flag."'
                            where `group_owner_uid` = '".$group_owner_uid."' and group_id='".$group_id."' ";
            
            mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
            if(mysql_errno($linkid)) {
                $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
            }
            else {
                $rslt_arr = array("status"=>"success","response"=>"Group Updated.");
            }
        }
        return $rslt_arr;
    }*/
}

$ob = new Modify();
if(!isset($get['content_style']))
{
    $output = $ob->unknown();
}
else
{
    switch($get['content_style']) {
	case 'single_content':
			if(!isset($get['module'])) $ob->print_error(array("status"=>"error","response"=>"Undefined module."));
//			if(!isset($get['uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
//			if(!isset($get['content_id'])) $ob->print_error(array("status"=>"error","response"=>"Undefined content id."));
//                        if(!isset($get['field_name'])) $ob->print_error(array("status"=>"error","response"=>"Undefined required field name."));
//                        if(!isset($get['field_value'])) $ob->print_error(array("status"=>"error","response"=>"Undefined field value."));
			$output = $ob->modify_single_content_info($get);
	    break;
//	case 'groups_content':
//			if(!isset($get['uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
//			if(!isset($get['module'])) $ob->print_error(array("status"=>"error","response"=>"Undefined module."));
//			$output = $ob->modify_groups_content_info($get);
//	    break;
	default : $output = $ob->unknown();
	    break;
    }
}
//echo json_encode($output);
?>