<?php


class Scriptus extends Omeka_Record_AbstractRecord
{
    private $item;
    private $file;
    private $imageUrl;
    private $transcription;
    private $file_title;
    private $item_link;
    private $idl_link;
    private $collguide_link;
    private $collection_link;
    private $form;

    public function __construct($itemId, $fileId) {
        $this->item = get_record_by_id('item', $itemId);
        $this->file = get_record_by_id('file', $fileId);
        $this->_isValid($this->item, $this->file, $itemId);
        set_current_record('item', $this->item);
        $this->imageUrl = $this->file->getWebPath('original');
        $this->transcription = metadata($this->file, array('Scriptus', 'Transcription'));
        $this->file_title = metadata($this->file, array('Dublin Core', 'Title') );
        $this->item_link = link_to($this->item, 'show', metadata($this->item, array('Dublin Core', 'Title') )); 
        $this->idl_link = metadata($this->file, array('Dublin Core', 'Source'));
        $this->collguide_link = metadata($this->item, array('Dublin Core', 'Relation'));
        $this->collection_link = link_to_collection_for_item();
    }

    public function getItem() {
    	return $this->item;
    }

    public function getFile() {
    	return $this->file;
    }

    public function getImageUrl() {
    	return $this->imageUrl;
    }

    public function getTranscription() {
    	return $this->transcription;
    }

    public function getFileTitle() {
    	return $this->file_title;
    }

    public function getItemLink() {
    	return $this->item_link;
    }

    public function getIdlLink() {
    	return $this->idl_link;
    }

    public function getCollguideLink() {
    	return $this->collguide_link;
    }

    public function getCollectionLink() {
    	return $this->collection_link;
    }

    private function _isValid($item, $file, $itemId) {
    	if (!$item || !$file) {

    	    throw new Zend_Controller_Action_Exception('This page does not exist', 404); 

    	} elseif ($file->item_id != $itemId) {

    	    throw new Zend_Controller_Action_Exception('This page does not exist', 404);        

    	} else {

    		return;
    	}
    }

}
