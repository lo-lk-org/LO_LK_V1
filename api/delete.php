<?php
header('Content-Type: application/json');

require_once 'google/appengine/api/taskqueue/PushTask.php';
use \google\appengine\api\taskqueue\PushTask;

//include "blocks/paths.php";
//include $myclass_url;

//$get = ($_REQUEST);
//print_r($get);
class delete extends Baseclass
{
    function __construct() {
        parent::__construct();
    }
    
    function del_single_content($get)
    {
        $linkid=$this->db_conn();
        $cond='';
        //http://localhost:13080/apis/delete/?action_object=user_profile&uid=8890977
        $output=array();
	$module=mysql_real_escape_string($get['module']);
	$uid=mysql_real_escape_string($get['uid']);
	//======================
        $rslt = mysql_query("select `uid` from `m_profile` where `uid`='$uid'",$linkid) or $this->print_error(mysql_error($linkid));
        $row = mysql_fetch_array($rslt);
        if($row['uid']=='') { $this->print_error("User/uid does not exits."); }
	//======================
	
	if($module=='profile')
	{
	    $output = $this->del_profile($get);
	}
	else if($module=='group')
	{
	    $output = $this->del_group($get);
	}
	else if($module=='member')
	{
	    $output = $this->del_member($get);
	}
	else
	{
	    $uid=mysql_real_escape_string($get['uid']);
//	    $content_id=mysql_real_escape_string($get['content_id']);

	    $rslt = mysql_query("select `uid` from `m_profile` where `uid`='$uid'",$linkid) or $this->print_error(mysql_error($linkid));
	    $row = mysql_fetch_array($rslt);
	    if($row['uid']=='') { $this->print_error("User/uid does not exits."); }

//	    $rslt = mysql_query("select `content_id` from `m_content` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
//	    $row = mysql_fetch_array($rslt);
//	    if($row['content_id']=='') { $this->print_error("Content id does not exits in contents record."); }

	    /*if($module == 'note') {

		    $rslt = mysql_query("select `content_id` from `tbl_notes` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
		    $row = mysql_fetch_array($rslt);
		    if($row['content_id']=='') { $this->print_error("Content id does not exits in notes record."); }

		    $sql = "DELETE from `tbl_notes` where `content_id`=$content_id";
		    $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

		    $sql = "DELETE from `m_content` WHERE `content_id`= $content_id";
		    $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

		    if(mysql_errno($linkid)) {
			$this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		    }
		    else {

			$output = array("status"=>"success","result"=>"Content removed successfully.");
			$taskname = $this->createTaskQueue($content_id,$module,$uid);
		    }
	    }
	    else*/ if($module == 'money') {
		    if(!isset($get['money_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined money id."));
		    $money_id=mysql_real_escape_string($get['money_id']);
		
		    $rslt = mysql_query("select `uid` from `m_money` where `uid`='$uid'",$linkid) or $this->print_error(mysql_error($linkid));
		    $row = mysql_fetch_array($rslt);
		    if($row['uid']=='') { $this->print_error("Invalid User/uid access."); }
		    
		    $rslt = mysql_query("select money_id FROM `m_money` where `money_id`='$money_id'",$linkid) or $this->print_error(mysql_error($linkid));
		    $row = mysql_fetch_array($rslt);
		    if($row['money_id']=='') { $this->print_error("Money id does not exits in money record."); }

		    $sql = "DELETE from `m_money` where `money_id`='$money_id'";
		    $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

		    $sql = "DELETE from `m_content` WHERE col_prim_key_name='money_id' AND col_prim_key_value= $money_id";
		    $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

		    if(mysql_errno($linkid)) {
			$this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		    }
		    else {

			$output = array("status"=>"success","result"=>"Money record deleted successfully.");
//			$taskname = $this->createTaskQueue($content_id,$module,$uid);
		    }

	    }
	    /*elseif($module == 'reminder') {

		    $rslt = mysql_query("select `content_id` from `tbl_reminders` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
		    $row = mysql_fetch_array($rslt);
		    if($row['content_id']=='') { $this->print_error("Content id does not exits in todo record."); }

		    $sql = "DELETE from `tbl_reminders` where `content_id`=$content_id";
		    $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

		    $sql = "DELETE from `m_content` WHERE `content_id`= $content_id";
		    $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

		    if(mysql_errno($linkid)) {
			$this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		    }
		    else {

			$output = array("status"=>"success","result"=>"Content removed successfully.");
			$taskname = $this->createTaskQueue($content_id,$module,$uid);
		    }

	    }
	    elseif($module == 'shoppinglist') {

		    $rslt = mysql_query("select `content_id` from `tbl_shoppinglist` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
		    $row = mysql_fetch_array($rslt);
		    if($row['content_id']=='') { $this->print_error("Content id does not exits in shopping list record."); }

		    $sql = "DELETE from `tbl_shoppinglist` where `content_id`=$content_id";
		    $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

		    $sql = "DELETE from `m_content` WHERE `content_id`= $content_id";
		    $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

		    if(mysql_errno($linkid)) {
			$this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		    }
		    else {

			$output = array("status"=>"success","result"=>"Content removed successfully.");
			$taskname = $this->createTaskQueue($content_id,$module,$uid);
		    }

	    }*/
	    else { $output = $this->unknown(); }
	}
        return $output;
    }
    
    function del_group($get)
    {
        $linkid=$this->db_conn();
        $cond='';
        $output=array();
	
//        $module=mysql_real_escape_string($get['module']);
//        $uid=mysql_real_escape_string($get['uid']);
//        $content_id=mysql_real_escape_string($get['content_id']);
        /*if($module == 'branch') {
                $rslt = mysql_query("select * from `tbl_branch` where branch_id='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
                $row = mysql_fetch_array($rslt);
                if($row['sno']=='') { $this->print_error("No data found."); }
                $sql = "DELETE from `m_member` where `branch_id`=$content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
                $sql = "DELETE from `tbl_branch` WHERE `branch_id`= $content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
                if(mysql_errno($linkid)) {
                    $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
                }
                else {
                    $output = array("status"=>"success","response"=>"Content removed successfully.");
                }
        }
        elseif($module == 'member') {
                $rslt = mysql_query("select * from `m_member` where member_id='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
                $row = mysql_fetch_array($rslt);
                if($row['sno']=='') { $this->print_error("No data found."); }
                $sql = "DELETE from `m_member` where `member_id`=$content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
                if(mysql_errno($linkid)) {
                    $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
                }
                else {
                    $output = array("status"=>"success","response"=>"Content removed successfully.");
                }
        }
        elseif($module == 'group') {
		$rslt = mysql_query("select * from `m_groups` where group_id='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
		$row = mysql_fetch_array($rslt);
		if($row['sno']=='') { $this->print_error("No data found."); }
		$sql = "DELETE from `m_member` where `group_id`=$content_id";
		$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
		$sql = "DELETE from `tbl_branch` WHERE `group_id`= $content_id";
		$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
		$sql = "DELETE from `m_groups` WHERE `group_id`= $content_id";
		$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
		if(mysql_errno($linkid)) {
		    $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
		}
		else {
		    $output = array("status"=>"success","response"=>"Content removed successfully.");
		}
        }
        else { $output = $this->unknown(); }*/
	if(!isset($get['group_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined group id."));
	$group_id=mysql_real_escape_string($get['group_id']);
	
	$rslt = mysql_query("SELECT * FROM `m_groups` WHERE group_id='$group_id'",$linkid) or $this->print_error(mysql_error($linkid));
	$row = mysql_fetch_array($rslt);
	if($row['sno']=='') { $this->print_error("Group not found."); }

	$entity_id=$row['entity_id'];
	$sql = "DELETE FROM `m_entities` WHERE `entity_id`=$entity_id";
	$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
	
	$sql = "DELETE FROM `m_member` WHERE `group_id`=$group_id";
	$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

	$sql = "DELETE FROM `m_groups` WHERE `group_id`= $group_id";
	$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

	if(mysql_errno($linkid)) {
	    $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
	}
	else {
	    $output = array("status"=>"success","response"=>"Group deleted successfully.");
	}
        return $output;
    }

    function del_member($get)
    {
	$linkid=$this->db_conn();
	if(!isset($get['member_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined member id."));
	$member_id=mysql_real_escape_string($get['member_id']);
	
	$rslt = mysql_query("SELECT * FROM `m_member` WHERE member_id='$member_id'",$linkid) or $this->print_error(mysql_error($linkid));
	$row = mysql_fetch_array($rslt);
	if($row['sno']=='') { $this->print_error("Member not found."); }

//	$entity_id=$row['entity_id'];
//	$sql = "DELETE FROM `m_entities` WHERE `entity_id`=$entity_id";
//	$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
	
	$sql = "DELETE FROM `m_member` WHERE `member_id`=$member_id";
	$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

	if(mysql_errno($linkid)) {
	    $this->print_error(array("status"=>"error","response"=>mysql_error($linkid)));
	}
	else {
	    $output = array("status"=>"success","response"=>"Member deleted successfully.");
	}
	return $output;
    }
    
//    function createTaskQueue($content_id,$module,$uid) {
//        $task = new PushTask('/worker/tagremover/', ['content_id' => $content_id, 'module' => $module,"uid"=>$uid]);
//        $task_name = $task->add();
//        return $task_name;
//    }
}

$ob = new delete();

if(!isset($get['content_style']))
{
    $output = $ob->unknown();
}
else
{
    switch($get['content_style']) {
	#case 'user_profile':  $output = delete_user_profile();
	#    break;
	case 'single_content': 
		if(!isset($get['uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
		if(!isset($get['module'])) $ob->print_error(array("status"=>"error","response"=>"Undefined module."));
//		if(!isset($get['content_id'])) $ob->print_error(array("status"=>"error","response"=>"Undefined content id."));
		$output = $ob->del_single_content($get);
	    break;
	/*case "groups_content":
		if(!isset($get['uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
		if(!isset($get['module'])) $ob->print_error(array("status"=>"error","response"=>"Undefined module."));
		if(!isset($get['content_id'])) $ob->print_error(array("status"=>"error","response"=>"Undefined content id."));
		$output = $ob->del_group($get);
	    break;*/
	default : $output = $ob->unknown();
	    break;

    }
}
//echo json_encode($output);
?>