<!--
    @Author Shivaraj<mrshivaraj123@gmail.com.in>_Oct_12_2014
-->
<!DOCTYPE html>
<html>
    <head>
	<title>Categories</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
	<h2>Categories</h2>
	<div align="right"><a href="/platform/dashboard/money-category-add">Add</a></div>
	
	<div>
<?php
		include "../blocks/paths.php";
		include $myclass_url;
		$ob =new Myactions();
		$linkid=$ob->db_conn();
		$rslt=mysql_query("SELECT * FROM m_categories ORDER BY category_name ASC",$linkid);
		if(mysql_num_rows($rslt)==0)
		{
		    echo ' No categories found.';
		}
		else
		{
?>
		    <table cellpadding="5" cellspacing="0">
			<tr>
			    <th>#</td>
			    <th>Category Name</th>
			    <th>Action</th>
			</tr>
<?php		    
		    $i=1;
		    while ($row = mysql_fetch_array($rslt)) 
		    {

?>
			
			    <tr>
				<td><?=$i;?></td>
				<td><?=$row['category_name'];?></td>
				<td><a href="/platform/dashboard/money-category-del/?id=<?=$row['category_id'];?>">Del</a></td>
			    </tr>
<?php
			$i++;
		    }
?>
		    </table>
<?php
		}
?>
	</div>
    </body>
</html>
