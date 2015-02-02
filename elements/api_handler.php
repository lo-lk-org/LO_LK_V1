<?php
/**
 * @author Shivaraj <mrshivaraj123@gmail.com> Jan_21_2015
 * @tutorial Sample file
 */
header('Content-Type: application/json');

include_once "../blocks/paths.php";
include $myclass_url;


Class Api_handler extends Baseclass
{
    function __construct() {
	parent::__construct();
    }
    
    /**
     * Money add form
     * @author Shivaraj <mrshivaraj123@gmail.com>_Jan_22_2015
     * http://localhost:13080/api/
     *   action=write&module=money&content_style=single_content&uid=4523623456456456&timestamp=2014-11-23+08%3A55%3A17&lat=44.596&long=4.7893&visibility=pub
     *   &money_title=Gas+cylinder&money_amount=450&item_unit_price=450&item_units=ltr&item_qty=16&total_price=450&money_flow_direction=1&group_id=1&category_id=5
     */
    function money_add_form($inp)
    {
	/**
	 * Add money item
	 */
	$results=$this->getApiContent($this->site_url()."/api/", 'array', array("action"=>"write","module"=>"money","content_style"=>"single_content","uid"=>$inp['uid']
		,"timestamp"=>$this->cur_datetime(),"lat"=>$this->_get_lat(),"long"=>$this->_get_long(),"visibility"=>$inp['visibility'],"money_title"=>$inp['money_title']
	    ,"money_amount"=>$inp['money_amount'],"item_unit_price"=>$inp['item_unit_price'],"item_units"=>$inp['item_units'],"item_qty"=>$inp['item_qty']
	    ,"total_price"=>$inp['total_price'],"money_flow_direction"=>$inp['money_flow_direction'],'group_id'=>$inp['group_id'],'category_id'=>$inp['category_id']));

	if($results['status_code']==200)
	{
//	    echo '<pre>';print_r($results); die();
	    $resp_data=json_decode($results['response'],TRUE); // $resp_data=json_decode('{"status":"success","results":[{"money_id":43}]}',TRUE);
//	    echo '<pre>';print_r($resp_data); die();
	    if($resp_data['status']=='success')
	    {
		//{"status":"success","results":[{"money_id":43}]}
		$output['status']=$resp_data['status'];
		$output['response']="Money item created";
		$output['money_id']=$resp_data['results'][0]['money_id'];
	    }
	    else {
		$this->print_error(array("status"=>"error","response"=>$results['response']));
	    }
	}
	else
	{
	    $this->print_error(array("status"=>"error","response"=>$results['response']));
	}
	return $output;
    }
}

$ob=new api_handler();
$inp=$_REQUEST;
switch ($inp['action-type'])
{
    case 'money-add-form':
	$output=$inp;
//	$output['status']='success';
//	$output['message']="Money item created";
//	$output['money_id']=33009;
	$output=$ob->money_add_form($inp);
	break;
    default:
	$output=array();
	break;
}

echo json_encode($output);

die();
?>
