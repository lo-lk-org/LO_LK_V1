<?php 

//require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
//use google\appengine\api\cloud_storage\CloudStorageTools;

/**
 * General Actions
 */
 class Myactions {
     function __construct() {     }
     
     /**
      * GET API Data
      * @param type $url string
      * @author shivaraj <mrshivaraj123@gmail.com>
      * @param type $outtype string
      * @param type $post Array
      * @return type array
      */
    function getApiContent($url,$outtype,$post='') {
            $content ='';
            if($post != '') {
                $post = http_build_query($post);
                $content = array(
                    "http" => array(
                        "method"=>"POST"
                        ,"header"=> "custom-header: if-any\r\n" .
                                    "custom-header-two: custome-value-2\r\n"
                        ,"content" => $post
                    )
                );
                $content = stream_context_create($content);
                $rdata = file_get_contents($url,false,$content);
            }
            else {
                $rdata = file_get_contents($url,false);
            }
            
            if($outtype=='json') {
                return json_decode($rdata,true);
            }
            else {
                return $rdata;
            }
    }
    
    /**
     * Stopping words will be removed from array
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $q_arr array
     * @return type array
     * @example "Like: name,is,this,"
     */
    function skip_stopwords($q_arr) {
        $r_arr= array();
        foreach ($q_arr as $i=>$query) {
            if($this->chk_stop_word($query)) {
                //skip the word
            }
            else {
                $r_arr[]=$query;
            }
        }
        return $r_arr;
    }
    
    /**
     * Checks stoping words
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $query string
     * @return boolean
     */
    function chk_stop_word($query) {
            $stopwords = array("a", "about", "above", "above", "across", "after", "afterwards", "again",
            "against", "all", "almost", "alone", "along", "already",
            "also","although","always","am","among", "amongst", "amoungst", "amount", "an", "and",
            "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around",
            "as", "at", "back","be","became", "because","become","becomes", "becoming", "been",
            "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond",
            "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could",
            "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg",
            "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever",
            "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find",
            "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front",
            "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here",
            "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how",
            "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself",
            "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me",
            "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move",
            "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next",
            "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off",
            "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours",
            "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re",
            "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should",
            "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone",
            "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten",
            "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter",
            "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this",
            "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too",
            "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon",
            "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence",
            "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon",
            "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom",
            "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours",
            "yourself", "yourselves", "the");
            // check these words are in a
            if(in_array($query,$stopwords)) {
                return true;
            }
            else {
                return false;
            }
    }
    
    /**
     * Default error message
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @return type array
     */
    function unknown() 
    {
        return array("status"=>"error","response"=>"Unknown url");
    }
    
    /**
     * Establish the database connection
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @return type int or array
     */
    function db_conn() {
        include "blocks/paths.php";
        include $db_file_url;
        
        if(mysql_error($linkid)) {
            echo json_encode(array("status"=>"error","response"=>mysql_error($linkid)));die();
        }
        else {
            return $linkid;
        }
    }
    
    /**
     * Function to check uid exits or not
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $uid string
     * @param type $linkid int
     * @return boolean Bool/error
     */
    function auth($uid,$linkid) {
		$rslt=mysql_query("SELECT * FROM `generic_profile` WHERE `uid`='$uid'",$linkid) or $this->print_error(mysql_error($linkid));
	        
	        if(mysql_affected_rows($linkid) > 0) {
		    return TRUE;
		}
		else {
		    $this->print_error("Invalid uid login");
		}
    }
    
}

/**
 * Base Class
 */
class Baseclass extends Myactions
{
    function __construct() {
        parent::__construct();
    }
    
    /**
     * Function to check weather this uid already exists
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $uid user uid
     * @return boolean true/false
     */
    function check_uid($uid) {
        $linkid=$this->db_conn();
        $rslt=mysql_query('select * from `oneapp_db`.`generic_profile` where uid="'.$uid.'" limit 1',$linkid);

        $rslt_arr=mysql_fetch_row($rslt);
        if(mysql_num_rows($rslt)) {
            return TRUE; //uid exits
        }
        else { 
            return FALSE;//uid doen't exits
        }

    }
    
    /**
     * Print all kind of errors of type array and strin
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type array or exit execution
     */
    function print_error($msg,$status="error") {
        if(is_array($msg)) {
            $msg['status']=$status;
            echo json_encode($msg);
        }
        else {
            echo json_encode(array("status"=>$status,"response"=>$msg));
        }
        die();
    }
    
    /**
     * Function to print both array or string output with status message
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $msg array/string
     * @param type $status string
     */
    function print_msg($msg,$status='success') {
        $this->print_error($msg,$status);
    }
    
