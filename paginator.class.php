<?php
	
	/**
	*
	* Pagination over collections
	* @Author Kurt Van den Branden
	* @Version 1.0
	*
	**/
	
	class Paginator{
	
		/*** PROPERTIES ***/
		
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
		private $getValues;
			
		
		/*** CONSTRUCTORS ***/
		
		public function Paginator(){
			
			//Setters gebruiken omdat er andere properties mee worden gezet!!!!!!!!!!
			$this->setCurrentPage(1);
			$this->setMidRange(3);
			$this->setItemsPerPage(8);
			
			
		}
		
		/*** METHODS ***/
		
		/**
		* Hulpmethod om een paginator-object te printen naar een scherm,
		* Just a tool for debugging
		*
		*/
		public function printPaginator(){
			echo "CurrentPage is: " . $this->getCurrentPage() . "<br />";
			echo "ItemsPerPage is: " . $this->getItemsPerPage() . "<br />";
			echo "MaxPages is: " . $this->getMaxPages() . "<br />";
			echo "StartingIndex is: " . $this->getStartingindex() . "<br />";
			echo "EndingIndex is: " . $this->getEndingIndex() . "<br />";
			echo "MidRange is: " . $this->getMidRange() . "<br />";
			echo "LowPage is: " . $this->getLowPage() . "<br />";
			echo "HighPage is: " . $this->getHighPage() . "<br />";
		}
		
		/**
		* Method om een pagination menu te creëren en te tonen in een view
		*
		*/
		public function showPaginationMenu(){
		
			/**
			* Handle previous button
			*/
			$previousPage = $this->getCurrentPage()- 1;
			if($this->getCurrentPage() == 1 )
				//$this->navigationMenuContainer = "";
				$this->navigationMenuContainer = "<span class=\"inactive\">Previous</span>";
			else
				$this->navigationMenuContainer = "<a class=\"paginationButtons\" href=\"$_SERVER[PHP_SELF]?page=$previousPage\">Previous </a>";
			
			/**
			* Handle middle buttons
			*/
			$this->lowPage = $this->currentPage - floor($this->midRange/2);
            $this->highPage = $this->currentPage + floor($this->midRange/2);
            if($this->lowPage <= 0){
                $this->highPage += abs($this->lowPage)+1;
                $this->lowPage = 1;
            }
            if($this->highPage > $this->maxPages){
                $this->lowPage -= $this->highPage-$this->maxPages;
                $this->highPage = $this->maxPages;
            }
            			
			$range = range($this->getLowPage(), $this->getHighPage());
			for ($i = 1; $i <= $this->getMaxPages(); $i++){
					
					if($range[0] > 2 && $i == $range[0])
						$this->navigationMenuContainer .= " ... ";
					
					if ($i == 1 || $i == $this->getMaxPages() || in_array($i, $range)){
						if($i == $this->getCurrentPage())
							$this->navigationMenuContainer .= "<a class=\"current\" >$i</a>"; //No href
						else
							$this->navigationMenuContainer .= "<a class=\"paginationButtons\" href=\"$_SERVER[PHP_SELF]?page=$i\">$i</a>";
					}
					
					if ($range[$this->getMidRange()-1] < ($this->getMaxPages()-1) && $i == $range[$this->getMidRange()-1])
						$this->navigationMenuContainer .= " ... ";
			}
			
			/**
			* Handle next button
			*/
			$nextPage= $this->getCurrentPage() + 1;
			if ($this->getCurrentPage() != $this->getMaxPages())
				$this->navigationMenuContainer .= "<a class=\"paginationButtons\" href=\"$_SERVER[PHP_SELF]?page=$nextPage\">Next </a>";
			else
				$this->navigationMenuContainer .= "<span class=\"inactive\">Next </span>";
			
			//Print menu to screen	
			echo $this->getNavigationMenuContainer();
				
		}
		
		
		/*** GETTERS ***/
		
		public function getCurrentPage(){
        	return $this->currentPage;
    	}
		public function getItemsPerPage(){
			return $this->itemsPerPage;
		}
		public function getMaxItems(){
			return $this->maxItems;
		}
		public function getMaxPages(){
			return $this->maxPages;
		}
		public function getStartingIndex(){
			return $this->startingIndex;
		}
		public function getEndingIndex(){
			return $this->endingIndex;
		}
		public function getMidRange(){
			return $this->midRange;
		}
		public function getLowPage(){
			return $this->lowPage;
		}
		public function getHighPage(){
			return $this->highPage;
		}
		public function getNavigationMenuContainer(){
			return $this->navigationMenuContainer;
		}
		public function getGetValues(){
			return $this->getValues;
		}
		
		/*** SETTERS ***/
		
		public function setCurrentPage($wantedPage){
			
			/**
			* CurrentPage zetten 
			*/
			if ($wantedPage >= $this->getMaxPages())	
				$this->currentPage = $this->getMaxPages(); //Out of bound exception opvangen
			if ($wantedPage <= 0)	
				$this->currentPage = 1; //De opgevraagde pagina mag niet negatief of nul zijn
			else 
				$this->currentPage = $wantedPage; //Everything ok, set the page
			
			/**
			* De start- en endingIndexen zetten, dit zijn arrayindexen [0-?] !!!!
			*/
	    	$this->startingIndex = $this->itemsPerPage * ($this->currentPage - 1);
	    	$this->endingIndex = $this->startingIndex + $this->itemsPerPage;
	    	if ($this->endingIndex > $this->getMaxItems()) //Corrigeer eveneens index out of bound exceptions
	      		$this->endingIndex = $this->getMaxItems();
			if ($this->startingIndex < 0) //Corrigeer eveneens index out of bound exceptions
	    		$this->startingIndex = 0;
			
			/*
			* De high en lowbuttons zetten
			*/	
			$this->setLowPage();
			$this->setHighPage();
		}
		
		public function setItemsPerPage($itemsPerPage) {
			$this->itemsPerPage = $itemsPerPage;
		}
		
		public function setMaxItems($maxItems){
			$this->maxItems = $maxItems;
			$this->setMaxPages();
		}
		
		public function setMaxPages(){
			$this->maxPages= ceil($this->getMaxItems()/$this->getItemsPerPage());
		}
		
		public function setMidRange($midRange) {
			if ($midRange <= 0)
				$midRange = 1; 
			//We dwingen enkel oneven midRanges af zodat de focus links en rechts symmetrisch wordt uitgelijnd, louter voor het visuele   
			if ($midRange % 2 == 0 )
				$this->midRange = $midRange -1 ; // INCORRECT: 	<<previous 1...4567...9 next>>
			if ($midRange % 2 == 1)
				$this->midRange = $midRange; // CORRECT:  	<<previous 1...45[6]78...9 next>>
		}
		
		public function setLowPage(){
			if(ceil($this->getCurrentPage() - ($this->midRange/2)) > 0 )
				$this->lowPage = ceil($this->getCurrentPage() - ($this->midRange/2));
			else
				$this->lowPage = 1;	
		}
		
		public function setHighPage(){
			if(floor($this->getCurrentPage() + ($this->midRange/2)) < $this->getMaxPages())
				$this->highPage = floor($this->getCurrentPage() + ($this->midRange/2));
			else
				$this->highPage = $this->getMaxPages(); 
		}
		
		public function setGetValues($getValues){
			$this->getValues = $getValues;
		}
		
	}


?>