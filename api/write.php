<?php
/**
 * @author Shivaraj <mrshivaraj123@gmail.com.com>
 * @uses Complete Write API
 */

require_once 'google/appengine/api/taskqueue/PushTask.php';
use \google\appengine\api\taskqueue\PushTask;

//$output= '';
//include "blocks/paths.php";
//include $myclass_url;

class Write extends Baseclass
{
    /**
     * Write Class
     * @author Shivaraj<mrshivaraj123@gmail.com>_Oct_07_2014
     */
    function __construct() {
        parent::__construct();
    }
    
    /**
     * Function to create session store
     * @author Shivaraj<mrshivaraj123@gmail.com>_Oct_07_2014
     * @param type $get
     * @return type
     */
    function create_session($get)
    {
	$linkid = $this->db_conn();
	$lat=(!isset($get['lat']))? '' : mysql_real_escape_string(urldecode($get['lat']));
	$long=(!isset($get['long']))? '' : mysql_real_escape_string(urldecode($get['long']));
	$uid=mysql_real_escape_string($get['uid']);
	$datetime = date("Y-m-d H:i:s",time());
	$session_src=$get['module'];
	//===============
	/*$sql="SELECT * FROM m_sessions WHERE uid='".$uid."' and session_die_dt is null";
	$ses_det_res=mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
	$aff_row=mysql_affected_rows($linkid);
	if($aff_row > 0) {
	    $ses_det=mysql_fetch_row($ses_det_res);
	    $old_session_id=$ses_det['session_id'];
	    mysql_query("UPDATE m_sessions SET session_die_dt='$datetime' WHERE uid=$uid");
	}else	{   }*/
	
	mysql_query("INSERT INTO m_sessions (`uid`,`session_start_dt`,`session_src`,`lat`,`long`) VALUES('".$uid."','".$datetime."','".$session_src."','".$lat."','".$long."')",$linkid);
	$insert_id=mysql_insert_id($linkid);
	$session_id=$insert_id;
	mysql_query("UPDATE m_sessions SET session_id='$session_id' WHERE sno=$insert_id");
	
	return $session_id;
    }
    
