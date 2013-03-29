##Pagination over collections
Php paginator-class to manage collections.

###Typical Usage

```php

<?php
	
	//Load our paginator-class
	require_once("paginator.class.php");
	
	//Make a database connection
	$connection = mysql_connect('url','password','');
	$database = mysql_select_db('Database_name',$connection);
	
	//Count your items
	$query ="SELECT COUNT(*) FROM table_name";
	$result = mysql_query($query, $connection);
	$r = mysql_fetch_row($result);
	
	//Create Paginator-object and fill it
	$pages = new Paginator;
	$pages->items_per_page=8;
	$pages->items_total = $r[0];
	$pages->mid_range = 3;
	$pages->paginate();
	echo $pages->display_pages();
	
	//Load data
	$query = "SELECT * FROM table_name ORDER BY column_name ASC $pages->limit";
	$result = mysql_query($query, $connection);
	$result = mysql_query($query);
	
	//Print data to screen
	$htmlcode ="";
	while ($r = mysql_fetch_array($result)){
		$htmlcode .= "<div class=\"someClass\" >" . $r['itemName'] . "</div>";
	}
	echo $htmlcode;
	
?>

```
	
	
###Gives you something like

```

		<< previous 1...678...44 next>>
```
