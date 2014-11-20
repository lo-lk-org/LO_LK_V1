<?php
$output= '';
//$get = ($_REQUEST);
//include "blocks/paths.php";
//include $myclass_url;
	
/**
 * Search Class
 */
class Search extends Baseclass {
    function __construct() { }
    
    
    /**
     * Get user profile by uid
     * @param type $uid int
     * @return type array
     */
    function _view_profile($get) {
        $linkid=$this->db_conn();
	if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Undefined uid."));
	$uid=mysql_real_escape_string($get['uid']);
	$sql = "SELECT * FROM `m_profile` WHERE `uid`='$uid'";
        $rslt = mysql_query($sql,$linkid);
        if(mysql_errno($linkid)) {
            $rslt_arr=array("status"=>"error","response"=>mysql_error($linkid));
        }
        else {
            if(mysql_affected_rows($linkid) > 0) {
                while($row = mysql_fetch_assoc($rslt)) {
                    $result['uid']=$row['uid'];
                    $result['gid']=$row['gid'];
                    $result['fname']=$row['fname'];
                    $result['mname']=$row['mname'];
                    $result['lname']=$row['lname'];
                    $result['name']=$row['name'];
                    $result['uname']=$row['uname'];
                    $result['email']=$row['email'];
                    $result['phone']=$row['phone'];
                    $result['image_url']=$row['image_url'];
                    $result['timezone']=$row['timezone'];
                    $result['stream_id']=$row['stream_id'];
                    $result['info_update_timestamp']=$row['info_update_timestamp'];
		    $result['status']="success";
                }
            }
            else {
                $result['error']="No Records found.";
            }
            //$result['rows']=mysql_affected_rows($linkid);

            $rslt_arr = $result;
        }
        return $rslt_arr;
    }
    
