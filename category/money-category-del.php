<?php
/**
 * @author Shivaraj <mrshivaraj123@gmail.com>_Oct_12_2014
 * @tutorial Delete category
 */
?>
<!DOCTYPE html>
<html>
    <head>
	<title></title>
    </head>
    <body>
	<h2></h2>
	<div align="right"><a href="/platform/dashboard/money-category">View Categories</a></div>

<?php
	    include "../blocks/paths.php";
	    include $myclass_url;

	    $ob=new Myactions();
	    $linkid=$ob->db_conn();
	    $category_id=$_GET['id'];
	    $rslt=mysql_query("DELETE FROM m_categories WHERE category_id='".$category_id."'",$linkid);
	    if(mysql_affected_rows()>0)
	    {
		echo 'Category deleted successfully';
	    }
	    else
	    {
		echo 'No category found.';
	    }
?>
    </body>
</html>