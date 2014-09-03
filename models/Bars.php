<?php

class Bars 
{
	
	public function __construct($itemId) {
		$this->item = get_record_by_id('item', $itemId);
	}

	public function getPercentComplete() {
		$this->percentComplete = metadata($this->item, array('Scriptus','Percent Completed'));
		return $this->percentComplete;
	}




}

?>