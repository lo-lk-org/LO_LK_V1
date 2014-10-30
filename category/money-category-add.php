<?php
/**
 * @author Shivaraj <mrshivaraj123@gmail.com>_Oct_12_2014
 * @tutorial Add category
 */
?>
<!DOCTYPE html>
<html>
    <head>
	<title>Add Category</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
	<h2>Add Category</h2>
	<div align="right"><a href="/platform/dashboard/money-category">View Categories</a></div>
	
	<div>
	    <div>
<?php
		if(isset($_POST['category_name']) && $_POST['category_name']!='')
		{
		    include "../blocks/paths.php";
		    include $myclass_url;
		    $ob=new Myactions();
		    $linkid=$ob->db_conn();
		    $category_name=$_POST['category_name'];
		    $rslt=mysql_query("SELECT * FROM m_categories WHERE category_name='".$category_name."'",$linkid);
		    if(mysql_num_rows($rslt)==0)
		    {
			mysql_query("INSERT INTO m_categories(`category_name`) VALUES('".$category_name."')",$linkid);
			$insert_id=mysql_insert_id($linkid);
			mysql_query("UPDATE m_categories SET category_id='".$insert_id."' WHERE sno='".$insert_id."'",$linkid);
			echo 'Category Created successfully';
		    }
		    else
		    {
			echo 'Categories already found.';
			
		    }
		}
		    
?>
	    </div>
	    <form onsubmit="return add_category(this);" method="post">
		<input type="text" size="20" name="category_name" id="category_name" value="">
		<input type="submit" value="Add">
	    </form>
	</div>
	</body>
	<script type="text/javascript">
	    function add_category(elt)
	    {
		var category_name=document.getElementById("category_name");
		if(category_name.value=='') {
		    alert("Please enter category name");
		    return false;
		}
		return true;
	    }
	</script>
</html>