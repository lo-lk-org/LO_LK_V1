<?php
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
    
    function del_single_content($get) {
        $linkid=$this->db_conn();
        $cond='';
        //http://localhost:13080/apis/delete/?action_object=user_profile&uid=8890977
        $output=array();
        $uid=mysql_real_escape_string($get['uid']);
        $content_id=mysql_real_escape_string($get['content_id']);
        $module=mysql_real_escape_string($get['module']);

        $rslt = mysql_query("select `uid` from `generic_profile` where `uid`='$uid'",$linkid) or $this->print_error(mysql_error($linkid));
        $row = mysql_fetch_array($rslt);
        if($row['uid']=='') { $this->print_error("User/uid does not exits."); }

        $rslt = mysql_query("select `content_id` from `tbl_content` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
        $row = mysql_fetch_array($rslt);
        if($row['content_id']=='') { $this->print_error("Content id does not exits in contents record."); }

        if($module == 'note') {

                $rslt = mysql_query("select `content_id` from `tbl_notes` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
                $row = mysql_fetch_array($rslt);
                if($row['content_id']=='') { $this->print_error("Content id does not exits in notes record."); }

                $sql = "DELETE from `tbl_notes` where `content_id`=$content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

                $sql = "DELETE from `tbl_content` WHERE `content_id`= $content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

                if(mysql_errno($linkid)) {
                    $this->print_error(array("status"=>"fail","response"=>mysql_error($linkid)));
                }
                else {

                    $output = array("status"=>"success","result"=>"Content removed successfully.");
                    $taskname = $this->createTaskQueue($content_id,$module,$uid);
                }
        }
        elseif($module == 'expense') {
                $rslt = mysql_query("select `content_id` from `tbl_expenses` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
                $row = mysql_fetch_array($rslt);
                if($row['content_id']=='') { $this->print_error("Content id does not exits in money record."); }

                $sql = "DELETE from `tbl_expenses` where `content_id`=$content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

                $sql = "DELETE from `tbl_content` WHERE `content_id`= $content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

                if(mysql_errno($linkid)) {
                    $this->print_error(array("status"=>"fail","response"=>mysql_error($linkid)));
                }
                else {

                    $output = array("status"=>"success","result"=>"Content removed successfully.");
                    $taskname = $this->createTaskQueue($content_id,$module,$uid);
                }

        }
        elseif($module == 'reminder') {

                $rslt = mysql_query("select `content_id` from `tbl_reminders` where `content_id`='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
                $row = mysql_fetch_array($rslt);
                if($row['content_id']=='') { $this->print_error("Content id does not exits in todo record."); }

                $sql = "DELETE from `tbl_reminders` where `content_id`=$content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

                $sql = "DELETE from `tbl_content` WHERE `content_id`= $content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

                if(mysql_errno($linkid)) {
                    $this->print_error(array("status"=>"fail","response"=>mysql_error($linkid)));
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

                $sql = "DELETE from `tbl_content` WHERE `content_id`= $content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

                if(mysql_errno($linkid)) {
                    $this->print_error(array("status"=>"fail","response"=>mysql_error($linkid)));
                }
                else {

                    $output = array("status"=>"success","result"=>"Content removed successfully.");
                    $taskname = $this->createTaskQueue($content_id,$module,$uid);
                }

        }
        else { $output = $this->unknown(); }
        return $output;
    }
    
    function del_groups_content($get) {
        $linkid=$this->db_conn();
        $cond='';
        
        $output=array();
        $uid=mysql_real_escape_string($get['uid']);
        $content_id=mysql_real_escape_string($get['content_id']);
        $module=mysql_real_escape_string($get['module']);

        $rslt = mysql_query("select `uid` from `generic_profile` where `uid`='$uid'",$linkid) or $this->print_error(mysql_error($linkid));
        $row = mysql_fetch_array($rslt);
        if($row['uid']=='') { $this->print_error("User/uid does not exits."); }

        if($module == 'branch') {

                $rslt = mysql_query("select * from `tbl_branch` where branch_id='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
                $row = mysql_fetch_array($rslt);
                if($row['sno']=='') { $this->print_error("No data found."); }

                $sql = "DELETE from `tbl_members` where `branch_id`=$content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

                $sql = "DELETE from `tbl_branch` WHERE `branch_id`= $content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

                if(mysql_errno($linkid)) {
                    $this->print_error(array("status"=>"fail","response"=>mysql_error($linkid)));
                }
                else {
                    $output = array("status"=>"success","response"=>"Content removed successfully.");
                }
        }
        elseif($module == 'member') {
                $rslt = mysql_query("select * from `tbl_members` where member_id='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
                $row = mysql_fetch_array($rslt);
                if($row['sno']=='') { $this->print_error("No data found."); }

                $sql = "DELETE from `tbl_members` where `member_id`=$content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

                if(mysql_errno($linkid)) {
                    $this->print_error(array("status"=>"fail","response"=>mysql_error($linkid)));
                }
                else {
                    $output = array("status"=>"success","response"=>"Content removed successfully.");
                }

        }
        elseif($module == 'group') {

                $rslt = mysql_query("select * from `tbl_groups` where group_id='$content_id'",$linkid) or $this->print_error(mysql_error($linkid));
                $row = mysql_fetch_array($rslt);
                if($row['sno']=='') { $this->print_error("No data found."); }

                $sql = "DELETE from `tbl_members` where `group_id`=$content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

                $sql = "DELETE from `tbl_branch` WHERE `group_id`= $content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

                $sql = "DELETE from `tbl_groups` WHERE `group_id`= $content_id";
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

                if(mysql_errno($linkid)) {
                    $this->print_error(array("status"=>"fail","response"=>mysql_error($linkid)));
                }
                else {

                    $output = array("status"=>"success","response"=>"Content removed successfully.");
                }

        }
        else { $output = $this->unknown(); }
        return $output;
    }

    function createTaskQueue($content_id,$module,$uid) {
        $task = new PushTask('/worker/tagremover/', ['content_id' => $content_id, 'module' => $module,"uid"=>$uid]);
        $task_name = $task->add();
        return $task_name;
    }
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
		if(!isset($get['uid'])) $ob->print_error(array("status"=>"fail","response"=>"Undefined uid."));
		if(!isset($get['module'])) $ob->print_error(array("status"=>"fail","response"=>"Undefined module."));
		if(!isset($get['content_id'])) $ob->print_error(array("status"=>"fail","response"=>"Undefined content id."));
		$output = $ob->del_single_content($get);
	    break;
	case "groups_content":
		if(!isset($get['uid'])) $ob->print_error(array("status"=>"fail","response"=>"Undefined uid."));
		if(!isset($get['module'])) $ob->print_error(array("status"=>"fail","response"=>"Undefined module."));
		if(!isset($get['content_id'])) $ob->print_error(array("status"=>"fail","response"=>"Undefined content id."));
		$output = $ob->del_groups_content($get);
	    break;
	default : $output = $ob->unknown();
	    break;

    }
}
echo json_encode($output);
?>