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
	
	//Get current page from url
	if (isset ($_GET['page']))
		$page = (int)$_GET['page'];
	else $page = 1;
	
	//Create Paginator-object and fill it
	$pages = new Paginator;
	$pages->setItemsPerPage=8;
	$pages->setMaxItems($r[0]);
	$pages->setMidRange = 3;
	$pages->setCurrentpage($page);
	
	//Print navigator to screen
	echo $pages->showPaginationMenu();
	
	//Load data
	$query = "SELECT * FROM table_name ORDER BY column_name DESC LIMIT " . $pages->getStartingIndex() . ", " . $pages->getItemsPerPage() . "";
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
	
	private $currentPage;
		private $itemsPerPage;
		private $maxItems;
		private $maxPages;
		private $startingIndex; //indexnummer 
		private $endingIndex; //indexnummer
		private $midRange; // <<previous 1 ... midrange(aantal buttons) ... 99 next>>
		private $lowPage; // = 4	<<previous 1...45678...99 next>>	
		private $highPage;// = 8	<<previous 1...45678...99 next>>	
		private $navigationMenuContainer;
	
###Gives you something like

```

		<< previous 1...678...13 next >>
		
				itemName 1
				itemName 2
				itemName 3
				itemName 4
				itemName 5
				itemName 6
				itemName 7
				itemName 8
				
				
```
