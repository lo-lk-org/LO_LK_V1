<?php
/**
 * @author Shivaraj <mrshivaraj123@gmail.com>_Sep_15_2014
 * @tutorial Root for api urls
 */
$get = ($_REQUEST);

require_once 'google/appengine/api/taskqueue/PushTask.php';
use \google\appengine\api\taskqueue\PushTask;

$output= '';
include "blocks/paths.php";
include $myclass_url;
include "includes/easyPagination.php";

$ob=new Myactions();

if(!isset($get['module']) ) { $output = $ob->unknown(); }
if(!isset($get['action'])) { $output = $ob->unknown(); }
switch($get['action'])
{
    case 'read': 
	    include 'search.php';
	break;
    case 'write':
	    include 'write.php';
	break;
    case 'delete':
	    include 'delete.php';
	break;
    case 'modify':
	    include 'modify.php';
	break;
    case 'analytics':
	    include 'analytics.php';
	break;
    default : $output = $ob->unknown();
	break;
}

echo json_encode($output);
?>
