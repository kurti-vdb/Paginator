##Pagination over collections
Php paginator-class to manage collections.

###Usage

```php
	
	require_once("paginator.class.php");
	
	//Paginator-object aanmaken en opvullen
	$pages = new Paginator;
	$pages->items_per_page=8;
	$pages->items_total = 123;
	$pages->mid_range = 3;
	$pages->paginate();
	echo $pages->display_pages();

```
	
