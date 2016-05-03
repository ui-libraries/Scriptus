<?php


class Scriptus 
{

    public function __construct($itemId, $fileId) {
        $this->item = get_record_by_id('item', $itemId);
        $this->file = get_record_by_id('file', $fileId);
        $this->_isValid($this->item, $this->file, $itemId);  
    }

    public function getItem() {
    	return $this->item;
    }

    public function getFile() {
    	return $this->file;
    }

    public function getImageUrl() {
        $this->imageUrl = $this->file->getWebPath('original');
    	return $this->imageUrl;
    }

    public function getSmallerImageUrl() {
        $this->smallerImageUrl = $this->file->getWebPath('fullsize');
        return $this->smallerImageUrl;
    }

    public function getImageThumbnail() {
        
        $this->thumbnailUrl = $this->file->getWebPath('square_thumbnail');

        return $this->thumbnailUrl;
    }

    public function getTranscription() {
        $this->transcription = metadata($this->file, array('Scriptus', 'Transcription'));
    	return $this->transcription;
    }

    public function getTranslation() {
        $this->translation = metadata($this->file, array('Scriptus', 'translation'));
        return $this->translation;
    }

    public function getStatus() {
        $this->status = metadata($this->file, array('Scriptus', 'Status'));
        return $this->status;
    }

    public function getFileLink() {
        $this->file_link = link_to($this->item, 'show', metadata($this->item, array('Dublin Core', 'Title')));
    	return $this->file_link;
    }

    public function getItemLink() {
        $this->item_link = link_to($this->item, 'show', metadata($this->item, array('Dublin Core', 'Title'))); 
    	return $this->item_link;
    }

    public function getItemTitle() {
        $this->item_title = metadata($this->item, array('Dublin Core', 'Title'));
        return $this->item_title;
    }

    public function getFileTitle() {
        $this->file_title = metadata($this->file, array('Dublin Core', 'Title'));
        return $this->file_title;
    }

    public function getIdlLink() {
        $this->idl_link = metadata($this->file, array('Dublin Core', 'Source'));
    	return $this->idl_link;
    }

    public function getCollguideLink() {
        $this->collguide_link = metadata($this->item, array('Dublin Core', 'Relation'));        
    	return $this->collguide_link;
    }

    public function getCollectionTitle(){
        $collection = get_collection_for_item($this->item);
        $this->collection_name = metadata($collection, array('Dublin Core', 'Title')); 
        return $this->collection_name;
    }

    public function getCollectionLink() {
        set_current_record('item', $this->item);
        $this->collection_link = link_to_collection_for_item();
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