    /**
     * API - Write the module single content
     * @author Shivaraj<mrshivaraj123@gmail.com>_Sep_25_2014
     * @param Mixed $get
     * @return JSON
     */
    function put_single_content_info($get) {
        $linkid = $this->db_conn();
	$output=array();
        //$uid=mysql_real_escape_string(urldecode($get['uid']));
        $module=mysql_real_escape_string(urldecode($get['module']));
	
	if($module=='profile')
	{
                    $output = $this->create_profile($get);
			//session
			$get['uid']=$output['uid'];
//			$session_id=$this->create_session($get);
//		    $output['session_id']=$session_id;
	}
	elseif($module=='social-contact')
	{
		$output = $this->create_social_contact($get);
	}
	elseif($module=='group')
	{
		$output=$this->create_group($get);
	}
	elseif($module=='member')
	{
		$output=$this->create_member($get);
	}
	else
	{
	    
	    if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Undefined uid."));
	    if(!isset($get['timestamp'])) $this->print_error(array("status"=>"error","response"=>"Undefined timestamp."));
	    
	    $uid=mysql_real_escape_string(($get['uid']));
	    
	    $timestamp=(!isset($get['timestamp']))? date('Y-m-d H:i:s',time()) : mysql_real_escape_string(urldecode($get['timestamp']));//Unix timestamp -strtotime()
	    $lat=(!isset($get['lat']))? '' : mysql_real_escape_string(urldecode($get['lat']));
	    $long=(!isset($get['long']))? '' : mysql_real_escape_string(urldecode($get['long']));
	    $visibility=(!isset($get['visibility']))? 'pri' : mysql_real_escape_string(urldecode($get['visibility']));
	    
	    $file_id = (!isset($get['file_id']))? 0:mysql_real_escape_string(($get['file_id']));
	    $category_id=(!isset($get['category_id']))? 0:mysql_real_escape_string(($get['category_id']));
	    
	    $group_id=(!isset($get['group_id']))? 0:mysql_real_escape_string(($get['group_id']));
	    
	    $content_tbl='m_content';
	    
	    //=============
	    $rslt = mysql_query("select `uid` from `m_profile` where `uid`='".$uid."'",$linkid) or $this->print_error(mysql_error($linkid));
	    $row = mysql_fetch_array($rslt);
	    if(mysql_num_rows($rslt) == 0) { $this->print_error("User/uid does not exits.".mysql_error()."".$uid); }
	    //=============
	    
	    //=============
	    $sql="INSERT INTO `$content_tbl`(`sno`,`content_id`,`uid`,`timestamp`,`lat`,`long`,`module`,`visibility`) 
				values ( NULL,NULL,'".$uid."','".$timestamp."','".$lat."','".$long."','".$module."','".$visibility."')";
//	    echo $sql;die();
	    mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
	    $slno = $content_id = mysql_insert_id(); //"cnt".rand(8,getrandmax());
	    
	    mysql_query("update `$content_tbl` set `content_id`='$content_id' where `sno`='$slno'") or $this->print_error(mysql_error($linkid));
	    //=============
	    
	    if($module == 'note') {
		    if(!isset($get['note_text'])) $this->print_error(array("status"=>"error","response"=>"Undefined note text."));
		    if(!isset($get['module'])) $this->print_error(array("status"=>"error","response"=>"Undefined content type."));

		    //,`timestamp`,`lat`,`long`,`shared_with`

		    $shared_with=(!isset($get['shared_with']) ) ? '' : mysql_real_escape_string(urldecode($get['shared_with']));

		    $note_text=  mysql_escape_string(urldecode($get['note_text']));
		    mysql_query("insert into `tbl_notes`(`sno`,`note_id`,`content_id`,`uid`,`note_text`,`visibility`,`file_id`,`timestamp`,`lat`,`long`,`shared_with`) 
					values ( NULL,NULL,'".$content_id."','".$uid."','".$note_text."','".$visibility."','".$file_id."','".$timestamp."','".$lat."','".$long."','".$shared_with."');",$linkid) or $this->print_error(mysql_error($linkid));
		    $insert_id = mysql_insert_id();
		    mysql_query("update `tbl_notes` set `note_id`='".$insert_id."' where `sno`=$insert_id") or $this->print_error(mysql_error($linkid));

		    if(mysql_errno($linkid)) {
			$this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		    }
		    else {
			$rslt_arr = array("status"=>"success","content_id"=>$content_id);

			$taskname = $this->createTaskQueue($content_id,$module,$uid,$visibility);
		    }
	    }
	    elseif($module == 'money')
	    {
		    if(!isset($get['money_title'])) $this->print_error(array("status"=>"error","response"=>"Please specify money title."));
		    if(!isset($get['money_amount'])) $this->print_error(array("status"=>"error","response"=>"Please specify amount."));
		    if(!isset($get['item_unit_price'])) $this->print_error(array("status"=>"error","response"=>"Please specify item price."));
		    if(!isset($get['item_units'])) $this->print_error(array("status"=>"error","response"=>"Please specify item units."));
		    if(!isset($get['item_qty'])) $this->print_error(array("status"=>"error","response"=>"Please specify item quantity."));
		    if(!isset($get['total_price'])) $this->print_error(array("status"=>"error","response"=>"Please specify item quantity."));
		    if(!isset($get['money_flow_direction'])) $this->print_error(array("status"=>"error","response"=>"Please specify money flow direction."));

		    $money_title=mysql_real_escape_string(urldecode($get['money_title']));
		    $money_amount=mysql_real_escape_string(urldecode($get['money_amount']));
		    $item_unit_price=(!isset($get['item_unit_price']) )? '' : mysql_real_escape_string(urldecode($get['item_unit_price']));
		    $item_units=(!isset($get['item_units']) )? '' : mysql_real_escape_string(urldecode($get['item_units']));
		    $item_qty=(!isset($get['item_qty']) )? '' : mysql_real_escape_string(urldecode($get['item_qty']));
		    $total_price=(!isset($get['total_price']) )? '' : mysql_real_escape_string(urldecode($get['total_price']));
		    $money_flow_direction=(!isset($get['money_flow_direction']) )? 1 : mysql_real_escape_string(urldecode($get['money_flow_direction']));

		    $tbl_name='m_money';
		    
//,`content_id`,'".$content_id."'
		    mysql_query("insert into `$tbl_name`(`sno`,`money_id`,`uid`,`timestamp`,`lat`,`long`,`visibility`,`money_title`,`money_amount`,`item_unit_price`,`item_units`,`item_qty`,`total_price`,`money_flow_direction`,`file_id`,`category_id`,`group_id`)
					values ( NULL,NULL,'".$uid."','".$timestamp."','".$lat."','".$long."','".$visibility."','".$money_title."','".$money_amount."','".$item_unit_price."','".$item_units."','".$item_qty."','".$total_price."','".$money_flow_direction."','".$file_id."','".$category_id."','".$group_id."');",$linkid) or $this->print_error(mysql_error($linkid));
		    $insert_id = $money_id=mysql_insert_id();
		    mysql_query("update `$tbl_name` set `money_id`='".$insert_id."' where `sno`=$insert_id") or $this->print_error(mysql_error($linkid));

		    if(mysql_errno($linkid)) {
			$this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		    }
		    else {
			
			//==== content table update=========
			$col_prim_key_name='money_id';
			
			$col_prim_key_value=$money_id;
			$tbl_path=$tbl_name;
			mysql_query("UPDATE `$content_tbl` SET col_prim_key_name='".$col_prim_key_name."',tbl_name='".$tbl_name."',col_prim_key_value='".$col_prim_key_value."',tbl_path='".$tbl_path."' WHERE `content_id`='".$content_id."' ") or $this->print_error(mysql_error($linkid));
			//==== END content table update=========
			
			//$taskname = $this->createTaskQueue($content_id,$module,$uid,$visibility);
			
			$rslt_arr = array("status"=>"success","money_id"=>$money_id);
		    }
	    }
	    elseif($module == 'reminder') {//todo
		    if(!isset($get['remind_time'])) $this->print_error(array("status"=>"error","response"=>"Please specify remind time."));
		    if(!isset($get['reminder_name'])) $this->print_error(array("status"=>"error","response"=>"Please specify reminder name."));

		    $remind_time=strtotime(mysql_real_escape_string(urldecode($get['remind_time'])));
		    $reminder_name=mysql_real_escape_string(urldecode($get['reminder_name']));

		    $shared_with=(!isset($get['shared_with']) )? '' : mysql_real_escape_string(urldecode($get['shared_with']));
		    $todo_status=(!isset($get['todo_status']) )? '1' : mysql_real_escape_string(urldecode($get['todo_status']));

		    //
		    mysql_query("insert into `tbl_reminders`(`sno`,`reminder_id`,`content_id`,`uid`,`remind_time`,`reminder_name`,`visibility`,`timestamp`,`lat`,`long`,`shared_with`,`todo_status`)
						 values ( NULL,NULL,'".$content_id."','".$uid."',".$remind_time.",'".$reminder_name."','".$visibility."','".$timestamp."','".$lat."','".$long."','".$shared_with."','".$todo_status."')",$linkid) or $this->print_error(mysql_error($linkid));
		    $insert_id = mysql_insert_id();
		    mysql_query("update `tbl_reminders` set `reminder_id`='".$insert_id."' where `sno`=$insert_id") or $this->print_error(mysql_error($linkid));;

		    if(mysql_errno($linkid)) {
			$this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		    }
		    else {
			$rslt_arr = array("status"=>"success","content_id"=>$content_id);

			$taskname = $this->createTaskQueue($content_id,$module,$uid,$visibility);
		    }
	    }
	    elseif($module == 'shoppinglist') {
		    if(!isset($get['item_name'])) $this->print_error(array("status"=>"error","response"=>"Please enter item name."));

		    $item_name=mysql_real_escape_string(urldecode($get['item_name']));
		    $item_qty=(!isset($get['item_qty']) )? '1' : mysql_real_escape_string(urldecode($get['item_qty']));
		    $file_id=(!isset($get['file_id']) )? '0' : mysql_real_escape_string(urldecode($get['file_id']));
		    $shared_with=(!isset($get['shared_with']) )? '1' : mysql_real_escape_string(urldecode($get['shared_with']));
		    //$qty=(!isset($get['quantity']) )? '' : mysql_real_escape_string(urldecode($get['quantity']));
		    $units=(!isset($get['units']) )? '' : mysql_real_escape_string(urldecode($get['units']));
		    $shopping_status=(!isset($get['shopping_status']) )? '1' : mysql_real_escape_string(urldecode($get['shopping_status']));

		    $item_name=mysql_real_escape_string(urldecode($get['item_name']));
		    //insert into `tbl_shoppinglist`(`sno`,`shop_list_item_id`,`content_id`,`item_name`,`item_qty`,`item_description`,`uid`,`timestamp`,`lat`,`long`,`file_id`,`shared_with`) values ( NULL,NULL,NULL,'buy a mobile','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL)
		    mysql_query("insert into `tbl_shoppinglist`(`content_id`,`item_name`,`item_qty`,`item_description`,`uid`,`timestamp`,`lat`,`long`,`file_id`,`shared_with`,`shopping_status`,`units`,`qty`,`visibility`)
						 values ( '".$content_id."','".$item_name."','".$item_qty."','".$item_name."','".$uid."','".$timestamp."','".$lat."','".$long."','".$file_id."','".$shared_with."','".$shopping_status."','".$units."','".$qty."','".$visibility."')",$linkid) or $this->print_error(mysql_error($linkid));
		    $insert_id = mysql_insert_id();
		    mysql_query("update `tbl_shoppinglist` set `shop_list_item_id`='".$insert_id."' where `sno`=$insert_id") or $this->print_error(mysql_error($linkid));;

		    if(mysql_errno($linkid)) {
			$this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		    }
		    else {
			$rslt_arr = array("status"=>"success","content_id"=>$content_id);

			$taskname = $this->createTaskQueue($content_id,$module,$uid,$visibility);
		    }
	    }
	    else { $output = $this->unknown(); }
	    $output=$rslt_arr;
	}

        return $output;
    }
    
    /**
     * Function to create user profile
     * @author Shivaraj<mrshivaraj123@gmail.com>_Oct_07_2014
     * @param type $get
     * @return string
     */
    function create_profile($get) {
        $linkid=$this->db_conn();
        if(!isset($get['gid'])) $this->print_error(array("status"=>"error","response"=>"Undefined gid."));
	if(!isset($get['name'])) $this->print_error(array("status"=>"error","response"=>"Undefined name."));
	if(!isset($get['email'])) $this->print_error(array("status"=>"error","response"=>"Undefined email."));
		    
        $module=mysql_real_escape_string(urldecode($get['module']));//req
//        $uid=mysql_real_escape_string(urldecode($get['uid'])); 
        $gid=mysql_real_escape_string(urldecode($get['gid'])); //req
        $name=mysql_real_escape_string(urldecode($get['name'])); //req
        $email=mysql_real_escape_string(urldecode($get['email'])); //req
	$datetime = date("Y-m-d H:i:s",time());
	
        $fname=(!isset($get['fname']))? '' : mysql_real_escape_string(urldecode($get['fname']));
        $mname=(!isset($get['mname']))? '' : mysql_real_escape_string(urldecode($get['mname']));
        $lname=(!isset($get['lname']))? '' : mysql_real_escape_string(urldecode($get['lname']));
        $uname=(!isset($get['uname']))? '' : mysql_real_escape_string(urldecode($get['uname']));
        $phone=(!isset($get['phone']))? '' : mysql_real_escape_string(urldecode($get['phone']));
       
        $timezone=  (!isset($get['timezone']))? '' : strtotime(mysql_real_escape_string(urldecode($get['timezone']))); //Unix timestamp
        $image_url=  (!isset($get['image_url']))? '' : mysql_real_escape_string(urldecode($get['image_url'])); //google+ image url
	$uid=$gid;
	
	$profile_res=mysql_query("SELECT * FROM m_profile WHERE gid='".$gid."' LIMIT 1",$linkid) or $this->print_error(mysql_error($linkid));
	if(mysql_affected_rows($linkid)>0)
	{
		$profile_det=mysql_fetch_assoc($profile_res);
		$rowid=$profile_det['sno'];
		$sql="UPDATE m_profile SET
				    `fname`='".$fname."',`mname`='".$mname."',`lname`='".$lname."',`name`='".$name."',`uname`='".$uname."'
				    ,`email`='".$email."',`phone`='".$phone."',`timezone`='".$timezone."',`info_update_timestamp`='".$datetime."',`image_url`='".$image_url."'
			    WHERE sno='".$rowid."' ";
		mysql_query($sql) or $this->print_error(mysql_error($linkid));//`uid`='".$uid."',`gid`='".$gid."',
			
		if(mysql_errno($linkid)) {
		    $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		}
		else { 

		    $rslt_arr = array("status"=>"success",'uid'=>$uid);
		}
		
	}
	else {
	    
		$sql="insert into `m_profile`(`uid`,`gid`,`fname`,`mname`,`lname`,`name`,`uname`,`email`,`phone`,`timezone`,`info_update_timestamp`,`image_url`) 
		    values( '".$uid."','".$gid."','".$fname."','".$mname."','".$lname."','".$name."','".$uname."','".$email."','".$phone."','".$timezone."','".$datetime."','".$image_url."')";
//		die('<pre>'.$sql.'</pre>');
		mysql_query($sql,$linkid);
		if(mysql_errno($linkid)) {
		    $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		}
		else { 

		    $rslt_arr = array("status"=>"success",'uid'=>$uid);
		}
	}
        return $rslt_arr;
    }
    
    /**
     * Function to store social contact of profile user
     * @author Shivaraj<mrshivaraj123@gmail.com>_Oct_07_2014
     * @param type $get
     */
    function create_social_contact($get)
    {
	$linkid=$this->db_conn();
	if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Undefined uid."));
	if(!isset($get['gid'])) $this->print_error(array("status"=>"error","response"=>"Undefined gid."));
	if(!isset($get['following_uid'])) $this->print_error(array("status"=>"error","response"=>"Undefined following_uid."));
	if(!isset($get['following_gid'])) $this->print_error(array("status"=>"error","response"=>"Undefined following_gid."));
//	if(!isset($get['following_uname'])) $this->print_error(array("status"=>"error","response"=>"Undefined following_uname."));
//	if(!isset($get['following_name'])) $this->print_error(array("status"=>"error","response"=>"Undefined following_name."));
	

        $uid=mysql_real_escape_string(urldecode($get['uid']));  //req
        $gid=mysql_real_escape_string(urldecode($get['gid'])); //req
        $following_uid=mysql_real_escape_string(urldecode($get['following_uid'])); //req
        $following_gid=mysql_real_escape_string(urldecode($get['following_gid'])); //req
	$datetime = date("Y-m-d H:i:s",time());
	
        $following_uname=(!isset($get['following_uname']))? '' : mysql_real_escape_string(urldecode($get['following_uname']));
        $following_name=(!isset($get['following_name']))? '' : mysql_real_escape_string(urldecode($get['following_name']));

	$profile_res=mysql_query("SELECT * FROM m_social_contacts WHERE uid='".$uid."' AND gid='".$gid."' AND following_uid='".$following_uid."' AND following_gid='".$following_gid."' LIMIT 1",$linkid) or $this->print_error(mysql_error($linkid));
	if(mysql_affected_rows($linkid)>0)
	{
		$profile_det=mysql_fetch_assoc($profile_res);
		$rowid=$profile_det['sno'];
		$sql="UPDATE m_social_contacts SET
				    `following_uname`='".$following_uname."',`following_name`='".$following_name."'
			    WHERE uid='".$uid."' AND gid='".$gid."' AND following_uid='".$following_uid."' AND following_gid='".$following_gid."' ";
		mysql_query($sql) or $this->print_error(mysql_error($linkid));//`uid`='".$uid."',`gid`='".$gid."',
			
		if(mysql_errno($linkid)) {
		    $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		}
		else { 

		    $rslt_arr = array("status"=>"success",'following_uid'=>$following_uid,'message'=>"Contact Updated");
		}
		
	}
	else {
	    
		$sql="INSERT INTO m_social_contacts (uid,gid,following_uid,following_gid,following_uname,following_name)
				VALUES('".$uid."','".$gid."','".$following_uid."','".$following_gid."','".$following_uname."','".$following_name."')";
		
	//	die('<pre>'.$sql.'</pre>');
		mysql_query($sql,$linkid);
		if(mysql_errno($linkid)) {
		    $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		}
		else { 

		    $rslt_arr = array("status"=>"success",'following_uid'=>$following_uid,'message'=>"Contact Created");
		}
	}
        return $rslt_arr;
    }

    /**
     * Create Group
     * @author Shivaraj<mrshivaraj123@gmail.com>_Oct_07_2014
     * @param type $get array
     * @return type array
     */
    function create_group($get) {
        $linkid=$this->db_conn();
        
	if(!isset($get['group_owner_uid'])) $this->print_error(array("status"=>"error","response"=>"Please enter Group Owner UID."));//group_owner_uid
	if(!isset($get['group_type'])) $this->print_error(array("status"=>"error","response"=>"Please enter group type."));
        if(!isset($get['group_name'])) $this->print_error(array("status"=>"error","response"=>"Please enter group name."));
        if(!isset($get['group_description'])) $this->print_error(array("status"=>"error","response"=>"Please enter group description.")); 
	
	
	$datetime = date("Y-m-d H:i:s",time());
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
        
	
	// m_groups: group_id,group_type,group_name,group_description,group_owner_uid,group_member_ctr,file_id,group_addr_line_1,group_addr_line_2,group_addr_line_3
	// ,group_addr_city,group_addr_state,group_addr_country,group_addr_zip,entity_id
	
	//echo $uq="select `uid` from m_profile where `uid`='$group_owner_uid'";
	$rslt = mysql_query($uq,$linkid) or $this->print_error(mysql_error($linkid));
        $row = mysql_fetch_array($rslt);
        if($row['uid']=='') { $this->print_error("User / uid does not exits."); }
	
	$file_id='';
        $sql="insert into `m_groups`(`group_type`,`group_name`,`group_description`,`group_owner_uid`,`group_member_ctr`,`file_id`,`group_addr_line_1`,`group_addr_line_2`,`group_addr_line_3`,`group_addr_city`,`group_addr_state`,`group_addr_country`,`group_addr_zip`,`created_on`) values
                            ( '".$group_type."','".$group_name."','".$group_description."','".$group_owner_uid."','".$group_member_ctr."','".$file_id."','".$group_addr_line_1."','".$group_addr_line_2."','".$group_addr_line_3."','".$group_addr_city."','".$group_addr_state."','".$group_addr_country."','".$group_addr_zip."','".$datetime."')";
	
	mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid)."-".$sql);
        $group_id=$grp_insert_id = mysql_insert_id();
        mysql_query("update `m_groups` set `group_id`='".$grp_insert_id."' where `sno`=$grp_insert_id") or $this->print_error(mysql_error($linkid));
	
	if(mysql_errno($linkid)) {
            $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
        }
        else {

	    // m_entities: entity_id,eid_fkey,entity_name,entity_type,entity_namespace,stream_id

	    //=====================================
            //Insert into entiry table
            mysql_query("insert into `m_entities`(`eid_fkey`,`entity_name`,`entity_type`) values ('".$group_id."','".$group_name."','".$module."')",$linkid) or $this->print_error(mysql_error($linkid));
            $entity_id = mysql_insert_id();
            mysql_query("update `m_entities` set `entity_id`='".$entity_id."' where `sno`=$entity_id") or $this->print_error(mysql_error($linkid));
            //=====================================
            //update to group table with entity id
            mysql_query("update `m_groups` set `entity_id`='".$entity_id."' where `group_id`=$group_id") or $this->print_error(mysql_error($linkid));
            
            $rslt_arr = array("status"=>"success","response"=>"Group created.","id"=>$grp_insert_id);

	}
        return $rslt_arr;
    }
    
    /**
     * Create member
     * @author Shivaraj<mrshivaraj123@gmail.com>_Oct_07_2014
     * @param type $get array
     * @return string array
     */
    function create_member($get)
    {
        $linkid=$this->db_conn();
	//m_member: member_id,uid,member_name,member_img_file_id,member_email,member_phone,member_role,managed_by_uid_1,managed_by_uid_2,group_id
	
        if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Please enter UID."));
        if(!isset($get['group_id'])) $this->print_error(array("status"=>"error","response"=>"Please enter Group ID."));
        if(!isset($get['member_name'])) $this->print_error(array("status"=>"error","response"=>"Please enter member name."));
        if(!isset($get['member_email'])) $this->print_error(array("status"=>"error","response"=>"Please enter member email."));
        if(!isset($get['member_role'])) $this->print_error(array("status"=>"error","response"=>"Please enter member role."));
        if(!isset($get['member_phone'])) $this->print_error(array("status"=>"error","response"=>"Please enter member phone."));

        $uid = mysql_real_escape_string(urldecode($get["uid"]));
	$group_id = mysql_real_escape_string(urldecode($get['group_id']));
        $member_name = mysql_real_escape_string(urldecode($get["member_name"]));
        $member_email = mysql_real_escape_string(urldecode($get["member_email"]));
        $member_phone = mysql_real_escape_string(urldecode($get["member_phone"]));
        $member_role = mysql_real_escape_string(urldecode($get["member_role"]));
	
	$managed_by_uid_1 = (!isset($get['managed_by_uid_1']))? '' : mysql_real_escape_string(urldecode($get["managed_by_uid_1"]));
	$managed_by_uid_2 = (!isset($get['managed_by_uid_2']))? '' : mysql_real_escape_string(urldecode($get["managed_by_uid_2"]));
	
	$created_on = date("Y-m-d H:i:s",time());
	$member_img_file_id='';
	
	
	$rslt = mysql_query("select `uid` from m_profile where `uid`='$uid'",$linkid) or $this->print_error(mysql_error($linkid));
        $row = mysql_fetch_array($rslt);
        if($row['uid']=='') { $this->print_error("User/uid does not exits."); }
	
        mysql_query("insert into `m_member`(`uid`,`member_name`,`member_img_file_id`,`member_email`,`member_phone`,`member_role`,`managed_by_uid_1`,`managed_by_uid_2`
		,`group_id`,`created_on`) values
            ( '".$uid."','".$member_name."','".$member_img_file_id."','".$member_email."','".$member_phone."','".$member_role."','".$managed_by_uid_1."','".$managed_by_uid_2."'
                ,'".$group_id."','".$created_on."')",$linkid) or $this->print_error(mysql_error($linkid));

        $member_id=$insert_id = mysql_insert_id();
        mysql_query("update `m_member` set `member_id`='".$member_id."' where `sno`=$insert_id") or $this->print_error(mysql_error($linkid));
	
	//================ update permission table =======================
	$permission_profile = (!($get['permission_profile']))? 0 : mysql_real_escape_string(urldecode($get["permission_profile"]));
	$permission_notes = (!($get['permission_notes']))? 0 : mysql_real_escape_string(urldecode($get["permission_notes"]));
	$permission_money = (!($get['permission_money']))? 0 : mysql_real_escape_string(urldecode($get["permission_money"]));
	$permission_todo = (!($get['permission_todo']))? 0 : mysql_real_escape_string(urldecode($get["permission_todo"]));
	$permission_shopping = (!($get['permission_shopping']))? 0 : mysql_real_escape_string(urldecode($get["permission_shopping"]));
	$permission_contacts = (!($get['permission_contacts']))? 0 : mysql_real_escape_string(urldecode($get["permission_contacts"]));
	$permission_recipes = (!($get['permission_recipes']))? 0 : mysql_real_escape_string(urldecode($get["permission_recipes"]));
	$permission_groups = (!($get['permission_groups']))? 0 : mysql_real_escape_string(urldecode($get["permission_groups"]));
	$permission_members = (!($get['permission_members']))? 0 : mysql_real_escape_string(urldecode($get["permission_members"]));
	$permission_services = (!($get['permission_services']))? 0 : mysql_real_escape_string(urldecode($get["permission_services"]));
	$permission_products = (!($get['permission_products']))? 0 : mysql_real_escape_string(urldecode($get["permission_products"]));
	$permission_store = (!($get['permission_store']))? 0 : mysql_real_escape_string(urldecode($get["permission_store"]));
	$permission_orders = (!($get['permission_orders']))? 0 : mysql_real_escape_string(urldecode($get["permission_orders"]));
	
	$sql="INSERT INTO `m_permissions` ( `member_id`, `uid`, `group_id`
		    ,`permission_profile`,`permission_notes`,`permission_money`,`permission_todo`,`permission_shopping`,`permission_contacts`,`permission_recipes`,`permission_groups`,`permission_members`
		    ,`permission_services`,`permission_products`,`permission_store`,`permission_orders`
		    ) VALUES 
		    ( '".$member_id."','".$uid."','".$group_id."'
			,'".$permission_profile."','".$permission_notes."','".$permission_money."','".$permission_todo."','".$permission_shopping."','".$permission_contacts."','".$permission_recipes."'
			,'".$permission_groups."','".$permission_members."','".$permission_services."','".$permission_products."','".$permission_store."','".$permission_orders."'
		    )";
        mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

        $insert_id = mysql_insert_id();
        mysql_query("update `m_permissions` set `permission_id`='".$insert_id."' where `sno`=$insert_id") or $this->print_error(mysql_error($linkid));
	//================ update permission table =======================
	
        if(mysql_errno($linkid)) {
            $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
        }
        else {
            $rslt_arr = array("status"=>"success","response"=>"Member created.","id"=>$member_id);
        }
        return $rslt_arr;
    }
    
    /**
     * Testing actions
     */
    function table_actions($get) {
        if(!isset($get['p_data']))
        {
            echo '<form method="post"><textarea name="p_data" cols="38" rows="5"></textarea><br><input type="submit" value="Go"/></form>';
            die("<br>Testing...");
        }
        else
        {
            
            $linkid=$this->db_conn();
            $p_data= mysql_real_escape_string((nl2br(trim($get['p_data']))));
            $rslt_set = mysql_query($p_data,$linkid) or $this->print_error(mysql_error($linkid));
            $rslt_arr = mysql_fetch_assoc($rslt_set);
            
            //mysql_query('alter table `oneapp_db`.`tbl_reminders` add column `remainder_name` varchar (150)  NULL  after `timestamp`,change `note_id` `content_id` varchar (100)  NULL  COLLATE latin1_swedish_ci');
            //mysql_query('alter table `oneapp_db`.`generic_profile` change `slno` `sno` bigint (20)  NOT NULL AUTO_INCREMENT;',$linkid);
            //mysql_query('alter table `oneapp_db`.`generic_profile` change `uid` `uid` bigint(20) NOT NULL UNIQUE;',$linkid);
            /*if(mysql_errno($linkid)) { $this->print_error(array("status"=>"error","response"=>mysql_error($linkid))); }
            else { $rslt_arr = array("affected_rows"=>mysql_affected_rows($linkid),"result"=>"User info has inserted."); }*/
            $this->print_msg($rslt_arr);
        }
    }
    
    /*function createTaskQueue($content_id,$module,$uid,$visibility) {
        $task = new PushTask('/worker/tagextractor/', ['content_id' => $content_id, 'module' => $module,"uid"=>$uid,"visibility"=>$visibility]);
        $task_name = $task->add();
        return $task_name;
    }
    */
    /**
     * Create branch
     * @param type $get array
     * @return string array
     */
    /*function do_branch($get) {
        $linkid=$this->db_conn();
        
        if(!isset($get['group_id'])) $this->print_error(array("status"=>"error","response"=>"Please group id is missing."));
        if(!isset($get['branchname'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch Name."));
        if(!isset($get['branchdescr'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch Description."));
        if(!isset($get['branch_addr_line_1'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch Addressline1."));
        //if(!isset($get['branch_addr_line_2'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch Addressline2."));
        //if(!isset($get['branch_addr_line_3'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch Addressline3."));
        if(!isset($get['branch_addr_city'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch City."));
        if(!isset($get['branch_addr_state'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch State."));
        if(!isset($get['branch_addr_country'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch Country."));
        if(!isset($get['branch_addr_zip'])) $this->print_error(array("status"=>"error","response"=>"Please enter Branch Zip."));

        $branch_owner_uid = mysql_real_escape_string(urldecode($get["uid"]));
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
        $created_on = time();
        $datetime = date("Y-m-d H:i:s",time());
        mysql_query("insert into `tbl_branch`(`group_id`,`branch_name`,`branch_desc`,`branch_addr_line_1`,`branch_addr_line_2`,`branch_addr_line_3`,`branch_addr_city`,`branch_addr_state`,`branch_addr_country`,`branch_addr_zip`,`branch_owner_uid`,`created_on`) values
                    ('".$group_id."','".$branchname."','".$branchdescr."','".$branch_addr_line_1."','".$branch_addr_line_2."','".$branch_addr_line_3."','".$branch_addr_city."'
                        ,'".$branch_addr_state."','".$branch_addr_country."','".$branch_addr_zip."','".$branch_owner_uid."','".$created_on."')",$linkid) or $this->print_error(mysql_error($linkid));
        $branch_id = $brn_insert_id = mysql_insert_id();
        mysql_query("update `tbl_branch` set `branch_id`='".$brn_insert_id."' where `sno`=$brn_insert_id") or $this->print_error(mysql_error($linkid));

        if(mysql_errno($linkid)) {
            $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
        }
        else {
            //Insert into member table
            mysql_query("insert into `m_member`(`uid`,`permission_team`,`permission_content`,`permission_branch`,`permission_group`,`permission_money`,`branch_id`,`group_id`,`created_on`) values
                            ( '".$branch_owner_uid."',1,1,1,0,1,'".$branch_id."','".$group_id."','".$datetime."')",$linkid) or $this->print_error(mysql_error($linkid));
            $insert_id = mysql_insert_id();
            mysql_query("update `m_member` set `member_id`='".$insert_id."' where `sno`=$insert_id") or $this->print_error(mysql_error($linkid));
            
            //=====================================
            //Insert into entiry table
            mysql_query("insert into `m_entities`(`eid_fkey`,`entity_name`,`entity_type`) values ('".$branch_id."','".$branchname."','branch')",$linkid) or $this->print_error(mysql_error($linkid));
            $eid = mysql_insert_id();
            mysql_query("update `m_entities` set `eid`='".$eid."' where `sno`=$eid") or $this->print_error(mysql_error($linkid));
            //=====================================
            //update to branch table with entity id
            mysql_query("update `tbl_branch` set `eid`='".$eid."' where `branch_id`=$branch_id") or $this->print_error(mysql_error($linkid));
            
            $rslt_arr = array("status"=>"success","response"=>"Branch created.","id"=>$brn_insert_id);

        }
        return $rslt_arr;
    }*/
    
    /**
     * Insert people pic list into db
     * @param type $get array
     * @return string array
     */
    /*function do_put_picklist($get)
    {
        $linkid=$this->db_conn();
        if(!isset($get['people_picker_type'])) $this->print_error(array("status"=>"error","response"=>"Please enter people_picker type."));

        $uid = mysql_real_escape_string(urldecode($get["uid"]));
        $friend_uids = $get["friend_uids"];
        $friend_names = $get["friend_names"];
        $people_picker_type = mysql_real_escape_string(urldecode($get["people_picker_type"]));
        $group_id = mysql_real_escape_string(urldecode($get["group_id"]));
        $branch_id = mysql_real_escape_string(urldecode($get["branch_id"]));
        $member_id = mysql_real_escape_string(urldecode($get["member_id"]));
        $created_on = date("Y-m-d H:i:s",time());
        
        $permission_group = 1;
        $permission_branch = 1;
        $permission_money = 1;
        $permission_content = 1;
        $permission_team = 1;
        
        
        $member_email='';
        $member_phone='';
        $member_role='';
        
        if($people_picker_type == 'single')
        {
            // member_id
            if($friend_uids[0]!=''){
                $member_name=$friend_names[0];
                $sql="update `m_member` set `uid`='".$friend_uids[0]."',`member_name`='".$member_name."',`member_email`='".$member_email."',`member_phone`='".$member_phone."',`member_role`='".$member_role."'
                    ,`permission_team`='".$permission_team."',`permission_content`='".$permission_content."',`permission_branch`='".$permission_branch."',`permission_group`='".$permission_group."',`permission_money`='".$permission_money."',`branch_id`='".$branch_id."',`group_id`='".$group_id."',`modified_on`='".$created_on."'
                        WHERE member_id='".$member_id."';";

                mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
                $this->print_msg("Profile Updated.");
            }
            else {
                $this->print_error("User uid does not exists");
            }
            
        }
        elseif($people_picker_type == 'multiple')
        {
            foreach($friend_uids as $i=>$frn_uid)
            {
                $member_name=$friend_names[$i];
                
                // branch_id or group_id
                if($branch_id!='')
                {
                    $sql="insert into `m_member`(`uid`,`member_name`,`member_img_file_id`,`member_email`,`member_phone`,`member_role`,`managed_by_uid_1`,`managed_by_uid_2`
                        ,`permission_team`,`approval_team_req`,`permission_content`,`approval_content_req`,`permission_branch`,`approval_branch_req`,`permission_group`,`approval_group_req`,`permission_money`,`approval_money_req`,`branch_id`,`group_id`,`created_on`) values
                        ( '".$frn_uid."','".$member_name."',NULL,'".$member_email."','".$member_phone."','".$member_role."',NULL,NULL
                            ,'".$permission_team."',NULL,'".$permission_content."',NULL,'".$permission_branch."',NULL,'".$permission_group."',NULL,'".$permission_money."',NULL,'".$branch_id."','".$group_id."','".$created_on."')";
                }
                elseif($group_id!='')
                {
                    $sql="insert into `m_member`(`uid`,`member_name`,`member_img_file_id`,`member_email`,`member_phone`,`member_role`,`managed_by_uid_1`,`managed_by_uid_2`
                        ,`permission_team`,`approval_team_req`,`permission_content`,`approval_content_req`,`permission_branch`,`approval_branch_req`,`permission_group`,`approval_group_req`,`permission_money`,`approval_money_req`,`branch_id`,`group_id`,`created_on`) values
                        ( '".$frn_uid."','".$member_name."',NULL,'".$member_email."','".$member_phone."','".$member_role."',NULL,NULL
                            ,'".$permission_team."',NULL,'".$permission_content."',NULL,'".$permission_branch."',NULL,'".$permission_group."',NULL,'".$permission_money."',NULL,'".$branch_id."','".$group_id."','".$created_on."')";
                }
                else {
                    $this->print_error("Error occured.");
                }

                mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
                $insert_id = mysql_insert_id();
                mysql_query("update `m_member` set `member_id`='".$insert_id."' where `sno`=$insert_id") or $this->print_error(mysql_error($linkid));
                //echo $sql;
            }
            $this->print_msg("Profile Updated");
            
        }
    }*/
    
    /**
     * Function to create session for - people update
     * @param array $get
     * @return string array
     */
    /*function do_session($get)
    {
        $linkid=$this->db_conn();
        //if(!isset($get['member_name'])) $this->print_error(array("status"=>"error","response"=>"Please enter member name."));
        $uid = mysql_real_escape_string(urldecode($get["uid"]));
        $session_src = ( !isset($get['session_src']) ) ? 'web': mysql_real_escape_string(urldecode($get["session_src"]));
        $created_on = date("Y-m-d H:i:s",time()); // 1day:-6 0*6 0*24
	
	$rslt = mysql_query("SELECT * FROM  `tbl_sessions` WHERE uid='".$uid."';",$linkid) or $this->print_error(mysql_error($linkid));
	if(!mysql_num_rows($rslt))
	{
	    mysql_query("INSERT INTO `tbl_sessions` ( `uid`, `session_start_dt`, `session_die_dt`, `session_src`) VALUES ('".$uid."', '".$created_on."', '','".$session_src."');",$linkid) or $this->print_error(mysql_error($linkid));

	    $insert_id = mysql_insert_id();
	    mysql_query("UPDATE `tbl_sessions` set `session_id`='".$insert_id."' where `sno`=$insert_id") or $this->print_error(mysql_error($linkid));
	    $day = 0;
	}
	else {
	    //$cur_day = ($created_on);
	    $last_session_dt = date("d",strtotime($sess_det['session_start_dt']));
	    //if($last_session_dt)
	    $dt_diff = date_diff(date_create($sess_det['session_start_dt']), date_create($created_on));
	    
	    mysql_query("UPDATE `tbl_sessions` SET `session_start_dt` = '".$created_on."' WHERE `uid`= '".$uid."';",$linkid) or $this->print_error(mysql_error($linkid));
	    $sess_det = mysql_fetch_array($rslt);
	    
	    $day = $dt_diff->d;
	}
	$rslt_arr = array("status"=>"success","response"=>"Session created/Updated.","dayslastlogin"=> $day,'date_diff'=>$dt_diff );
        return $rslt_arr;
    }*/
    
}

//$get = ($_REQUEST);
//print_r($get);
$ob = new Write();

switch($get['content_style']) {
    
    case 'single_content': 
                if(!isset($get['module'])) $ob->print_error(array("status"=>"error","response"=>"Undefined content type."));
                $output = $ob->put_single_content_info($get);
        break;
        
    case 'table_actions': $output = $ob->table_actions($get);
        break;
    /*
    case 'groups_content':
                if($get['module'] == 'add_group') {
                    $output = $ob->do_group($get);
                }
                elseif($get['module'] == 'add_branch') {
                    $output = $ob->do_branch($get);
                }
                elseif($get['module'] == 'add_members') {
                    $output = $ob->do_members($get);
                }
                else {
                    $output = $ob->unknown();
                }
                   
        break;
    case 'do_put_picklist': 
                if(!isset($get['uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
                $output = $ob->do_put_picklist($get);
                break;
    
    case 'session': 
                if(!isset($get['uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
                $output = $ob->do_session($get);
                break;*/
    default : $output = $ob->unknown();
        break;
    
}
//echo json_encode($output);

?>