    /**
     * Format the text
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $string string
     * @return type string
     */
    function format_text($string) {
        return htmlspecialchars(strip_tags(nl2br($string)));
    }
    
    /**
     * Function to return random number with given length
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $length int
     * @return type int
     */
    function randomNumber($length) {
		$result = '';
		for($i = 0; $i < $length; $i++) {
		    $result .= mt_rand(0, 9);
		}
		return $result;
    }
    
    /**
     * Function to return mysql pagination values - start,prev
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $start int
     * @param type $limit int
     * @param type $total_rows int
     * @return type array
     */
    function get_pagination_vals($start,$limit,$total_rows)
    {
	$pagination=array();
	if($total_rows > $limit)
	{
	    $next_count = $start + $limit;
	    if($start  == 0 ) {
		$pagination['start'] = $limit;
		//$rslt_arr['prev']=0;
	    }
	    elseif($start > 0 && $start < $total_rows && $next_count < $total_rows ) {
		$pagination['start'] = $start + $limit;
		$pagination['prev'] = $start - $limit;
	    }
	    elseif($next_count >= $total_rows) {
		$pagination['prev'] = $start - $limit;
	    }
	}
	return $pagination;
    }
    
    /**
     * General database table insert query
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $tablename Name of table
     * @param type $in_array array field & value list
     * @example path $out_array = array("file_version_id"=>$file_version,"timestamp"=>$utc_time);
			$tablename = "tbl_files";
			$inrs = $this->insert_qry($tablename,$out_array);
			if($inrs['status']=="success") {}
     * @return type Array
     */
    function insert_qry($tablename,$in_array)
    {
	$linkid = $this->db_conn();
	foreach($in_array as $field_name=>$value)
	{
	    $field_array[] = '`'.$field_name.'`';
	    $value_array[] = '"'.$value.'"';
	}
	//rtrim($value_array,",")
	$in_qry = "insert into ".$tablename."(".implode(",",$field_array).") values (".implode(',',$value_array).")";
	
	// Insert qry
	mysql_query($in_qry,$linkid) or $this->print_error(mysql_error($linkid).'\n'.$in_qry);
	if(mysql_errno())
	{
	    
	    return array("status"=>"error","response"=>$in_qry.' '.  mysql_error($linkid));
	}
	else
	{
	    return array("status"=>"success","insert_id"=>mysql_insert_id($linkid));
	}
    }
    
    /**
     * General table update query
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $tablename Name of table
     * @param type $data_array Input field value array
     * @param type $where_array Where field value array
     * @example path $data_arr = array("file_id"=>$file_id);
	    $where_arr = array("sno"=>$id);
	    $uprs = $this->update_qry($tablename,$data_arr,$where_arr);
	    if($uprs['status']=="success") {}
     * @return Array status,msg,affected_rows
     */
    function update_qry($tablename,$data_array,$where_array)
    {
	$linkid = $this->db_conn();
	$set_qry = $where_qry = array();
	foreach($data_array as $field_name=>$value)
	{
	    $set_qry[] = '`'.$field_name.'`'."=".'"'.$value.'"';
	}
	
	foreach($where_array as $field_name=>$value)
	{
	    $where_qry[] = '`'.$field_name.'`'."=".'"'.$value.'"';
	}
	$in_qry = "update ".$tablename." SET ".implode(",",$set_qry)." WHERE ".implode(" and ",$where_qry)." ";
	
	// Update qry
	mysql_query($in_qry,$linkid) or $this->print_error(mysql_error($linkid).'\n'.$in_qry);
	if(mysql_errno())
	{
	    return array("status"=>"error","response"=>$in_qry.' '.  mysql_error($linkid));
	}
	else
	{
	    return array("status"=>"success","affected_rows"=> mysql_affected_rows($linkid));
	}
    }
    
    /**
     * General table update query
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $tablename Name of table
     * @param type $data_array Input field value array
     * @param type $where_array Where field value array
     * @example path $where_arr = array("email"=>$email);
			$select = $this->select_qry($tablename,$where_arr,"email");
			if($select['status'] == 'success') { }
     * @return Array status,msg,affected_rows
     */
    function select_qry($tablename,$where_array,$fileds='')
    {
	$linkid = $this->db_conn();
	$set_qry = $where_qry = array();
	/*foreach($data_array as $field_name=>$value)
	{
	    $set_qry[] = '`'.$field_name.'`'."=".'"'.$value.'"';
	}*/
	
	foreach($where_array as $field_name=>$value)
	{
	    $where_qry[] = '`'.$field_name.'`'."=".'"'.$value.'"';
	}
	
	if($fileds == '')
	{
	    $fileds = '*';
	}
	$in_qry = "SELECT ".$fileds." FROM ".$tablename." WHERE ".implode(" and ",$where_qry)." ";
	
	// Update qry
	$results = mysql_query($in_qry,$linkid) or $this->print_error(mysql_error($linkid).'\n'.$in_qry);
	if(mysql_errno())
	{
	    return array("status"=>"error","response"=>$in_qry.' '.  mysql_error($linkid));
	}
	else
	{
	    if(mysql_num_rows($results) <= 0)
		return array("status"=>"error","response"=> "No results found.");
	    else
		return array("status"=>"success","num_rows"=> mysql_num_rows($results));
	}
    }
    
