<?php

class Scriptus_IndexController extends Omeka_Controller_AbstractActionController
{
        
    public function transcribeAction()
    {

        //get the item and file ids
        $itemId = $this->getParam('item');
        $fileId = $this->getParam('file');

        $scriptus = new Scriptus($itemId, $fileId);
        $bars = new Bars($itemId);

        //set the transcription for the page
        $this->transcription = $scriptus->getTranscription(); 

        //send everything to the view
        $this->view->imageUrl = $scriptus->getImageUrl();                           
        $this->view->file_title = $scriptus->getFileTitle();            
        $this->view->item_link = $scriptus->getItemLink();
        $this->view->collection_link = $scriptus->getCollectionLink(); 
        $this->view->idl_link = $scriptus->getIdlLink(); 
        $this->view->collguide_link = $scriptus->getCollguideLink();  
        $this->view->percent_complete = $bars->getPercentComplete();         

        $this->view->form = $this->_getForm();

        //set up pagination array
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

    private function _getForm() {
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