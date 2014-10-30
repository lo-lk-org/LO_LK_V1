<?php
//include "blocks/paths.php";
//include $myclass_url;

//$get = ($_REQUEST);
//print_r($get);

$output= '';

class Analytics extends Baseclass
{
    function __construct() {
        parent::__construct();
    }
    
    function get_user_count($get) {
        $linkid=$this->db_conn();

        $output=array(); $con='';
        $content_type=mysql_real_escape_string(urldecode($get['content_type']));


        if($content_type == 'user_count') {
            $sql = "select count(*) as user_count from generic_profile";
            //echo '<pre>';die($sql);
            $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
                if(mysql_errno($linkid)) {
                    $output=array("status"=>"error","response"=>mysql_error($linkid));
                }
                else {
                    $row = mysql_fetch_array($rslt);

                    $output['user_count'] = $row['user_count'];
                }
        }
     else {
            $output = $this->unknown();
        }
        return $output;
    }

    function get_content_count($get) {
        $linkid=$this->db_conn();
        $cond=''; $output=array();
        $uid=mysql_real_escape_string(urldecode($get['uid']));
        $content_type=mysql_real_escape_string(urldecode($get['content_type']));
        #$timestamp=strtotime(mysql_real_escape_string(urldecode($get['timestamp'])));//Unix timestamp

        if($uid != 'all') {
            $rslt = mysql_query("select `uid` from `generic_profile` where `uid`='$uid'",$linkid) or $this->print_error(mysql_error($linkid));
            $row = mysql_fetch_array($rslt);
            if($row['uid']=='') { $this->print_error("User/uid does not exits."); }

            $cond .= ' uid="'.$uid.'" ';
        }

        if($content_type == 'all') {
            if($cond != '') $cond = ' where '.$cond;
            $rslt = mysql_query("select count(*) as content_count from tbl_content $cond",$linkid) or $this->print_error(mysql_error($linkid));
            $row = mysql_fetch_array($rslt);
            $output['content_count'] = $row['content_count'];
        }
        elseif($content_type == 'note') {
                if($cond != '') $cond = ' and '.$cond.'" ';
                $sql="select count(*) as content_count from tbl_content where 1=1 and `content_type`='".$content_type."' $cond";
    //            die($sql);
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
                $row = mysql_fetch_array($rslt);
                $output['content_count'] = $row['content_count'];
        }
        elseif($content_type == 'expense') {
                if($cond != '') $cond = ' and '.$cond.'" ';
                $sql="select count(*) as content_count from tbl_content where 1=1 and `content_type`='".$content_type."' $cond";
    //            die($sql);
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
                $row = mysql_fetch_array($rslt);
                $output['content_count'] = $row['content_count'];
        }
        elseif($content_type == 'reminder') {
                if($cond != '') $cond = ' and '.$cond.'" ';
                $sql="select count(*) as content_count from tbl_content where 1=1 and `content_type`='".$content_type."' $cond";
    //            die($sql);
                $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
                $row = mysql_fetch_array($rslt);
                $output['content_count'] = $row['content_count'];
        }
        return $output;
    }
    function table_actions() {
        $linkid=$this->db_conn();
        mysql_query('alter table `oneapp_db`.`tbl_reminders` add column `remainder_name` varchar (150)  NULL  after `timestamp`,change `note_id` `content_id` varchar (100)  NULL  COLLATE latin1_swedish_ci');
        //mysql_query('alter table `oneapp_db`.`generic_profile` change `slno` `sno` bigint (20)  NOT NULL AUTO_INCREMENT;',$linkid);
        //mysql_query('alter table `oneapp_db`.`generic_profile` change `uid` `uid` bigint(20) NOT NULL UNIQUE;',$linkid);
        if(mysql_errno($linkid)) {
            $rslt_arr=array("status"=>"error","response"=>mysql_error($linkid));
        }
        else { 
            $rslt_arr = array("affected_rows"=>mysql_affected_rows($linkid),"result"=>"User info has inserted.");
        }
        return $rslt_arr;
    }
}
$ob = new Analytics();
if(!isset($get['content_style']))
{
    $output = $ob->unknown();
}
else
{
    switch($get['content_style']) {
	case 'user_count': 
			if(!isset($get['content_type'])) $ob->print_error(array("status"=>"error","response"=>"Undefined Content Type."));
			$output = $ob->get_user_count($get);
	    break;

	case 'table_actions': $output = $ob->table_actions($get);
	    break;

	case 'content_count': 
			if(!isset($get['content_type'])) $ob->print_error(array("status"=>"error","response"=>"Undefined Content Type."));
			if(!isset($get['uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined UID."));
			$output = $ob->get_content_count($get);

	    break;
	default : $output = $ob->unknown();
	    break;

    }
}
echo json_encode($output);
?>