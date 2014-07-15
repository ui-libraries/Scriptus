<?php

class Scriptus_IndexController extends Omeka_Controller_AbstractActionController
{
        
    public function init()
    {        
        
        $this->_helper->db->setDefaultModelName('Scriptus');
    }
    
    
    public function transcribeAction()
    {

        $scriptus = new Scriptus();

        $itemId = $this->getParam('item');
        $fileId = $this->getParam('file');

        $scriptus->setRecords($itemId, $fileId);

        $item = $scriptus->getItem();        
        $file = $scriptus->getFile(); 

        if ($scriptus->isValid($item, $file, $itemId) == false) {
            throw new Zend_Controller_Action_Exception('This page does not exist', 404);   
        } 
            
        set_current_record('item', $item); 
        $scriptus->setMetadata($item, $file); 

        $this->view->imageUrl = $scriptus->getImageUrl(); 
        $this->view->transcription = $scriptus->getTranscription();           
        $this->view->file_title = $scriptus->getFileTitle();            
        $this->view->item_link = $scriptus->getItemLink();
        $this->view->collection_link = $scriptus->getCollectionLink(); 
        $this->view->idl_link = $scriptus->getIdlLink(); 
        $this->view->collguide_link = $scriptus->getCollguideLink();           

        $this->view->form = $scriptus->buildForm();   

        $paginationUrls = array();  
        $files = get_records('file', array('item_id'=>$itemId), 999);

            foreach ($files as $file) {
                
                $fileID = $file->id;

                if (isset($current)) {
                    $paginationUrls['next'] = WEB_ROOT . '/transcribe/' . $itemId . '/' . $fileID;
                    break;
                }

                if ($fileID == $fileId) {
                    $current = true;
                } else {
                    $paginationUrls['prev'] = WEB_ROOT . '/transcribe/' . $itemId . '/' . $fileID;
                }

            }
            
        $this->view->paginationUrls = $paginationUrls; 

    }

     public function saveAction() 
     {        
        //get the record based on URL param
        $fileId = $this->getParam('file');
        $file = get_record_by_id('file', $fileId);

        //get the posted transcription data       
        $request = new Zend_Controller_Request_Http();
        $transcription = $request->getPost('transcription');        

        //save the new transcription data
        $element = $file->getElement('Scriptus', 'Transcription');
        $file->deleteElementTextsByElementId(array($element->id));
        $file->addTextForElement($element, $transcription, false);
    
        $file->save();    
    }
    

}