    function _group_info($get)
    {
        $linkid=$this->db_conn();
        if(!isset($get['group_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined group id."));
        $group_id = mysql_real_escape_string(urldecode($get['group_id']));//$group_id = trim($group_id,',');
         //find_in_set(`group_id`,'$group_ids')
            $rslt=mysql_query("SELECT * FROM m_groups WHERE group_id='$group_id' ",$linkid) or $this->print_error(mysql_error($linkid));

            if(mysql_errno($linkid)) {
                $rslt_arr=array("status"=>"error","response"=>mysql_error($linkid));
            }
	    elseif(mysql_num_rows($rslt)==0)
	    {
		$this->print_error("No records found");
	    }
            else {
                $i=0;$data_array=array();
                while ($row=mysql_fetch_assoc($rslt))
                {

                    //$data_array[$i]['group_id'] = $row['group_id'];
                    $data_array['group_id'] = $row['group_id'];
                    $data_array['group_type'] = ucfirst($row['group_type']);
                    $data_array['group_name'] = ucfirst($this->format_text($row['group_name']));
                    $data_array['group_description'] = $this->format_text($row['group_description']);
                    $data_array['group_owner_uid'] = $row['group_owner_uid'];
                    $mems_count_qry = mysql_query("select count(*) as ttl_members from m_member where group_id=".$row['group_id']);
                    $member = mysql_fetch_array($mems_count_qry);
                    $group_member_ctr = $member['ttl_members'];
                    $data_array['group_member_ctr'] = $group_member_ctr; //$row['group_member_ctr'];
                    $data_array['file_id'] = $row['file_id'];
                    $data_array['group_addr_line_1'] = $row['group_addr_line_1'];
                    $data_array['group_addr_line_2'] = $row['group_addr_line_2'];
                    $data_array['group_addr_line_3'] = $row['group_addr_line_3'];
                    $data_array['group_addr_city'] = $row['group_addr_city'];
                    $data_array['group_addr_state'] = $row['group_addr_state'];
                    $data_array['group_addr_country'] = $row['group_addr_country'];
                    $data_array['group_addr_zip'] = $row['group_addr_zip'];
                    $data_array['group_addr_zip'] = $row['group_addr_zip'];
                    $data_array['entity_id'] = $row['entity_id'];
                    $data_array['created_on'] = $row['created_on'];

                    //$data_array[$i]['branch'] =  $this->get_group_branch_info($linkid,$row['group_id']);//$data_array2;
                    //$i++;
                }
                $rslt_arr = array("status"=>"success","results"=>$data_array);
        }
        return $rslt_arr;
    }

    /**
     * 
     * @param type $get array
     */
    function _member_info($get)
    {
        $linkid=$this->db_conn();
        if(!isset($get['member_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined Member id."));
//        $group_id = mysql_real_escape_string(urldecode($get['group_id']));
        $member_id = mysql_real_escape_string(urldecode($get['member_id']));
        
        $rslt = mysql_query("select * from m_member where member_id=".$member_id."",$linkid) or $this->print_error(mysql_error($linkid));
        if(mysql_errno($linkid)) {
            $rslt_arr=array("status"=>"error","response"=>mysql_error($linkid));
        }
	elseif(mysql_num_rows($rslt)==0)
	{
	    $this->print_error("No records found");
	}
        else
        {
            $data_array=array();
            while ($row=mysql_fetch_assoc($rslt)) {
                $data_array['member_id'] = $row['member_id'];
                $data_array['uid'] = $row['uid'];
                $data_array['member_name'] = $this->format_text($row['member_name']);
                $data_array['member_img_file_id'] = $row['member_img_file_id'];
                $data_array['member_email'] = $row['member_email'];
                $data_array['member_phone'] = $row['member_phone'];
                $data_array['member_role'] = $row['member_role'];
                $data_array['managed_by_uid_1'] = $row['managed_by_uid_1'];
                $data_array['managed_by_uid_2'] = $row['managed_by_uid_2'];
                //$data_array['permissions']['permission_group'] = $row['permission_group'];
                $data_array['group_id'] = $row['group_id'];
                $data_array['created_on'] = $row['created_on'];
            }
            $rslt_arr['status'] = "success";
            $rslt_arr['members'] = $data_array;
            //$rslt_arr['member_permission'] = $this->get_permissions_bymid($linkid,$group_id,$branch_id);
        }
        return $rslt_arr;
    }
    
    
    function _list_groups($get)
    {
        $linkid=$this->db_conn();
//        if(!isset($get['group_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined group id."));
//        $group_id = mysql_real_escape_string(urldecode($get['group_id']));//$group_id = trim($group_id,',');
        if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Undefined uid."));
	$page = $lat=(!isset($get['page']))? '0' : mysql_real_escape_string(urldecode($get['page']))-1;
	$limit = $lat=(!isset($get['limit']))? '35' : mysql_real_escape_string(urldecode($get['limit']));
		
        $uid = mysql_real_escape_string(urldecode($get['uid']));
	
         //find_in_set(`group_id`,'$group_ids')
            $rslt=mysql_query("SELECT * FROM m_groups WHERE group_owner_uid='$uid' ",$linkid) or $this->print_error(mysql_error($linkid));

            if(mysql_errno($linkid)) {
                $rslt_arr=array("status"=>"error","response"=>mysql_error($linkid));
            }
	    elseif(mysql_num_rows($rslt)==0)
	    {
		$this->print_error("No records found");
	    }
            else {
		$num_rows=mysql_num_rows($rslt);
                $i=0;$data_array=array();
                while ($row=mysql_fetch_assoc($rslt))
                {
		    $mems_count_qry = mysql_query("select count(*) as ttl_members from m_member where group_id=".$row['group_id']);
                    $member = mysql_fetch_array($mems_count_qry);
                    $group_member_ctr = $member['ttl_members'];
		    
                    //$data_array[$i]['group_id'] = $row['group_id'];
                    $data_array[$i]['group_id'] = $row['group_id'];
                    $data_array[$i]['group_type'] = ucfirst($row['group_type']);
                    $data_array[$i]['group_name'] = ucfirst($this->format_text($row['group_name']));
                    $data_array[$i]['group_description'] = $this->format_text($row['group_description']);
                    $data_array[$i]['group_owner_uid'] = $row['group_owner_uid'];
                    $data_array[$i]['group_member_ctr'] = $group_member_ctr; //$row['group_member_ctr'];
                    $data_array[$i]['file_id'] = $row['file_id'];
                    $data_array[$i]['group_addr_line_1'] = $row['group_addr_line_1'];
                    $data_array[$i]['group_addr_line_2'] = $row['group_addr_line_2'];
                    $data_array[$i]['group_addr_line_3'] = $row['group_addr_line_3'];
                    $data_array[$i]['group_addr_city'] = $row['group_addr_city'];
                    $data_array[$i]['group_addr_state'] = $row['group_addr_state'];
                    $data_array[$i]['group_addr_country'] = $row['group_addr_country'];
                    $data_array[$i]['group_addr_zip'] = $row['group_addr_zip'];
                    $data_array[$i]['group_addr_zip'] = $row['group_addr_zip'];
                    $data_array[$i]['entity_id'] = $row['entity_id'];
                    $data_array[$i]['created_on'] = $row['created_on'];

                    //$data_array[$i]['branch'] =  $this->get_group_branch_info($linkid,$row['group_id']);//$data_array2;
                    $i++;
                }
                $rslt_arr = array("status"=>"success","results"=>$data_array,'num_rows'=>$num_rows);
        }
        return $rslt_arr;
    }

    function _list_members($get)
    {
        $linkid=$this->db_conn();
//        if(!isset($get['member_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined Member id."));
	if(!isset($get['group_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined Group id."));
        $group_id = mysql_real_escape_string(urldecode($get['group_id']));
//        $member_id = mysql_real_escape_string(urldecode($get['member_id']));
        
        $rslt = mysql_query("select * from m_member where group_id=".$group_id."",$linkid) or $this->print_error(mysql_error($linkid));
        if(mysql_errno($linkid)) {
            $rslt_arr=array("status"=>"error","response"=>mysql_error($linkid));
        }
	elseif(mysql_num_rows($rslt)==0)
	{
	    $this->print_error("No records found");
	}
        else
        {
            $i=0;$data_array=array();
	    $num_rows=mysql_num_rows($rslt);
            while ($row=mysql_fetch_assoc($rslt)) {
                $data_array[$i]['member_id'] = $row['member_id'];
                $data_array[$i]['uid'] = $row['uid'];
                $data_array[$i]['member_name'] = $this->format_text($row['member_name']);
                $data_array[$i]['member_img_file_id'] = $row['member_img_file_id'];
                $data_array[$i]['member_email'] = $row['member_email'];
                $data_array[$i]['member_phone'] = $row['member_phone'];
                $data_array[$i]['member_role'] = $row['member_role'];
                $data_array[$i]['managed_by_uid_1'] = $row['managed_by_uid_1'];
                $data_array[$i]['managed_by_uid_2'] = $row['managed_by_uid_2'];
                //$data_array[$i]['permissions']['permission_group'] = $row['permission_group'];
                $data_array[$i]['group_id'] = $row['group_id'];
                $data_array[$i]['created_on'] = $row['created_on'];
		$i++;
            }
            $rslt_arr = array('status'=>"success",'num_rows'=>$num_rows,'members'=>$data_array);
            //$rslt_arr['member_permission'] = $this->get_permissions_bymid($linkid,$group_id,$branch_id);
        }
        return $rslt_arr;
    }
    
    function _list_category($get)
    {
        $linkid=$this->db_conn();
//        if(!isset($get['group_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined group id."));
//        $group_id = mysql_real_escape_string(urldecode($get['group_id']));//$group_id = trim($group_id,',');
//        if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Undefined uid."));
//	$page = $lat=(!isset($get['page']))? '0' : mysql_real_escape_string(urldecode($get['page']))-1;
//	$limit = $lat=(!isset($get['limit']))? '35' : mysql_real_escape_string(urldecode($get['limit']));
		
//        $uid = mysql_real_escape_string(urldecode($get['uid']));
	
         //find_in_set(`group_id`,'$group_ids')
	    $rslt=mysql_query("SELECT * FROM m_categories ORDER BY category_name ASC",$linkid) or $this->print_error(mysql_error($linkid));

	    if(mysql_errno($linkid)) {
		$rslt_arr=array("status"=>"error","response"=>mysql_error($linkid));
	    }
	    elseif(mysql_num_rows($rslt)==0)
	    {
		$this->print_error("No records found");
	    }
	    else {
		$num_rows=mysql_num_rows($rslt);
		$i=0;$data_array=array();
		while ($row=mysql_fetch_assoc($rslt))
		{
		    $data_array[$i]['category_id'] = $row['category_id'];
		    $data_array[$i]['category_name'] = ucfirst($row['category_name']);
		    $i++;
		}
		$rslt_arr = array("status"=>"success","results"=>$data_array,'num_rows'=>$num_rows);
	    }
	    return $rslt_arr;
    }
    
    function _social_contacts($get)
    {
	$linkid=$this->db_conn();
//	if(!isset($get['group_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined group id."));
//	$group_id = mysql_real_escape_string(urldecode($get['group_id']));//$group_id = trim($group_id,',');
//	$page = $lat=(!isset($get['page']))? '0' : mysql_real_escape_string(urldecode($get['page']))-1;
//	$limit = $lat=(!isset($get['limit']))? '35' : mysql_real_escape_string(urldecode($get['limit']));

	if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Undefined uid."));
	
        $uid = mysql_real_escape_string(urldecode($get['uid']));
	

	$rslt=mysql_query("SELECT * FROM m_social_contacts WHERE uid='".$uid."' ORDER BY following_name ASC",$linkid) or $this->print_error(mysql_error($linkid));

	if(mysql_errno($linkid)) {
	    $rslt_arr=array("status"=>"error","response"=>mysql_error($linkid));
	}
	elseif(mysql_num_rows($rslt)==0)
	{
	    $this->print_error("No records found");
	}
	else {
	    $num_rows=mysql_num_rows($rslt);
	    $i=0;$data_array=array();
	    while ($row=mysql_fetch_assoc($rslt))
	    {
		$data_array[$i]['sno'] = $row['sno'];
		$data_array[$i]['following_uid'] = $row['following_uid'];
		$data_array[$i]['following_uname'] = ucfirst($row['following_uname']);
		$data_array[$i]['following_name'] = ucfirst($row['following_name']);
		$i++;
	    }
	    $rslt_arr = array("status"=>"success","results"=>$data_array,'num_rows'=>$num_rows);
	}
        return $rslt_arr;
    }
    
    /********************************************************************************************/
    /**
     * Get single content api
     * @param type $get
     * @return type array
     */
    function get_single_content_info($get) {
        $linkid=$this->db_conn();
        
        $output=array(); $cond='';
        //$uid=mysql_real_escape_string($get['uid']);
//        $content_id=mysql_real_escape_string($get['content_id']);
        $module=mysql_real_escape_string($get['module']);
        //uid,content_id,file_id,visibility
	if($module == 'profile')
	{
	    $output= $this->_view_profile($get); 
	}
	elseif($module == 'group') 
	{
	    $output= $this->_group_info($get);
	}
	elseif($module == 'member') 
	{
	    $output= $this->_member_info($get);
	}
	elseif($module == 'member') 
	{
	    $output= $this->_member_info($get);
	}
	elseif($module == 'contacts') 
	{
	    $output= $this->_social_contacts($get);
	}
	else
	{
		if(!isset($get['uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
		$uid=mysql_real_escape_string($get['uid']);
		
		if($module == 'note') {
		    //Notes
		    $rslt = mysql_query("select c.uid,c.content_id,n.visibility,c.timestamp,n.note_id,n.note_text,n.visibility,fl.file_url,fl.file_type
						from tbl_notes n
						join m_content c on c.content_id=n.content_id
						left join tbl_files fl on fl.file_id = n.file_id
					where n.`content_id`=$content_id
					order by c.timestamp desc",$linkid) or $this->print_error(mysql_error($linkid));
		    $i=0;$data_array=array();
		    while ($row=mysql_fetch_array($rslt)) {

			$data_array[$i]['uid'] = $row['uid'];
			$data_array[$i]['content_id'] = $row['content_id'];
			$data_array[$i]['note_id'] = $row['note_id'];
			$data_array[$i]['note_text'] = $this->format_text($row['note_text']);
			$data_array[$i]['visibility'] = $row['visibility'];
			$data_array[$i]['timestamp'] = date("Y-m-d H:i:s",$row['timestamp']);
			//$size=300;$crop=TRUE;
			$data_array[$i]['file_url'] = $row['file_url'];//$this->image_serving_url($row['file_url'],$size,$crop,$row['file_type']);
			$data_array[$i]['media_type'] =  $this->get_file_type($row['file_type']);
			$i++;
		    }
		    $output['notes'] = $data_array;
		}
		elseif($module == 'money') {
			if(!isset($get['money_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined Money id."));
			$money_id = mysql_real_escape_string(urldecode($get['money_id']));
			
			//$rslt = mysql_query("select e.content_id,e.expense_id,e.expense_title,e.expense_amount,e.visibility  from m_money e join m_content c on c.content_id=e.content_id where e.content_id=$content_id $cond order by c.timestamp desc",$linkid) or $this->print_error(mysql_error($linkid));
			
			$rslt=mysql_query("SELECT * FROM m_money WHERE money_id='".$money_id."' AND uid='".$uid."' ");
			if(mysql_errno($linkid)) {
			    $this->print_error(mysql_error($linkid));
			}
			elseif(mysql_num_rows($rslt)==0)
			{
			    $this->print_error("No matching record found or item not created by you");
			}
			else {
			    $i=0;$data_array=array();
			    while($row = mysql_fetch_assoc($rslt))
			    {
				//$data_array[$i]['money_id']=$row['money_id'];
				$data_array['money_id']=$row['money_id'];
				$data_array['uid']=$row['uid'];
				$data_array['timestamp']=$row['timestamp'];
				$data_array['lat']=$row['lat'];
				$data_array['long']=$row['long'];
				$data_array['visibility']=$row['visibility'];
				$data_array['money_title']= $this->format_text($row['money_title']);
				$data_array['money_amount']=$row['money_amount'];
				$data_array['item_unit_price']=$row['item_unit_price'];
				$data_array['item_units']=$row['item_units'];
				$data_array['item_qty']=$row['item_qty'];
				$data_array['total_price']=$row['total_price'];
				$data_array['money_flow_direction']=$row['money_flow_direction'];
				$data_array['file_id']=$row['file_id'];
				$data_array['category_id']=$row['category_id'];
				$data_array['modified_on']=$row['modified_on'];
				$data_array['group_id']=$row['group_id'];
				//$i++;
			    }
			    $output['money'] = $data_array;
			}
		}
		elseif($module == 'reminder') {
		    $rslt = mysql_query("select c.content_id,reminder_id,remind_time,reminder_name,visibility
			from tbl_reminders where `content_id`=$content_id
			order by remind_time asc",$linkid) or $this->print_error(mysql_error($linkid));
			$i=0;
			while ($row=mysql_fetch_array($rslt)) {

			    $data_array[$i]['reminder_id'] = $row['reminder_id'];
			    $data_array[$i]['content_id'] = $row['content_id'];
			    $data_array[$i]['reminder_name'] = $this->format_text($row['reminder_name']);
			    $data_array[$i]['visibility'] = $row['visibility'];
			    $data_array[$i]['remind_time'] = date("Y-m-d H:i:s",$row['remind_time']);
			    $i++;
			}
			$output['reminders'] = $data_array;
		}
		else { $output = $this->unknown(); }
	}
        return $output;
    }
    
    /**
     * Get list content
     * @param type $get
     * @return type array
     */
    function get_list_content_info($get) {
        $linkid=$this->db_conn();
        $output=array(); $cond='';
        
        $module=mysql_real_escape_string($get['module']);
        

	if($module == 'profile')
	{
	    $output= $this->_list_profiles($get); 
	}
	elseif($module == 'group') 
	{
	    $output= $this->_list_groups($get);
	}
	elseif($module == 'member') 
	{
	    $output= $this->_list_members($get);
	}
	elseif($module == 'category') 
	{
	    $output= $this->_list_category($get);
	}
	else
	{
		if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Undefined uid."));
		$uid=mysql_real_escape_string($get['uid']);
		
		$page = (!isset($get['page']) || $get['page']< 0)? 1 : mysql_real_escape_string(urldecode($get['page']));
		$limit = (!isset($get['limit']) || $get['limit']< 0)? 3 : mysql_real_escape_string(urldecode($get['limit']));
		
		/*if($get['module'] == 'all') {
		    //money total
			$sql="select sum(amount) as ttl_expense from m_money e
			    where e.`uid`=$uid limit $page,$limit";
			$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
			$row = mysql_fetch_array($rslt);
			$output['expense_total'] = $row['ttl_expense'];

			//reminders
			//===============
			$time = time();
			$cond = ' AND r.remind_time > '.$time;
			//===============
			$sql = "select c.content_id,r.reminder_id,r.remind_time,r.reminder_name,r.visibility from tbl_reminders r
					    join m_content c on c.content_id=r.content_id
					    WHERE r.`uid`='$uid' $cond order by r.remind_time ASC limit $page,$limit";
			$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
			$i=0;
			while ($row=mysql_fetch_array($rslt)) {

			    $data_array[$i]['content_id'] = $row['content_id'];
			    $data_array[$i]['reminder_id'] = $row['reminder_id'];
			    $data_array[$i]['reminder_name'] = $this->format_text($row['reminder_name']);
			    $data_array[$i]['visibility'] = $row['visibility'];
			    $data_array[$i]['remind_time'] = date("Y-m-d H:i:s",$row['remind_time']);
			    $i++;
			}
			$output['reminders'] = $data_array;

			//Notes
			$sql="select c.content_id,n.note_id,n.note_text,n.visibility,date_format(from_unixtime(c.timestamp),'%b %d, %Y at %h:%i %p') as created_on,fl.file_url,fl.file_type,gp.name,gp.img_url
					    from tbl_notes n
					    join m_content c on c.content_id=n.content_id
					    left join tbl_files fl on fl.file_id = n.file_id
					    join generic_profile gp on gp.uid = c.uid
					    where n.`uid`='$uid' order by n.note_id desc limit $page,$limit";
			$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
			$i=0;$data_array=array();
			while ($row=mysql_fetch_array($rslt)) {

			    $data_array[$i]['content_id'] = $row['content_id'];
			    $data_array[$i]['note_id'] = $row['note_id'];
			    $data_array[$i]['note_text'] = $this->format_text($row['note_text']);
			    $data_array[$i]['visibility'] = $row['visibility'];
			    //$data_array[$i]['timestamp'] = date("Y-m-d H:i:s",$row['timestamp']);

			    $data_array[$i]['author_name'] = $row['name'];
			    $data_array[$i]['author_img_url'] = $row['img_url'];
			    $data_array[$i]['author_created_on'] = $row['created_on'];

			    //$size=300;$crop=TRUE;
			    $data_array[$i]['file_url'] = $row['file_url'];//$this->image_serving_url($row['file_url'],$size,$crop,$row['file_type']);
			    $data_array[$i]['media_type'] =  $this->get_file_type($row['file_type']);
			    $i++;
			}
			$output['notes'] = $data_array;

			// Shoppinglist
			$sql = "select c.content_id,sl.shop_list_item_id,sl.item_name,sl.item_qty,sl.shopping_status,sl.units,c.visibility,c.timestamp
					    from tbl_shoppinglist sl
					    join m_content c on c.content_id=sl.content_id
					    where sl.`uid`='$uid' order by sl.shop_list_item_id desc limit $page,$limit";
			$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
			$i=0;$data_array=array();
			while ($row=mysql_fetch_array($rslt)) {

			    $data_array[$i]['content_id'] = $row['content_id'];
			    $data_array[$i]['shop_list_item_id'] = $row['shop_list_item_id'];
			    $data_array[$i]['item_name'] = $this->format_text($row['item_name']);
			    $data_array[$i]['visibility'] = $row['visibility'];
			    $data_array[$i]['timestamp'] = date("Y-m-d H:i:s",$row['timestamp']);
			    $data_array[$i]['item_qty'] = $this->format_text($row['item_qty']);
			    $data_array[$i]['shopping_status'] = $this->format_text($row['shopping_status']);
			    $data_array[$i]['units'] = $this->format_text($row['units']);
			    $i++;
			}
			$output['shoppinglist'] = $data_array;
		}
		else*/ if($get['module'] == 'note') {

			$rslt = mysql_query("select c.content_id,n.note_id,n.note_text,n.visibility,c.timestamp from tbl_notes n
					    join m_content c on c.content_id=n.content_id
					    where n.`uid`='$uid' order by n.note_id desc limit $page,$limit",$linkid) or $this->print_error(mysql_error($linkid));

			$i=0;$data_array=array();
			while ($row=mysql_fetch_array($rslt)) {

			    $data_array[$i]['content_id'] = $row['content_id'];
			    $data_array[$i]['note_id'] = $row['note_id'];
			    $data_array[$i]['note_text'] = $this->format_text($row['note_text']);
			    $data_array[$i]['visibility'] = $row['visibility'];
			    $data_array[$i]['timestamp'] = date("Y-m-d H:i:s",$row['timestamp']);
			    $i++;
			}
			$output['notes'] = $data_array;

		}
		elseif($get['module'] == 'money') {

			//money total
			/*$rslt = mysql_query("select sum(money_amount) as ttl_expense from m_money 
			    where `uid`=$uid limit $page,$limit",$linkid) or $this->print_error(mysql_error($linkid));
			$row = mysql_fetch_array($rslt);
			$output['expense_total'] = $row['ttl_expense'];
	    //            $output['currency'] = $row['currency'];

			if($get['filter_type']=='time') {
			    if(!isset($get['filter_from'])) $this->print_error(array("status"=>"error","response"=>"Please specify filter from."));
			    //if(!isset($get['filter_to'])) $this->print_error(array("status"=>"error","response"=>"Please specify filter to."));
			    $from= strtotime(urldecode($get['filter_from']));
			    $to= isset($get['filter_to'])? strtotime(urldecode($get['filter_to'])) : time();
			    $cond .= ' and c.timestamp between '.$from.' and '.$to.' ';
			}*/
			/*$filter_from_str = urlencode(strtolower($get['filter_from']));
			if($get['filter_type']=='value') {
			    $cond = ' and e.title like "%'.$filter_from_str.'%" or e.desc like "%'.$filter_from_str.'%" ';
			}*/
			/*$sql="select * from m_money e
				join m_content c on c.content_id=e.content_id
				where e.uid='$uid' $cond 
				order by c.timestamp asc
				limit $page,$limit";
			$rslt = mysql_query($sql,$linkid) or $this->print_error($sql.''.mysql_error($linkid));

			if(mysql_errno($linkid)) {
			    $this->print_error(mysql_error($linkid));
			}
			else {

			    $i=0;$data_array=array();
			    while($row = mysql_fetch_assoc($rslt)) {
				$month=date("M",$row['timestamp']);
//				$data_array[$month][$i]['expense_id']=$row['expense_id'];
//				$data_array[$month][$i]['content_id']=$row['content_id'];
//				$data_array[$month][$i]['expense_title']=$row['title'];
//				$data_array[$month][$i]['expense_amount']=$row['amount'];
	    //                    $data_array[$month]['month_total']+=$row['amount'];
				$data_array[$i]['expense_id']=$row['expense_id'];
				$data_array[$i]['content_id']=$row['content_id'];
				$data_array[$i]['expense_title']=$row['title'];
				$data_array[$i]['expense_amount']=$row['amount'];
				$data_array[$i]['month']=date("M",$row['timestamp']);;
//				$data_array[$i]['visibility']=$row['visibility'];
	    //                    $data_array[$i]['currency']=$row['currency'];
	    //                    $data_array[$month]['month_total']+=$row['amount'];
				$i++;
			    }
			    $output['expenses'] = $data_array;
			}*/
			///======================
			$cond='';
			if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Undefined uid."));
			if(!isset($get['tff'])) $this->print_error(array("status"=>"error","response"=>"Please specify time filter from."));
			if(!isset($get['tft'])) $this->print_error(array("status"=>"error","response"=>"Please specify time filter to."));
			
			$uid = mysql_real_escape_string(urldecode($get['uid']));
			$q = mysql_real_escape_string(urldecode($get['q']));
			
			if($q!='')
			    $cond.= " AND (money_title LIKE '%".$q."%')";
			
			$dt_from=$this->validate_datetime(urldecode($get['tff']),'from');
			$dt_to=$this->validate_datetime(urldecode($get['tft']),'to');
			
			$tff= isset($get['tff'])? ($dt_from) : date("Y-m-d",time()-(60*60*24*30));//strtotime
			$tft= isset($get['tft'])? ($dt_to) : date("Y-m-d",time());
			$cond .= " and (m.timestamp) between '".$tff."' and '".$tft."' ";
			
			//========TOTAL QRY======
			$sql="SELECT * FROM m_money m
					    WHERE m.uid='".$uid."' $cond
					    ORDER BY m.sno DESC";//ORDER BY m.timestamp desc
			
			$ttl_rslt=mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
			$num_rows=mysql_num_rows($ttl_rslt);
			$output['total_rows'] = $num_rows;
			
			//==============
			$start_row=0;
			if($num_rows > $limit)
			{
			    
			   //=============< Pagination Class>==============
			    $paginacion = new Paginacion($limit);//MAX_RECORDS_X_PAGE
			    $paginacion->setTotalRegs($num_rows); //count($data)
			    $paginacion->setCurrentPage($page-1);// SET THE CURRENT PAGE
			    //=============< Pagination Class>==============
//			    $output['getTotalRegs']=$paginacion->getTotalRegs();
			    $getTotalPages=$output['pages']=$paginacion->getTotalPages();

			    $start_row=$output['start_row']=$paginacion->getStartRow();
			    //$output['end_row']=$paginacion->getLastRow();
			    
			    $output['current_page']=$paginacion->getCurrentPage()+1;
			    
			    if($paginacion->getCurrentPage()-1 > -1)
				$output['prev_page']=$paginacion->getCurrentPage();
			    
			    $p_next=$paginacion->getCurrentPage()+2;
			    if($getTotalPages >= $p_next )
				$output['next_page']=$p_next;
			}
			
			$sql=$sql." LIMIT $start_row,$limit";
//			echo '<pre>'.$sql;
			$rslt=mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
			if(mysql_errno($linkid)) {
			    $this->print_error(mysql_error($linkid));
			}
			elseif(mysql_num_rows($rslt)==0)
			{
			    $output['response'] = "No matching record found or item may not belongs to you";
			    $this->print_error($output);
			}
			else {
			    $i=0;$data_array=array();
			    while($row = mysql_fetch_assoc($rslt))
			    {
				$month=date("M",strtotime($row['timestamp']));
				$tmstmp=strtotime(date("Y-m-d",strtotime($row['timestamp']) ));
				$quarter_start_dt=$this->this_quarter();
				if( strtotime('today' ) == $tmstmp)
				{
				    $data_array['today'][$i]['sno']=$page+$i;
				    $data_array['today'][$i]['money_id']=$row['money_id'];
				    $data_array['today'][$i]['uid']=$row['uid'];
				    $data_array['today'][$i]['lat']=$row['lat'];
				    $data_array['today'][$i]['long']=$row['long'];
				    $data_array['today'][$i]['visibility']=$row['visibility'];
				    $data_array['today'][$i]['money_title']= $this->format_text($row['money_title']);
				    $data_array['today'][$i]['money_amount']=$row['money_amount'];
				    $data_array['today'][$i]['item_unit_price']=$row['item_unit_price'];
				    $data_array['today'][$i]['item_units']=$row['item_units'];
				    $data_array['today'][$i]['item_qty']=$row['item_qty'];
				    $data_array['today'][$i]['total_price']=$row['total_price'];
				    $data_array['today'][$i]['money_flow_direction']=$row['money_flow_direction'];
				    $data_array['today'][$i]['file_id']=$row['file_id'];
				    $data_array['today'][$i]['category_id']=$row['category_id'];
				    $data_array['today'][$i]['modified_on']=$row['modified_on'];
				    $data_array['today'][$i]['timestamp']=$row['timestamp'];
				    $data_array['today'][$i]['group_id']=$row['group_id'];
				}
				elseif( strtotime('yesterday' ) == $tmstmp )
				{
				    $data_array['yesterday'][$i]['sno']=$page+$i;
				    $data_array['yesterday'][$i]['money_id']=$row['money_id'];
				    $data_array['yesterday'][$i]['uid']=$row['uid'];
				    $data_array['yesterday'][$i]['lat']=$row['lat'];
				    $data_array['yesterday'][$i]['long']=$row['long'];
				    $data_array['yesterday'][$i]['visibility']=$row['visibility'];
				    $data_array['yesterday'][$i]['money_title']= $this->format_text($row['money_title']);
				    $data_array['yesterday'][$i]['money_amount']=$row['money_amount'];
				    $data_array['yesterday'][$i]['item_unit_price']=$row['item_unit_price'];
				    $data_array['yesterday'][$i]['item_units']=$row['item_units'];
				    $data_array['yesterday'][$i]['item_qty']=$row['item_qty'];
				    $data_array['yesterday'][$i]['total_price']=$row['total_price'];
				    $data_array['yesterday'][$i]['money_flow_direction']=$row['money_flow_direction'];
				    $data_array['yesterday'][$i]['file_id']=$row['file_id'];
				    $data_array['yesterday'][$i]['category_id']=$row['category_id'];
				    $data_array['yesterday'][$i]['modified_on']=$row['modified_on'];
				    $data_array['yesterday'][$i]['timestamp']=$row['timestamp'];
				    $data_array['yesterday'][$i]['group_id']=$row['group_id'];
				}
				elseif( strtotime('-1 week' ) <= $tmstmp )
				{
				    $data_array['this_week'][$i]['sno']=$page+$i;
				    $data_array['this_week'][$i]['money_id']=$row['money_id'];
				    $data_array['this_week'][$i]['uid']=$row['uid'];
				    $data_array['this_week'][$i]['lat']=$row['lat'];
				    $data_array['this_week'][$i]['long']=$row['long'];
				    $data_array['this_week'][$i]['visibility']=$row['visibility'];
				    $data_array['this_week'][$i]['money_title']= $this->format_text($row['money_title']);
				    $data_array['this_week'][$i]['money_amount']=$row['money_amount'];
				    $data_array['this_week'][$i]['item_unit_price']=$row['item_unit_price'];
				    $data_array['this_week'][$i]['item_units']=$row['item_units'];
				    $data_array['this_week'][$i]['item_qty']=$row['item_qty'];
				    $data_array['this_week'][$i]['total_price']=$row['total_price'];
				    $data_array['this_week'][$i]['money_flow_direction']=$row['money_flow_direction'];
				    $data_array['this_week'][$i]['file_id']=$row['file_id'];
				    $data_array['this_week'][$i]['category_id']=$row['category_id'];
				    $data_array['this_week'][$i]['modified_on']=$row['modified_on'];
				    $data_array['this_week'][$i]['timestamp']=$row['timestamp'];
				    $data_array['this_week'][$i]['group_id']=$row['group_id'];
				}
				elseif(strtotime(date("Y-m-1",time())) < $tmstmp )
				{
				    $data_array['this_month'][$i]['sno']=$page+$i;
				    $data_array['this_month'][$i]['money_id']=$row['money_id'];
				    $data_array['this_month'][$i]['uid']=$row['uid'];
				    $data_array['this_month'][$i]['lat']=$row['lat'];
				    $data_array['this_month'][$i]['long']=$row['long'];
				    $data_array['this_month'][$i]['visibility']=$row['visibility'];
				    $data_array['this_month'][$i]['money_title']= $this->format_text($row['money_title']);
				    $data_array['this_month'][$i]['money_amount']=$row['money_amount'];
				    $data_array['this_month'][$i]['item_unit_price']=$row['item_unit_price'];
				    $data_array['this_month'][$i]['item_units']=$row['item_units'];
				    $data_array['this_month'][$i]['item_qty']=$row['item_qty'];
				    $data_array['this_month'][$i]['total_price']=$row['total_price'];
				    $data_array['this_month'][$i]['money_flow_direction']=$row['money_flow_direction'];
				    $data_array['this_month'][$i]['file_id']=$row['file_id'];
				    $data_array['this_month'][$i]['category_id']=$row['category_id'];
				    $data_array['this_month'][$i]['modified_on']=$row['modified_on'];
				    $data_array['this_month'][$i]['timestamp']=$row['timestamp'];
				    $data_array['this_month'][$i]['group_id']=$row['group_id'];
				}
				elseif($quarter_start_dt < $tmstmp )
				{
				    $data_array['this_quarter'][$i]['sno']=$page+$i;
				    $data_array['this_quarter'][$i]['money_id']=$row['money_id'];
				    $data_array['this_quarter'][$i]['uid']=$row['uid'];
				    $data_array['this_quarter'][$i]['lat']=$row['lat'];
				    $data_array['this_quarter'][$i]['long']=$row['long'];
				    $data_array['this_quarter'][$i]['visibility']=$row['visibility'];
				    $data_array['this_quarter'][$i]['money_title']= $this->format_text($row['money_title']);
				    $data_array['this_quarter'][$i]['money_amount']=$row['money_amount'];
				    $data_array['this_quarter'][$i]['item_unit_price']=$row['item_unit_price'];
				    $data_array['this_quarter'][$i]['item_units']=$row['item_units'];
				    $data_array['this_quarter'][$i]['item_qty']=$row['item_qty'];
				    $data_array['this_quarter'][$i]['total_price']=$row['total_price'];
				    $data_array['this_quarter'][$i]['money_flow_direction']=$row['money_flow_direction'];
				    $data_array['this_quarter'][$i]['file_id']=$row['file_id'];
				    $data_array['this_quarter'][$i]['category_id']=$row['category_id'];
				    $data_array['this_quarter'][$i]['modified_on']=$row['modified_on'];
				    $data_array['this_quarter'][$i]['timestamp']=$row['timestamp'];
				    $data_array['this_quarter'][$i]['group_id']=$row['group_id'];
				}
				elseif(strtotime(date("Y-01-01",time())) < $tmstmp )
				{
				    $data_array['this_year'][$i]['sno']=$page+$i;
				    $data_array['this_year'][$i]['money_id']=$row['money_id'];
				    $data_array['this_year'][$i]['uid']=$row['uid'];
				    $data_array['this_year'][$i]['lat']=$row['lat'];
				    $data_array['this_year'][$i]['long']=$row['long'];
				    $data_array['this_year'][$i]['visibility']=$row['visibility'];
				    $data_array['this_year'][$i]['money_title']= $this->format_text($row['money_title']);
				    $data_array['this_year'][$i]['money_amount']=$row['money_amount'];
				    $data_array['this_year'][$i]['item_unit_price']=$row['item_unit_price'];
				    $data_array['this_year'][$i]['item_units']=$row['item_units'];
				    $data_array['this_year'][$i]['item_qty']=$row['item_qty'];
				    $data_array['this_year'][$i]['total_price']=$row['total_price'];
				    $data_array['this_year'][$i]['money_flow_direction']=$row['money_flow_direction'];
				    $data_array['this_year'][$i]['file_id']=$row['file_id'];
				    $data_array['this_year'][$i]['category_id']=$row['category_id'];
				    $data_array['this_year'][$i]['modified_on']=$row['modified_on'];
				    $data_array['this_year'][$i]['timestamp']=$row['timestamp'];
				    $data_array['this_year'][$i]['group_id']=$row['group_id'];
				}
				else
				{
				    $data_array['last_year'][$i]['sno']=$page+$i;
				    $data_array['last_year'][$i]['money_id']=$row['money_id'];
				    $data_array['last_year'][$i]['uid']=$row['uid'];
				    $data_array['last_year'][$i]['lat']=$row['lat'];
				    $data_array['last_year'][$i]['long']=$row['long'];
				    $data_array['last_year'][$i]['visibility']=$row['visibility'];
				    $data_array['last_year'][$i]['money_title']= $this->format_text($row['money_title']);
				    $data_array['last_year'][$i]['money_amount']=$row['money_amount'];
				    $data_array['last_year'][$i]['item_unit_price']=$row['item_unit_price'];
				    $data_array['last_year'][$i]['item_units']=$row['item_units'];
				    $data_array['last_year'][$i]['item_qty']=$row['item_qty'];
				    $data_array['last_year'][$i]['total_price']=$row['total_price'];
				    $data_array['last_year'][$i]['money_flow_direction']=$row['money_flow_direction'];
				    $data_array['last_year'][$i]['file_id']=$row['file_id'];
				    $data_array['last_year'][$i]['category_id']=$row['category_id'];
				    $data_array['last_year'][$i]['modified_on']=$row['modified_on'];
				    $data_array['last_year'][$i]['timestamp']=$row['timestamp'];
				    $data_array['last_year'][$i]['group_id']=$row['group_id'];
				}

//				$data_array[$i]['month']=$month;
//				$data_array[$month]['month_total']+=$row['money_amount'];
				$i++;
			    }
			    $output['money'] = $data_array;
			}
			///========================
		}
		elseif($get['module'] == 'reminder') {

			$rslt = mysql_query("select c.content_id,r.reminder_id,r.remind_time,r.reminder_name,r.visibility from tbl_reminders r
			    join m_content c on c.content_id=r.content_id
			    where r.`uid`='$uid' order by r.remind_time ASC limit $page,$limit",$linkid) or $this->print_error(mysql_error($linkid));
			$i=0;
			while ($row=mysql_fetch_array($rslt)) {

			    $data_array[$i]['content_id'] = $row['content_id'];
			    $data_array[$i]['reminder_id'] = $row['reminder_id'];
			    $data_array[$i]['reminder_name'] = $this->format_text($row['reminder_name']);
			    $data_array[$i]['visibility'] = $row['visibility'];
			    $data_array[$i]['remind_time'] = date("Y-m-d H:i:s",$row['remind_time']);
			    $i++;
			}
			$output['reminders'] = $data_array;

		}
		elseif($get['module'] == 'shoppinglist') {
			// Shoppinglist
			$sql="select c.content_id,sl.shop_list_item_id,sl.item_name,sl.item_qty,sl.units,sl.shopping_status,c.visibility,c.timestamp
					    from tbl_shoppinglist sl
					    join m_content c on c.content_id=sl.content_id
					    where sl.`uid`='$uid' order by sl.shop_list_item_id desc limit $page,$limit";
			$rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
			$i=0;$data_array=array();
			while ($row=mysql_fetch_array($rslt)) {

			    $data_array[$i]['content_id'] = $row['content_id'];
			    $data_array[$i]['shop_list_item_id'] = $row['shop_list_item_id'];
			    $data_array[$i]['item_name'] = $this->format_text($row['item_name']);
			    $data_array[$i]['visibility'] = $row['visibility'];
			    $data_array[$i]['timestamp'] = date("Y-m-d H:i:s",$row['timestamp']);
			    $data_array[$i]['item_qty'] = $this->format_text($row['item_qty']);
			    $data_array[$i]['shopping_status'] = $this->format_text($row['shopping_status']);
			    $data_array[$i]['units'] = $this->format_text($row['units']);
			    $i++;
			}
			//$output['lst_qry'] = $sql;
			$output['shoppinglist'] = $data_array;

		}
		else { $output = $this->unknown(); }
	}
        return $output;
    }
    
    /********************************************************************************************/
    
    /**
     * Search in tag content table using tag, module and uid(optional).
     * Display public content and private content of uid_requesting return JSON.
     * @example WITH UID= /api/search/?&action_object=tag_content&module=note&tag=food
     * &uid=101651219808545508511&requesting_uid=104219296596850018797&privacy=pub&time=2013-12-28+10%3A07%3A12
     * &lat=112&long=76
     * @example WITHOUT UID=/api/search/?&action_object=tag_content&module=note&tag=hello&uid=
     * &requesting_uid=&privacy=&time=2013-12-28+10%3A07%3A12
     * @param type Array
     * @return array Array
     */
    function get_tag_content_info($get) {
        $linkid=$this->db_conn();
        $output=array(); $cond='';

        $tag_str = mysql_real_escape_string(urldecode($get['tag']));
        $requesting_uid = mysql_real_escape_string(urldecode($get['requesting_uid']));
        
        $module = (!isset($get['module']))? 'all' : mysql_real_escape_string(urldecode($get['module']));
        $uid = (!isset($get['uid']))? '' : mysql_real_escape_string(urldecode($get['uid']));
//        $privacy=(!isset($get['privacy']))? 'pub' : mysql_real_escape_string(urldecode($get['privacy']));
//        $timestamp=  (!isset($get['time']))? date("Y-m-d H:i:s",time()) : strtotime(mysql_real_escape_string(urldecode($get['timestamp']))); //Unix timestamp
//        $lat=(!isset($get['lat']))? '' : mysql_real_escape_string(urldecode($get['lat']));
//        $long=(!isset($get['long']))? '' : mysql_real_escape_string(urldecode($get['long']));
//        $src=(!isset($get['src']))? 'pub' : mysql_real_escape_string(urldecode($get['src']));
        
        
        //insert to table
        $rslt= mysql_query("insert into `tbl_queries`(`sno`,`query_id`,`query_string`,`uid`,`timestamp`,`lat`,`long`,`src`) 
            values ( NULL,NULL,'".$tag_str."','".$uid."','".$timestamp."','.$lat.','.$long.','".$src."')", $linkid) or $this->print_error(mysql_error($linkid));
//        $sno = mysql_insert_id(); //"cnt".rand(8,getrandmax());
//        mysql_query("update `tbl_queries` set `query_id`='$sno' where `sno`=$sno") or $this->print_error(mysql_error($linkid));
       
        
        // search core query
        $list_content_info =  $this->get_srch_tags_data($tag_str,$requesting_uid,$uid,$module,$privacy);
//        echo '<pre>';print_r($list_content_info); die();
        if($list_content_info['status']=='error') {
            $this->print_error($list_content_info);
        }
         else {
                foreach ($list_content_info as $type => $data_arr) {
                    if(!empty($data_arr)) {
                        $output[$type] = array_map("unserialize", array_unique(array_map("serialize", $data_arr)));
                    }
                    else {
                        $output[$type] =$data_arr;
                    }
                }
    //            echo '<pre>';print_r($output); die();
                return $output;
        }
    }
    
    /**
     * Search in tag content table using tag, module and uid(optional).
     * Display public content and private content of uid_requesting return JSON.
     * @example WITH UID= /api/search/?&action_object=tag_content&module=note&tag=food
     * &uid=101651219808545508511&requesting_uid=104219296596850018797&privacy=pub&time=2013-12-28+10%3A07%3A12
     * &lat=112&long=76
     * @example WITHOUT UID=/api/search/?&action_object=tag_content&module=note&tag=hello&uid=
     * &requesting_uid=&privacy=&time=2013-12-28+10%3A07%3A12
     * @param type Array
     * @return array Array
     */
    function get_srch_tag_content_info($get) {
        $linkid=$this->db_conn();
        $output=array(); $cond='';

        $tag_str = mysql_real_escape_string(urldecode($get['tag']));
        $requesting_uid = mysql_real_escape_string(urldecode($get['requesting_uid']));
        
        $module = (!isset($get['module']))? 'all' : mysql_real_escape_string(urldecode($get['module']));
        $uid = (!isset($get['uid']))? '' : mysql_real_escape_string(urldecode($get['uid']));
        $privacy=(!isset($get['privacy']))? 'pub' : mysql_real_escape_string(urldecode($get['privacy']));
        $timestamp=  (!isset($get['time']))? date("Y-m-d H:i:s",time()) : strtotime(mysql_real_escape_string(urldecode($get['timestamp']))); //Unix timestamp
        $lat=(!isset($get['lat']))? '' : mysql_real_escape_string(urldecode($get['lat']));
        $long=(!isset($get['long']))? '' : mysql_real_escape_string(urldecode($get['long']));
        $src=(!isset($get['src']))? 'pub' : mysql_real_escape_string(urldecode($get['src']));
        
        
        //insert to table
        $rslt= mysql_query("insert into `tbl_queries`(`sno`,`query_id`,`query_string`,`uid`,`timestamp`,`lat`,`long`,`src`) 
            values ( NULL,NULL,'".$tag_str."','".$uid."','".$timestamp."','.$lat.','.$long.','".$src."')", $linkid) or $this->print_error(mysql_error($linkid));
        $sno = mysql_insert_id(); //"cnt".rand(8,getrandmax());
        mysql_query("update `tbl_queries` set `query_id`='$sno' where `sno`=$sno") or $this->print_error(mysql_error($linkid));
       
        
        // search core query
        $list_content_info =  $this->get_srch_tags_data($tag_str,$requesting_uid,$uid,$module,$privacy);
//        echo '<pre>';print_r($list_content_info); die();
        if($list_content_info['status']=='error') {
            $this->print_error($list_content_info);
        }
         else {
                foreach ($list_content_info as $type => $data_arr) {
                    if(!empty($data_arr)) {
                        //if($type == 'tags')
                            $output[$type] = array_map("unserialize", array_unique(array_map("serialize", $data_arr)));
                        //else
                         //   $output[$type] = $data_arr;
                    }
                    else {
                        $output[$type] = $data_arr;
                    }
                }
                $output['status'] = 'success';
//                echo '<pre>';print_r($output); die();
                return $output;
        }
    }
    
    /**
     * Get matched tag ids
     * @param type $query_str string
     * @param type $uid big int
     * @param type $module string
     * @param type $privacy string
     * @return type String
     */
    function get_srch_tags_data($query_str,$requesting_uid,$uid,$module,$privacy='pub') {
        $linkid=$this->db_conn();$cond = '';
        
        if($module == 'all' || $module == 'note' || $module == 'reminder' 
                || $module == 'money' ) {
            
                    if($module == 'all') {
                        //$cond .= '';
                    }
                    else {
                        $cond .= " and module='$module' and ";
                    }
                    
                    // return all pub
                    // if uid => return private content of uid
                    
                    if($cond == '') { $cond .= " and "; }
                    
                    $cond .= " tc.privacy='pub' and tc.tag_string like '%$query_str%' ";
                    
                    if($requesting_uid != '') {
                        $cond .= " or ( tc.`uid`='".$requesting_uid."' and tc.privacy='pri' and tc.tag_string like '%$query_str%' ) ";
                    }
                    
                    if($module == 'note' || $module == 'all') {
                            //limit $page,$limit
                            $sql = "select * from tbl_tag_content tc
                                    join tbl_notes n on n.content_id = tc.content_id
                                                where tc.tag_string like '%$query_str%' $cond ";

                            $rslt= mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid).' Query='.$sql);

        //                    $this->print_error($sql);
                            $i=0;$data_array=array();
                            while ($row=mysql_fetch_array($rslt)) {
                                    $data_array[$i]['tag_id'] = $row['tag_id'];
                                    $data_array[$i]['content_id'] = $row['content_id'];
                                    $data_array[$i]['note_id'] = $row['note_id'];
                                    $data_array[$i]['note_text'] = $this->format_text($row['note_text']);
                                    $data_array[$i]['visibility'] = $row['visibility'];
                                    $data_array[$i]['tag_string'] = $this->format_text($row['tag_string']);
                                    $data_array[$i]['uid'] = $this->format_text($row['uid']);
                                    $data_array[$i]['timestamp'] = $row['timestamp'];
                                    //$data_array[$i]['privacy'] = $row['privacy'];
                                    $i++;
                            }
                            //$output['sql'] = $sql;
                            $output['notes'] = $data_array;
                    }
                    if($module == 'money' || $module == 'all') {
                            //limit $page,$limit
                            $sql = "select * from tbl_tag_content tc
                                    join m_money e on e.content_id = tc.content_id
                                                where tc.tag_string like '%$query_str%' $cond ";

                            $rslt= mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid).' Query='.$sql);

        //                    $this->print_error($sql);
                            $i=0;$data_array=array();
                            while ($row=mysql_fetch_array($rslt)) {
                                    $data_array[$i]['tag_id'] = $row['tag_id'];
                                    $data_array[$i]['content_id'] = $row['content_id'];
                                    $month=date("M",$row['timestamp']);
                                    $data_array[$i]['expense_id'] = $row['expense_id'];
                                    $data_array[$i]['content_id'] = $row['content_id'];
                                    $data_array[$i]['title'] = $this->format_text($row['title']);
                                    $data_array[$i]['expense_title']=$this->format_text($row['title']);
                                    $data_array[$i]['desc'] = $this->format_text($row['desc']);
                                    $data_array[$i]['amount'] = $row['amount'];
                                    $data_array[$i]['expense_amount']=$row['amount'];
                                    $data_array[$i]['visibility'] = $row['visibility'];
                                    $data_array[$i]['uid'] = $row['uid'];
                                    $data_array[$i]['month']=$month;
                
                                    $i++;
                            }
                            //$output['sql'] = $sql;
                            $output['expenses'] = $data_array;
                    }
                    if($module == 'reminder' || $module == 'all') {
                            //limit $page,$limit
                            $sql = "select * from tbl_tag_content tc
                                    join tbl_reminders r on r.content_id = tc.content_id
                                                where tc.tag_string like '%$query_str%' $cond ";

                            $rslt= mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid).' Query='.$sql);

        //                    $this->print_error($sql);
                            $i=0;$data_array=array();
                            while ($row=mysql_fetch_array($rslt)) {
                                    $data_array[$i]['tag_id'] = $row['tag_id'];
                                    $data_array[$i]['content_id'] = $row['content_id'];
                                    $data_array[$i]['reminder_id'] = $row['reminder_id'];
                                    $data_array[$i]['reminder_name'] = $this->format_text($row['reminder_name']);
                                    $data_array[$i]['visibility'] = $row['visibility'];
                                    $data_array[$i]['remind_time'] = date("Y-m-d H:i:s",$row['remind_time']);
                                    $i++;
                            }
                            //$output['sql'] = $sql;
                            $output['reminders'] = $data_array;
                    }
                    if($module == 'shoppinglist' || $module == 'all') {
                        $rslt = mysql_query("select * from tbl_tag_content tc
                                join tbl_shoppinglist sl on sl.content_id = tc.content_id
                                where tc.tag_string like '%$query_str%' $cond
                                order by sl.shop_list_item_id desc",$linkid) or $this->print_error(mysql_error($linkid));
                        $i=0;$data_array=array();
                        while ($row=mysql_fetch_assoc($rslt)) {
                            $data_array[$i]['tag_id'] = $row['tag_id'];
                            $data_array[$i]['content_id'] = $row['content_id'];
                            $data_array[$i]['shop_list_item_id'] = $row['shop_list_item_id'];
                            $data_array[$i]['item_name'] = $this->format_text($row['item_name']);
                            $data_array[$i]['visibility'] = $row['visibility'];
                            $data_array[$i]['timestamp'] = date("Y-m-d H:i:s",$row['timestamp']);
            //                $data_array[$i]['uid'] = $row['uid'];
                            $i++;
                        }
                        $output['shoppinglist'] = $data_array;
                    }
            }
            else { $output = $this->unknown(); }
            return $output;
    }
    
    /**
     * 
     * @param type array
     * @return array array
     * @example 1./api/search/?action_object=search_content&requesting_uid=101651219808545508511
     * &query=sh&module=reminder&time=2013-12-28+10%3A07%3A12&lat=112&long=76&src=stream With UID(All public & pri of UID)
     * @example 2./api/search/?action_object=search_content&requesting_uid=&query=finish
     * &module=all&time=2013-12-28+10%3A07%3A12&lat=112&long=76&src=stream Without UID(All public)
     */
    function get_srch_content_info($get) {
        $linkid=$this->db_conn();
        $output=array(); $cond='';

        $requesting_uid = mysql_real_escape_string(urldecode($get['requesting_uid']));
        $query_str = mysql_real_escape_string(urldecode($get['query']));
        $lat=(!isset($get['lat']))? '' : mysql_real_escape_string(urldecode($get['lat']));
        $long=(!isset($get['long']))? '' : mysql_real_escape_string(urldecode($get['long']));
        $timestamp=  (!isset($get['time']))? date("Y-m-d H:i:s",time()) : strtotime(mysql_real_escape_string(urldecode($get['time']))); //Unix timestamp
        $src = $lat=(!isset($get['src']))? '' : mysql_real_escape_string(urldecode($get['src']));
//        $visibility =(!isset($get['visibility']))? 'pub' : mysql_real_escape_string(urldecode($get['visibility']));

        $module = (!isset($get['module']))? 'all' : mysql_real_escape_string(urldecode($get['module']));
        
        //insert to table
        $rslt= mysql_query("insert into `tbl_queries`(`sno`,`query_id`,`query_string`,`uid`,`timestamp`,`lat`,`long`,`src`) 
            values ( NULL,NULL,'".$query_str."','".$uid."','".$timestamp."','.$lat.','.$long.','".$src."')", $linkid) or $this->print_error(mysql_error($linkid));

        $sno = mysql_insert_id(); //"cnt".rand(8,getrandmax());

        mysql_query("update `tbl_queries` set `query_id`='$sno' where `sno`=$sno") or $this->print_error(mysql_error($linkid));
        
        // search core query
        $list_content_info =  $this->get_query_content($query_str,$requesting_uid,$module);
        
        if($list_content_info['status']=='error') {
            $this->print_error($list_content_info);
        }
         else {
             /*
                // skip stoping words
                $q_arr = explode(" ",$query_str);
                $rdata = $this->skip_stopwords($q_arr);

                //loop through each query words
                foreach ($rdata as $qry) {
                    $temp_res = $this->get_query_content($qry,$requesting_uid,$module);

                    $type = 'expenses';
                    if(count($temp_res[$type])) {
                        foreach($temp_res[$type] as $i=>$res) {
                            $content_id=$res['content_id'];
                            if(in_array($content_id, $list_content_info[$type])) {
        //                        echo 'already in array..';
                            }
                            else {
                                //skipped
                                $list_content_info[$type][]=$res;
                            }
                        }
                    }
                    
                    $type='reminders';
                    if(count($temp_res[$type])) {
                        foreach($temp_res[$type] as $i=>$res) {
                            $content_id=$res['content_id'];
                            if(in_array($content_id, $list_content_info[$type])) {
        //                        echo 'already in array..';
                            }
                            else {
                                //skipped
                                $list_content_info[$type][]=$res;
                            }
                        }
                    }

                    $type='notes';
                    if(count($temp_res[$type])) {
                        foreach($temp_res[$type] as $i=>$res) {
                            $content_id=$res['content_id'];
                            foreach($list_content_info[$type] as $prior) {

                                if(!in_array($content_id, $prior)) {

                                    $list_content_info[$type][]=$res;
                                }
                            }
                        }
                    }
                    $type='profile';
                    if(count($temp_res[$type])) {
                        foreach($temp_res[$type] as $i=>$res){
                            foreach($list_content_info[$type] as $prior) {
                                    $list_content_info[$type][]=$res;
                            }
                        }
                    }
                }//Keyword loop ends
                */
//             if($list_content_info)
             
             
                foreach ($list_content_info as $type => $data_arr) {
                    if(!empty($data_arr)) {
                        $output[$type] = array_map("unserialize", array_unique(array_map("serialize", $data_arr)));
                    }
                    else {
                        $output[$type] =$data_arr;
                    }
                }
//                echo '<pre>';print_r($output); die();
                $output["status"] = "success";
                return $output;
        }
       
    }
    
    function get_srch_expense($query_str,$cond) {      
        $linkid=$this->db_conn();

        // limit $page,$limit
        $sql="select * from m_money e 
            join m_content c on c.content_id=e.content_id
            where (e.title like '%$query_str%' or e.desc like '%$query_str%') 
            and ( $cond ) 
            order by e.expense_id desc";
        $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));

        $i=0;$data_array=array();
        while ($row=mysql_fetch_assoc($rslt)) {
                $month=date("M",$row['timestamp']);
                $data_array[$i]['expense_id'] = $row['expense_id'];
                $data_array[$i]['content_id'] = $row['content_id'];
                $data_array[$i]['title'] = $this->format_text($row['title']);
                $data_array[$i]['expense_title']=$this->format_text($row['title']);
                $data_array[$i]['desc'] = $this->format_text($row['desc']);
                $data_array[$i]['amount'] = $row['amount'];
                $data_array[$i]['expense_amount']=$row['amount'];
                $data_array[$i]['visibility'] = $row['visibility'];
                $data_array[$i]['uid'] = $row['uid'];
                $data_array[$i]['month']=$month;
//                    $data_array[$i]['currency']=$row['currency'];
//                    $data_array[$month]['month_total']+=$row['amount'];
                $i++;
        }
        return $data_array;
    }
    
    function get_srch_reminder($query_str,$cond) {
            $linkid=$this->db_conn();
        
            //reminders limit $page,$limit
            $rslt = mysql_query("select c.uid,c.content_id,r.reminder_id,r.remind_time,r.reminder_name,r.visibility from tbl_reminders r
                                join m_content c on c.content_id=r.content_id
                                where r.reminder_name like '%$query_str%'  
                                and ( $cond ) 
                                order by r.remind_time ASC",$linkid) or $this->print_error(mysql_error($linkid));
            $i=0;$data_array=array();
            while ($row=mysql_fetch_assoc($rslt)) {

                $data_array[$i]['content_id'] = $row['content_id'];
                $data_array[$i]['reminder_id'] = $row['reminder_id'];
                $data_array[$i]['reminder_name'] = $this->format_text($row['reminder_name']);
                $data_array[$i]['visibility'] = $row['visibility'];
                $data_array[$i]['remind_time'] = date("Y-m-d H:i:s",$row['remind_time']);
//                $data_array[$i]['uid'] = $row['uid'];
                $i++;
            }
            return $data_array;
    }
    
    function get_srch_shoppinglist($query_str,$cond) {
            $linkid=$this->db_conn();
        
            $rslt = mysql_query("select c.uid,c.content_id,c.visibility,sl.shop_list_item_id,sl.item_name,sl.timestamp from tbl_shoppinglist sl
                                join m_content c on c.content_id = sl.content_id
                                where sl.item_name like '%$query_str%'  
                                and ( $cond ) 
                                order by sl.shop_list_item_id desc",$linkid) or $this->print_error(mysql_error($linkid));
            $i=0;$data_array=array();
            while ($row=mysql_fetch_assoc($rslt)) {

                $data_array[$i]['content_id'] = $row['content_id'];
                $data_array[$i]['shop_list_item_id'] = $row['shop_list_item_id'];
                $data_array[$i]['item_name'] = $this->format_text($row['item_name']);
                $data_array[$i]['visibility'] = $row['visibility'];
                $data_array[$i]['timestamp'] = date("Y-m-d H:i:s",$row['timestamp']);
//                $data_array[$i]['uid'] = $row['uid'];
                $i++;
            }
            return $data_array;
    }
    
    function get_srch_note($query_str,$cond) {
        $linkid=$this->db_conn();
        //Notes limit $page,$limit
        $rslt = mysql_query("select c.uid,c.content_id,n.note_id,n.note_text,n.visibility,c.timestamp,fl.file_url,fl.file_type
			    from tbl_notes n
                            join m_content c on c.content_id=n.content_id
			    left join tbl_files fl on fl.file_id = n.file_id
                            where n.note_text like '%$query_str%'  
                            and ( $cond )
                            order by n.note_id desc",$linkid) or $this->print_error(mysql_error($linkid));
        $i=0;$data_array=array();
        while ($row=mysql_fetch_assoc($rslt)) {

            $data_array[$i]['content_id'] = $row['content_id'];
            $data_array[$i]['note_id'] = $row['note_id'];
            $data_array[$i]['note_text'] = $this->format_text($row['note_text']);
            $data_array[$i]['visibility'] = $row['visibility'];
            $data_array[$i]['timestamp'] = date("Y-m-d H:i:s",$row['timestamp']);
            $data_array[$i]['file_url'] = $row['file_url'];
	    $data_array[$i]['media_type'] =  $this->get_file_type($row['file_type']);
//            $data_array[$i]['uid'] = $row['uid'];
            $i++;
        }
        return $data_array;
    }
    
    function get_srch_profile($query_str,$cond) {
        $linkid=$this->db_conn();
        //Profile
        $rslt = mysql_query("select p.fname,p.lname,p.img_url,p.uid from generic_profile p 
                                where p.fname like '%$query_str%' or p.mname like '%$query_str%' 
                                or p.name like '%$query_str%' or p.uname like '%$query_str%'
                                order by p.fname desc",$linkid) or $this->print_error(mysql_error($linkid));
        $i=0;$data_array=array();
        while ($row=mysql_fetch_assoc($rslt)) {

            $data_array[$i]['fname'] = $this->format_text($row['fname']);
            $data_array[$i]['lname'] = $this->format_text($row['lname']);
            $data_array[$i]['name'] = $this->format_text($row['name']);
            $data_array[$i]['uname'] = $this->format_text($row['uname']);
//            $data_array[$i]['uid'] = $row['uid'];
            $i++;
        }
        return $data_array;
    }

    /**
     * Get matched content ids
     * @param type $query_str
     * @param type array
     */
    function get_query_content($query_str,$uid,$module) {

            $cond .= " ( c.visibility='pub' ) ";
            if($uid != '') {
                $cond .= " or ( c.`uid`='".$uid."' and c.visibility='pri' ) ";
            }
            
            
            if($module == 'all') {
               
                $output['expenses'] = $this->get_srch_expense($query_str,$cond);
                $output['reminders'] = $this->get_srch_reminder($query_str,$cond);
                $output['notes'] = $this->get_srch_note($query_str,$cond);
                $output['profile'] = $this->get_srch_profile($query_str,$cond);
                $output['shoppinglist'] = $this->get_srch_shoppinglist($query_str,$cond);
                   
            }
            elseif($module == 'note') {
                    $output['notes'] = $this->get_srch_note($query_str,$cond);
            }
            elseif($module == 'money') {
                
                $output['expenses'] = $this->get_srch_expense($query_str,$cond);
            }
            elseif($module == 'reminder') {
                $output['reminders'] = $this->get_srch_reminder($query_str,$cond);
                
            }
            elseif($module == 'shoppinglist') {
                $output['shoppinglist'] = $this->get_srch_shoppinglist($query_str,$cond);
                
            }
            elseif($module == 'profile') {
                $output['profile'] = $this->get_srch_profile($query_str,$cond);
            }
            else { $output = $this->unknown(); }
            return $output;
    }

    function get_list_groups($get)
    {
        $linkid=$this->db_conn();
        if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Undefined uid."));
        $uid = mysql_real_escape_string(urldecode($get['uid']));
        $rslt=mysql_query("SELECT * FROM `m_member` as m WHERE m.`uid`='$uid' ",$linkid) or $this->print_error(mysql_error($linkid));

        $i=0;$data_array=array();
        while ($row=mysql_fetch_assoc($rslt)) {

            $data_array[$i]['member_id'] = $row['member_id'];
            $data_array[$i]['group_id'] = $row['group_id'];
            $data_array[$i]['branch_id'] = $row['branch_id'];
            //$data_array[$i]['member_name'] = $row['member_name'];
            $i++;
        }
        $rslt_arr = array("status"=>"success","results"=>$data_array);
        return $rslt_arr;
    }

    function get_group_members_info($linkid,$group_id)
    {
            $rslt=mysql_query("SELECT * FROM `m_member` WHERE group_id='$group_id' ",$linkid) or $this->print_error(mysql_error($linkid));
            $data_array=array();
            if(mysql_errno($linkid)) {
                $data_array=array("status"=>"error","response"=>mysql_error($linkid));
            }
            else {
                $i=0;
                while ($row=mysql_fetch_assoc($rslt)) {
                    $data_array[$i]['member_id'] = $row['member_id'];
                    $data_array[$i]['member_name'] = $this->format_text($row['member_name']);
                    $data_array[$i]['member_email'] = $row['member_email'];
                    $data_array[$i]['member_phone'] = $row['member_phone'];
                    //$data_array[$i]['branch_desc'] = $this->format_text($row['branch_desc']);
                    $data_array[$i]['created_on'] = date("Y-m-d H:i:s",$row['created_on']);
                    $data_array[$i]['uid'] = $row['uid'];
                    $i++;
                }
            }
            return $data_array;
    }
    
    /*function get_group_branch_info($linkid,$group_id)
    {
        $rslt=mysql_query("SELECT * FROM `tbl_branch` WHERE group_id='$group_id'",$linkid) or $this->print_error(mysql_error($linkid));
            $data_array=array();
            if(mysql_errno($linkid)) {
                $data_array=array("status"=>"error","response"=>mysql_error($linkid));
            }
            else {
                $i=0;
                while ($row=mysql_fetch_assoc($rslt)) {

                    $data_array[$i]['branch_id'] = $row['branch_id'];
                    $data_array[$i]['branch_name'] = $this->format_text($row['branch_name']);
                    $data_array[$i]['branch_desc'] = $this->format_text($row['branch_desc']);
                    
                    $mems_count_qry = mysql_query("select count(*) as mem_cnt from m_member where branch_id=".$row['branch_id']);
                    $member = mysql_fetch_array($mems_count_qry);
                    $data_array[$i]['branch_member_ctr'] = $member['mem_cnt'];
                    $data_array[$i]['created_on'] = date("Y-m-d H:i:s",$row['created_on']);
                    $data_array[$i]['uid'] = $row['branch_owner_uid'];
                    $data_array[$i]['eid'] = $row['eid'];
                    $i++;
                }
        }
        return $data_array;
    }
    
    function get_group_info($linkid,$group_id)
    {
            $sql="SELECT * FROM `tbl_groups` WHERE group_id='$group_id'";
            $rslt=mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
            $data_array=array();
            if(mysql_errno($linkid)) {
                $data_array=array("status"=>"error","response"=>mysql_error($linkid));
            }
            else {

                while ($row=mysql_fetch_assoc($rslt)) {
                    $data_array['group_id'] = $row['group_id'];
                    $data_array['group_type'] = $row['group_type'];
                    $data_array['group_name'] = $this->format_text($row['group_name']);
                    $data_array['group_description'] = $this->format_text($row['group_description']);
                    $data_array['uid'] = $row['group_owner_uid'];
                    $data_array['common_prod_flag'] = $row['common_prod_flag'];
                    $data_array['created_on'] = date("Y-m-d H:i:s",$row['created_on']);
                }
            }
            return $data_array;
    }*/
    
    function get_branch_member_by_groupid($get)
    {
        $linkid=$this->db_conn();
        if(!isset($get['group_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined group_id."));
        if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Undefined UID."));
        $group_id = mysql_real_escape_string(urldecode($get['group_id']));
        $uid = mysql_real_escape_string(urldecode($get['uid']));
        
        $rslt_arr=array();
        $rslt_arr['group'] = $this->get_group_info($linkid,$group_id);
        $rslt_arr['branches'] = $this->get_group_branch_info($linkid,$group_id);
        $rslt_arr['members'] = $this->get_group_members_info($linkid,$group_id);
        $rslt_arr['grp_permissions'] = $this->get_permissions_bygid($linkid,$group_id,$uid);
        
        return $rslt_arr;
    }
    
    function get_members_by_branchid($get)
    {
        $linkid=$this->db_conn();
        if(!isset($get['branch_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined branch id."));
        if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Undefined UID."));
        $branch_id = mysql_real_escape_string(urldecode($get['branch_id']));
        $uid = mysql_real_escape_string(urldecode($get['uid']));
        
        $rslt = mysql_query("select * from m_member where branch_id=".$branch_id,$linkid) or $this->print_error(mysql_error($linkid));
        if(mysql_errno($linkid)) {
            $rslt_arr=array("status"=>"error","response"=>mysql_error($linkid));
        }
        else
        {
            $i=0;$data_array=array();
            while ($row=mysql_fetch_assoc($rslt)) {
                $data_array[$i]['member_id'] = $row['member_id'];
                $data_array[$i]['member_name'] = $this->format_text($row['member_name']);
                $data_array[$i]['member_email'] = $row['member_email'];
                $data_array[$i]['member_phone'] = $row['member_phone'];
                //$data_array[$i]['member_email'] = $this->format_text($row['branch_desc']);
                //$data_array[$i]['branch_member_ctr'] = $row['member_phone'];
                $data_array[$i]['created_on'] = date("Y-m-d H:i:s",$row['created_on']);
                $data_array[$i]['uid'] = $row['branch_owner_uid'];
                $i++;
            }
            $rslt_arr['members'] = $data_array;
        }
        $rslt_arr['branch'] = $this->get_branch_info($linkid,$branch_id);
        $rslt_arr['branch_permissions'] = $this->get_permissions_bybid($linkid,$branch_id,$uid);

        return $rslt_arr;
    }
    
    /**
     * 
     * @param type $linkid
     * @param type $branch_id
     * @return type
     */
    /*function get_branch_info($linkid,$branch_id)
    {
        $rslt=mysql_query("SELECT * FROM tbl_branch  where branch_id = '$branch_id' ",$linkid) or $this->print_error(mysql_error($linkid));
        $data_array=array();
        while ($row=mysql_fetch_assoc($rslt)) {

            $data_array['branch_id'] = $row['branch_id'];
            $data_array['branch_name'] = $this->format_text($row['branch_name']);
            $data_array['branch_desc'] = $this->format_text($row['branch_desc']);
            $data_array['created_on'] = date("Y-m-d H:i:s",$row['created_on']);
        }
        return $data_array;
    }*/

    /*
    function get_permissions_bygid($linkid,$group_id,$uid)
    {
        $rslt = mysql_query("select * from m_member where uid=".$uid." and group_id=".$group_id."",$linkid) or $this->print_error(mysql_error($linkid));
            $data_array=array();
            while ($row=mysql_fetch_assoc($rslt)) {
                $data_array['permission_team'] = $row['permission_team'];
                $data_array['permission_content'] = $row['permission_content'];
                $data_array['permission_branch'] = $row['permission_branch'];
                $data_array['permission_group'] = $row['permission_group'];
                $data_array['permission_money'] = $row['permission_money'];
                $data_array['uid'] = $row['uid'];
            }
        return $data_array;
    }
    
    function get_permissions_bybid($linkid,$branch_id,$uid)
    {
        $rslt = mysql_query("select * from m_member where uid=".$uid." or branch_id=".$branch_id." ",$linkid) or $this->print_error(mysql_error($linkid));
        $data_array=array();
        while ($row=mysql_fetch_assoc($rslt)) {
            $data_array['permission_team'] = $row['permission_team'];
            $data_array['permission_content'] = $row['permission_content'];
            $data_array['permission_branch'] = $row['permission_branch'];
            $data_array['permission_group'] = $row['permission_group'];
            $data_array['permission_money'] = $row['permission_money'];
            $data_array['uid'] = $row['uid'];
        }
        return $data_array;
    }*/
    
    /**
     * Function to get branch details by branchid(bid)
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $get array
     */
    function get_branch_by_bid($get)
    {
        $linkid=$this->db_conn();
        if(!isset($get['branch_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined branch_id."));
        if(!isset($get['group_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined group_id."));
        $group_id = mysql_real_escape_string(urldecode($get['group_id']));
        $branch_id = mysql_real_escape_string(urldecode($get['branch_id']));
//        $member_id = mysql_real_escape_string(urldecode($get['member_id']));
        
        $rslt = mysql_query("select * from tbl_branch where branch_id='".$branch_id."' and group_id='".$group_id."' ",$linkid) or $this->print_error(mysql_error($linkid));
        if(mysql_errno($linkid)) {
            $rslt_arr=array("status"=>"error","response"=>mysql_error($linkid));
        }
        else
        {
            $data_array=array();
            while ($row=mysql_fetch_assoc($rslt)) {
                $data_array['branch_id'] = $row['branch_id'];
                $data_array['branch_name'] = $row['branch_name'];
                $data_array['branch_desc'] = $this->format_text($row['branch_desc']);
                $data_array['branch_addr_line_1'] = $this->format_text($row['branch_addr_line_1']);
                $data_array['branch_addr_line_2'] = $this->format_text($row['branch_addr_line_2']);
                $data_array['branch_addr_line_3'] = $this->format_text($row['branch_addr_line_3']);
                $data_array['branch_addr_city'] = $this->format_text($row['branch_addr_city']);
                $data_array['branch_addr_state'] = $this->format_text($row['branch_addr_state']);
                $data_array['branch_addr_country'] = $this->format_text($row['branch_addr_country']);
                $data_array['branch_addr_zip'] = $this->format_text($row['branch_addr_zip']);
                $data_array['branch_img_file_id'] = $this->format_text($row['branch_img_file_id']);
                $data_array['branch_billing_currency'] = $row['branch_billing_currency'];
                //$data_array['created_on'] = date("Y-m-d H:i:s",$row['created_on']);
            }
            $rslt_arr['status'] = "success";
            $rslt_arr['branch'] = $data_array;
            //$rslt_arr['member_permission'] = $this->get_permissions_bymid($linkid,$group_id,$branch_id);
        }
        return $rslt_arr;
    }
    
    /**
     * API to get group by groupid(gid) 
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $get array
     */
    function get_group_by_gid($get)
    {
        $linkid=$this->db_conn();
        if(!isset($get['uid'])) $this->print_error(array("status"=>"error","response"=>"Undefined uid."));
        if(!isset($get['group_id'])) $this->print_error(array("status"=>"error","response"=>"Undefined group_id."));
        
        $group_id = mysql_real_escape_string(urldecode($get['group_id']));
        $uid = mysql_real_escape_string(urldecode($get['uid']));
        
        $rslt = mysql_query("select * from tbl_groups where group_id='".$group_id."' and group_owner_uid='".$uid."' ",$linkid) or $this->print_error(mysql_error($linkid));
        if(mysql_errno($linkid)) {
            $rslt_arr=array("status"=>"error","response"=>mysql_error($linkid));
        }
        else
        {
            $data_array=array();
            while ($row=mysql_fetch_assoc($rslt))
            {
                    $data_array['group_id'] = $row['group_id'];
                    $data_array['group_type'] = $row['group_type'];
                    $data_array['group_name'] = $this->format_text($row['group_name']);
                    $data_array['group_description'] = $this->format_text($row['group_description']);
                    $data_array['group_owner_uid'] = $row['group_owner_uid'];
                    $data_array['group_img_file_id'] = $row['group_img_file_id'];
                    $data_array['common_prod_flag'] = $row['common_prod_flag'];
                    $data_array['created_on'] = date("Y-m-d H:i:s",$row['created_on']);
            }
            $rslt_arr['status'] = "success";
            $rslt_arr['group'] = $data_array;
            //$rslt_arr['member_permission'] = $this->get_permissions_bymid($linkid,$group_id,$branch_id);
        }
        return $rslt_arr;
    }
    
    /**
     * Get list profile by uid
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $uid int
     * @return type array
     */
    function get_list_profile($get) {
        $linkid=$this->db_conn();
        
        $uid = mysql_real_escape_string(urldecode($get['uid']));
        $module = mysql_real_escape_string(urldecode($get['module']));
        $member_id = mysql_real_escape_string(urldecode($get['member_id']));
        
        $rslt=mysql_query("SELECT * FROM `tbl_social_contacts` WHERE `uid`='$uid'",$linkid) or $this->print_error(mysql_error($linkid));
        
        if(mysql_affected_rows($linkid) > 0) {
            $i=0;
            while($row = mysql_fetch_assoc($rslt))
            {
                $result[$i]['uid']=$row['uid'];
                $result[$i]['gid']=$row['gid'];
                $result[$i]['c_uid']=$row['c_uid'];
                $result[$i]['c_gid']=$row['c_gid'];
                $result[$i]['name']=$row['name'];
                $result[$i]['pic_url']= htmlspecialchars_decode($row['pic_url']);
                $result[$i]['created_on']=$row['created_on'];
                ++$i;
            }
            $rslt_arr['status']="success";
            $rslt_arr['results']=$result;
            $rslt_arr['rows']=mysql_affected_rows($linkid);
        }
        else {
            $rslt_arr['status']="error";
            $rslt_arr['response']="No Records found.";
        }
        
        return $rslt_arr;
    }
        
    /**
     * Get list profile by uid
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $uid int
     * @return type array
     */
    function get_todo_list($get) {
        $linkid=$this->db_conn();
        $uid = mysql_real_escape_string(urldecode($get['uid']));
        $this->auth($uid,$linkid);
	
	$list_type = mysql_real_escape_string(urldecode($get['list_type']));
	
        $start = (!isset($get['start']) || $get['start'] == '')? 0 : mysql_real_escape_string(urldecode($get['start']));
        $limit=(!isset($get['limit']) || $get['limit'] == '')? 2 : mysql_real_escape_string(urldecode($get['limit']));
        
	//===============
	$cond ='';
	$time = time();
	if($list_type == 'upcoming') {
	    $cond .= ' AND remind_time > '.$time;
	}
	elseif($list_type == 'expired')	{
	    $cond .= ' AND remind_time < '.$time;
	}
	else {
	    $this->print_error("Invalid list type");
	}
	//===============
        $ttl_res=mysql_query("select count(*) as total from tbl_reminders r
                    JOIN m_content c on c.content_id=r.content_id
                    where r.`uid`='$uid' $cond order by r.remind_time ASC",$linkid) or $this->print_error(mysql_error($linkid));
        $ttl_row = mysql_fetch_assoc($ttl_res);
	$total_rows = $ttl_row['total'];

        if($total_rows > 0) {
	    
	    
	    $rslt = mysql_query("select c.content_id,r.reminder_id,r.remind_time,r.reminder_name,r.visibility from tbl_reminders r
                    join m_content c on c.content_id=r.content_id
                    where r.`uid`='$uid' $cond order by r.remind_time ASC 
		    limit $start,$limit",$linkid) or $this->print_error(mysql_error($linkid));
	    
            $i=0; $data_array=array();
	    while ($row=mysql_fetch_array($rslt)) {

		$data_array[$i]['sno'] = $start + $i;
		$data_array[$i]['content_id'] = $row['content_id'];
		$data_array[$i]['reminder_id'] = $row['reminder_id'];
		$data_array[$i]['reminder_name'] = $this->format_text($row['reminder_name']);
		$data_array[$i]['visibility'] = $row['visibility'];
		$data_array[$i]['remind_time'] = date("Y-m-d H:i:s",$row['remind_time']);
		$i++;
	    }
            $rslt_arr['status']="success";
            $rslt_arr['total_rows']=$total_rows;
            $rslt_arr['results']=$data_array;
	    
	    // pagination
	    $pagination = $this->get_pagination_vals($start,$limit,$total_rows);
	    if(isset($pagination['start'])) {
		$rslt_arr['start'] = $pagination['start'];
	    }
	    if(isset($pagination['prev'])) {
		$rslt_arr['prev'] = $pagination['prev'];
	    }
	    
        }
        else {
            $rslt_arr['status']="error";
	    $rslt_arr['total_rows']=$total_rows;
            $rslt_arr['response']="No Records found.";
        }
        
        return $rslt_arr;
    }
    
    function get_search_by_eid($get)
    {
	    $linkid=$this->db_conn();
	    $output=array(); $cond='';
	    $eid=mysql_real_escape_string($get['eid']);
	    $page = $lat=(!isset($get['page']))? '0' : mysql_real_escape_string(urldecode($get['page']))-1;
	    $limit = $lat=(!isset($get['limit']))? '35' : mysql_real_escape_string(urldecode($get['limit']));


	    //if($get['module'] == 'all') {
	    //money total
	    $sql="select sum(amount) as ttl_expense from m_money e
		where e.`visibility`=$eid limit $page,$limit";
	    $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
	    $row = mysql_fetch_array($rslt);
	    $output['expense_total'] = $row['ttl_expense'];

	    //reminders
	    //===============
	    $time = time();
	    $cond = ' AND r.remind_time > '.$time;
	    //===============
	    $sql = "select c.content_id,r.reminder_id,r.remind_time,r.reminder_name,r.visibility from tbl_reminders r
				join m_content c on c.content_id=r.content_id
				WHERE r.`visibility`='$eid' $cond order by r.remind_time ASC limit $page,$limit";
	    $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
	    $i=0;
	    while ($row=mysql_fetch_array($rslt)) {

		$data_array[$i]['content_id'] = $row['content_id'];
		$data_array[$i]['reminder_id'] = $row['reminder_id'];
		$data_array[$i]['reminder_name'] = $this->format_text($row['reminder_name']);
		$data_array[$i]['visibility'] = $row['visibility'];
		$data_array[$i]['remind_time'] = date("Y-m-d H:i:s",$row['remind_time']);
		$i++;
	    }
	    $output['reminders'] = $data_array;

	    //Notes
	    $sql="select c.content_id,n.note_id,n.note_text,n.visibility,date_format(from_unixtime(c.timestamp),'%b %d, %Y at %h:%i %p') as created_on,fl.file_url,fl.file_type,gp.name,gp.img_url
				from tbl_notes n
				join m_content c on c.content_id=n.content_id
				left join tbl_files fl on fl.file_id = n.file_id
				join generic_profile gp on gp.uid = c.uid
				where n.`visibility`='$eid' order by n.note_id desc limit $page,$limit";
	    $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
	    $i=0;$data_array=array();
	    while ($row=mysql_fetch_array($rslt)) {

		$data_array[$i]['content_id'] = $row['content_id'];
		$data_array[$i]['note_id'] = $row['note_id'];
		$data_array[$i]['note_text'] = $this->format_text($row['note_text']);
		$data_array[$i]['visibility'] = $row['visibility'];
		//$data_array[$i]['timestamp'] = date("Y-m-d H:i:s",$row['timestamp']);

		$data_array[$i]['author_name'] = $row['name'];
		$data_array[$i]['author_img_url'] = $row['img_url'];
		$data_array[$i]['author_created_on'] = $row['created_on'];

		//$size=300;$crop=TRUE;
		$data_array[$i]['file_url'] = $row['file_url'];//$this->image_serving_url($row['file_url'],$size,$crop,$row['file_type']);
		$data_array[$i]['media_type'] =  $this->get_file_type($row['file_type']);
		$i++;
	    }
	    $output['notes'] = $data_array;

	    // Shoppinglist
	    $sql = "select c.content_id,sl.shop_list_item_id,sl.item_name,sl.item_qty,sl.shopping_status,sl.units,c.visibility,c.timestamp
				from tbl_shoppinglist sl
				join m_content c on c.content_id=sl.content_id
				where sl.`visibility`='$eid' order by sl.shop_list_item_id desc limit $page,$limit";
	    $rslt = mysql_query($sql,$linkid) or $this->print_error(mysql_error($linkid));
	    $i=0;$data_array=array();
	    while ($row=mysql_fetch_array($rslt)) {

		$data_array[$i]['content_id'] = $row['content_id'];
		$data_array[$i]['shop_list_item_id'] = $row['shop_list_item_id'];
		$data_array[$i]['item_name'] = $this->format_text($row['item_name']);
		$data_array[$i]['visibility'] = $row['visibility'];
		$data_array[$i]['timestamp'] = date("Y-m-d H:i:s",$row['timestamp']);
		$data_array[$i]['item_qty'] = $this->format_text($row['item_qty']);
		$data_array[$i]['shopping_status'] = $this->format_text($row['shopping_status']);
		$data_array[$i]['units'] = $this->format_text($row['units']);
		$i++;
	    }
	    $output['shoppinglist'] = $data_array;
	//}
    }
    
}

$ob = new Search();
if(isset($get['content_style']))
{
    switch($get['content_style']) {
	
	case 'single_content': 

//			    if(!isset($get['content_id'])) $ob->print_error(array("status"=>"error","response"=>"Undefined content id."));
			    if(!isset($get['module'])) $ob->print_error(array("status"=>"error","response"=>"Undefined module.")); 
			    $output= $ob->get_single_content_info($get);
			    break;

	case 'list_content': 
//			    if(!isset($get['uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
			    if(!isset($get['module'])) $ob->print_error(array("status"=>"error","response"=>"Undefined module.")); 
			    //if(!isset($get['filter_type'])) print_error(array("status"=>"error","response"=>"Undefined filter type.")); 
			    $output= $ob->get_list_content_info($get); 
			    break;
			    
			    
			    
//	case 'user_profile':
//			    if(!isset($get['uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
//			    $output= $ob->_view_profile($get['uid']); 
//			    break;
	

//	case 'search_content': 
//			    if(!isset($get['requesting_uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
//			    if(!isset($get['query'])) $ob->print_error(array("status"=>"error","response"=>"Undefined query.")); 
//			    if(!isset($get['module'])) $ob->print_error(array("status"=>"error","response"=>"Undefined module."));
//			    $output= $ob->get_srch_content_info($get);
//			    break;

//	case 'tag_content': 
//			    if(!isset($get['requesting_uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
//			    if(!isset($get['tag'])) $ob->print_error(array("status"=>"error","response"=>"Undefined tag.")); 
//    //                        if(!isset($get['module'])) $ob->print_error(array("status"=>"error","response"=>"Undefined module."));
//			    $output= $ob->get_srch_tag_content_info($get); 
//			    break;

//	case 'list_tag_content': 
//			    if(!isset($get['requesting_uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
//    //                        if(!isset($get['tag'])) $ob->print_error(array("status"=>"error","response"=>"Undefined tag.")); 
//    //                        if(!isset($get['module'])) $ob->print_error(array("status"=>"error","response"=>"Undefined module."));
//			    $output= $ob->get_tag_content_info($get); 
//			    break;

//	case 'groups':
//			    //?action_object=groups&uid="+uid+"&module=list_groups
//			    if(!isset($get['module'])) $ob->print_error(array("status"=>"error","response"=>"Undefined module.")); 
//
//			    elseif($get['module'] == 'list_groups') {
//				$output = $ob->get_list_groups($get);
//			    }
//			    elseif($get['module'] == 'groups_info') {
//				$output = $ob->get_groups_info($get);
//			    }
//			    elseif($get['module'] == 'branch_member_by_groupid') {
//				$output = $ob->get_branch_member_by_groupid($get);
//			    }
//			    elseif($get['module'] == 'members_by_branchid') {
//				$output = $ob->get_members_by_branchid($get);
//			    }
//			    elseif($get['module'] == 'member_by_memid') {
//				$output = $ob->_member_info($get);
//			    }
//			    elseif($get['module'] == 'branch_by_bid') {
//				$output = $ob->get_branch_by_bid($get);
//			    }
//			    elseif($get['module'] == 'group_by_gid') {
//				$output = $ob->get_group_by_gid($get);
//			    }
//			    elseif($get['module'] == 'group_by_memid') {
//				$output = $ob->get_group_by_memid($get);
//			    }
//			    elseif($get['module'] == 'search_eid') {
//				$output = $ob->get_search_by_eid($get);
//			    }
//			    else {
//				$output= $ob->unknown();
//			    }
//			    break;

//	case 'list_profile':
//			    if(!isset($get['uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
//			    if(!isset($get['module'])) $ob->print_error(array("status"=>"error","response"=>"Undefined module.")); 
//			    $output= $ob->get_list_profile($get); 
//			    break;

//	case 'todo_list':
//			    if(!isset($get['uid'])) $ob->print_error(array("status"=>"error","response"=>"Undefined uid."));
//			    if(!isset($get['list_type'])) $ob->print_error(array("status"=>"error","response"=>"Undefined list type."));
//			    $output= $ob->get_todo_list($get); 
//			    break;

	default : $output= $ob->unknown();
	    break;
    }
}
else {
    $output= $ob->unknown();
}
#echo '<pre>';
//echo json_encode($output);
#echo '</pre>';


?>