<?php


class Scriptus extends Omeka_Record_AbstractRecord
{
    public $item;
    public $file;
    public $imageUrl;
    public $transcription;
    public $file_title;
    public $item_link;
    public $idl_link;
    public $collguide_link;
    public $collection_link;
    public $form;

    public function setRecords($itemId, $fileId) {
    	$this->item = get_record_by_id('item', $itemId);
    	$this->file = get_record_by_id('file', $fileId);    	
    }

    public function getItem() {
    	return $this->item;
    }

    public function getFile() {
    	return $this->file;
    }

    public function setMetadata($item, $file) {
    	$this->imageUrl = $file->getWebPath('original');
    	$this->transcription = metadata($file, array('Scriptus', 'Transcription'));
    	$this->file_title = metadata($file, array('Dublin Core', 'Title') );
    	$this->item_link = link_to($item, 'show', metadata($item, array('Dublin Core', 'Title') )); 
    	$this->idl_link = metadata($file, array('Dublin Core', 'Source'));
    	$this->collguide_link = metadata($item, array('Dublin Core', 'Relation'));
    	$this->collection_link = link_to_collection_for_item();
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

    public function isValid($item, $file, $itemId) {
    	if (!$item || !$file) {

    	    return false;

    	} elseif ($file->item_id != $itemId) {

    	    return false;          

    	} else {

    		return true;
    	}
    }

    public function buildForm() {
    	//create a new Omeka form
    	$this->form = new Omeka_Form;         
    	$this->form->setMethod('post'); 

    	$transcriptionArea = new Zend_Form_Element_Textarea('transcribebox');  

    	$transcriptionArea  ->setRequired(true)       
    	                    ->setValue($this->transcription)
    	                    ->setAttrib('class', 'col-xs-12')
    	                    ->setAttrib('class', 'form-control');
    	
    	$this->form->addElement($transcriptionArea);

    	$save = new Zend_Form_Element_Submit('save');
    	$save ->setLabel('Save');
    	$save->setAttrib('class', 'btn btn-primary');
    	$save->setAttrib('data-loading-text', "Saving...");
    	$save->setAttrib('id', 'save-button');
    	$this->form->addElement($save);

    	return $this->form;
    }

}
