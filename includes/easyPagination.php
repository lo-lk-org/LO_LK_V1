<?php
/**
 * This class provide an easy pagination of
 * array of elements.
 * we pass it the number of elements and current page to show,
 * and we have vars we need to paginate!
 */
class Paginacion 
{
	private $currentPage;
	private $totalRegs;
	private $maxRowsXPage;
	
	/**
	 *	__construct()
	 *  Params:
	 *	$maxRowsXPage: number of records to show
	 *  $currentPage: Page to show
	 */
	public function __construct($maxRowsXPage,$currentPage = 0){
		$this->maxRowsXPage = $maxRowsXPage;
		$this->currentPage = ($currentPage >= 0) ? $currentPage : 0;
	}
	/**
	 * Number of records of array
	 */
	 public function setTotalRegs($countArray){
		 $this->totalRegs = $countArray;
	}
	/**
	 * set Max number of records to show in a page
	 */
	public function setMaxRowsXPage($maxRows){
		$this->maxRowsXPage = $maxRows;
	}
	/**
	 * set current page
	 */
	 public function setCurrentPage($n_page){
		 $this->currentPage = $n_page;
	 }
	/**
	 * Return the last reg to paginate
	 *
	 */
	 public function getLastRow()
	 {
		 if ($this->getTotalPages() > ($this->currentPage + 1)){
			return ($this->getStartRow() + $this->maxRowsXPage);
		 } 
			return $this->getTotalRegs();
	 }
	 /**
	  * return current page
	  */
	 public function getCurrentPage(){
		 return $this->currentPage;
	 }
	 /** 
	  * Return number of first register to paginate
	  */
	 public function getStartRow(){
		 return ($this->currentPage < 0)? 0: ($this->currentPage * $this->maxRowsXPage);
	 }
	 /**
	  * Return total number of items of array
	  */
	 public function getTotalRegs(){
		 return $this->totalRegs;
	 }
	 /**
	  * Return total number of pages
	  */
	  public function getTotalPages(){
		  return ceil($this->getTotalRegs() / $this->maxRowsXPage);
	  }
}
?>