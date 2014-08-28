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

        //Update transcription status to Started if any transcription was submitted
        if ($transcription != ''){
            $statusText = 'Started';
        }
        else {
            $statusText = '';
        }

        //update status based on text in transcription field
        $element = $file->getElement('Scriptus', 'Status');
        $file->deleteElementTextsByElementId(array($element->id));
        $file->addTextForElement($element, $statusText, false);

        $file->save();

        /*Update progress of item by counting the number of files associated with the item  that have been started*/

        //get the parent item
        $item = $file->getItem();

        //get all the parent item's files
        $files = $item->getFiles();

        //get the number of files associated with the item
        $fileLength = count($files);

        //use this variable to track the number of files that have been started
        $numberStarted = 0;

        //iterate through files, tracking the number started with $numberStarted.  
        foreach($files as $file){
            
            $status = $file->getElementTexts('Scriptus', 'Status');
            $status = $status[0];
            if (($status->text == 'Started') || ($status->text == 'Needs Review') || ($status->text == 'Completed')){
                $numberStarted++;
            }
        }

        //calculate percentage progress in item with numberStarted and fileLength
        $progress = round($numberStarted / $fileLength * 100);

        //update percent completed
        $element = $item->getElement('Scriptus', 'Percent Completed');
        $item->deleteElementTextsByElementId(array($element->id));
        $item->addTextForElement($element, $progress, false);



        //Scriptus does not use  Percent Needs Review and Percent Completed - only Percent Completed is tracked.  
        //However, the legacy information from Scripto in Percent Needs Review and Percent Completed has been left as is until one of the files in a given item have been saved.
        //On the front-end, both percent completed and percent needs review should be added together to get the total progress -- this ensures backward compatibility with Scripto, which used both fields.
        //But since Scriptus just uses one field to track progress, we want to update Percent Needs Review to be zero when we save so that when the two values are added, we make sure that what's in Percent Needs Review doesn't get double-counted (in Scriptus, what used to be in Percent Needs Review is now included in Percent Completed).
        $element = $item->getElement('Scriptus', 'Percent Needs Review');
        $item->deleteElementTextsByElementId(array($element->id));
        $item->addTextForElement($element, 0, false);

        //save item
        $item->save();    
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