    /**
     * Function to identify file type like image,media,etc
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $file_type_ext file_type
     * @return type string
     */
    function get_file_type($file_type_ext)
    {
	$arr_file_type = explode('-',$file_type_ext);
	/*switch ($file_type_ext) {
	    case 'image-jpg':
	    case 'image-jepg':
	    case 'image-gif':
	    case 'image-png':
	    case 'image-bmp': $media_type = 'image'; break;
	    case 'file/avi':
	    case 'file/vob':
	    case 'file/dat': $media_type = 'video'; break;
	    case 'file/mp3':
	    case 'file/ogg': $media_type = 'audio'; break;
	}*/
	$media_type = $arr_file_type[0];
	return $media_type;
    }
    
    /**
     * Function to return serving url
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $object_image_file string
     * @param type $size int
     * @param type $crop bool
     * @param type $file_type string
     * @return type string
     */
    /*function image_serving_url($object_image_file,$size=400,$crop=TRUE,$file_type='')
    {
	
	if( $this->get_file_type($file_type) == 'image' )
	{
	    //$object_image_file = 'gs://my-bucket/myfile.png';
		    $object_image_url = CloudStorageTools::getImageServingUrl($this->replace($object_image_file),
						    array('crop' => $crop,'size' => $size) );
	}
	else {
	    $object_image_url=$object_image_file;
	}
	return $object_image_url;//header('Location:' .$object_image_url);
    }*/
    
//    function _is_imagetype($file_type)
//    {
//	$mname = explode('/',$file_type);
//	if($mname[0] == 'image')
//	    return true;
//	else
//	    return false;
//    }

    /**
     * 
     * @author shivaraj <mrshivaraj123@gmail.com>
     * @param type $object_image_file
     * @param type $search
     * @param type $replace 
     * @return type string
     */
    function replace($object_image_file,$search='http://',$replace='gs://')
    {
	return str_replace($search, $replace, $object_image_file);
    }
    
    /**
     * Function to return quarter month start date
     * @author shivaraj <mrshivaraj123@gmail.com>_Oct_17_2014
     * @return date
     */
    function this_quarter()
    {
	$current_month = date('m');
	$current_year = date('Y');

	if($current_month>=1 && $current_month<=3)
	{
	  $start_date = strtotime('1-October-'.($current_year-1));  // timestamp or 1-October Last Year 12:00:00 AM
	  $end_date = strtotime('1-Janauary-'.$current_year);  // // timestamp or 1-January  12:00:00 AM means end of 31 December Last year
	}
	else if($current_month>=4 && $current_month<=6)
	{
	  $start_date = strtotime('1-January-'.$current_year);  // timestamp or 1-Janauray 12:00:00 AM
	  $end_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM means end of 31 March
	}
	else  if($current_month>=7 && $current_month<=9)
	{
	  $start_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM
	  $end_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM means end of 30 June
	}
	else  if($current_month>=10 && $current_month<=12)
	{
	  $start_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM
	  $end_date = strtotime('1-October-'.$current_year);  // timestamp or 1-October 12:00:00 AM means end of 30 September
	}
	return $start_date;
    }
    
    /**
     * Function to validate default start & end time
     * @author shivaraj <mrshivaraj123@gmail.com>_Oct_18_2014
     * @param date $date
     * @param string $dt_type
     * @return date
     */
    function validate_datetime($datetime,$dt_type='from')
    {
	$ar_dt=explode(" ",trim($datetime) );
	$date=$ar_dt[0];
	$time=@$ar_dt[1];
	if($date=='') {
	    if($dt_type=='to')
		$date=date("Y-m-d",time());
	    else
		$date=date("Y-m-d",strtotime('-2 year'));
	}
	if($time=='') {
	    if($dt_type=='to')
		$time='23:59:59';
	    else
		$time='00:00:00';
	}
	return $date.' '.$time;
    }
}
?>