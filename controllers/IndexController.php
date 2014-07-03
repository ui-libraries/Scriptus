<?php

class Scriptus_IndexController extends Omeka_Controller_AbstractActionController
{
    /*    
    public function init()
    {        
        
        $this->_helper->db->setDefaultModelName('Scriptus');
    }
    */
    
    public function transcribeAction()
    {
        $transcription = '';

        $itemId = $this->getParam('item');
        $fileId = $this->getParam('file');
        
        $item = get_record_by_id('item', $itemId);
        $file = get_record_by_id('file', $fileId);       

        if (!$item || !$file) {

            throw new Zend_Controller_Action_Exception('This page does not exist', 404);  

        } elseif ($file->item_id != $itemId) {

            throw new Zend_Controller_Action_Exception('This page does not exist', 404);             

        } else {
            
           set_current_record('item', $item); 

           //get the path to the file image  
           $imageUrl = $file->getWebPath('original');           

           //get the relevant metadata
           $transcription = metadata($file, array('Scriptus', 'Transcription'));
           $dc_file_title = metadata($file, array('Dublin Core', 'Title') );
           $dc_item_link = link_to($item, 'show', metadata($item, array('Dublin Core', 'Title') )); 
           $idl_link = metadata($file, array('Dublin Core', 'Source'));
           $collguide_link = metadata($item, array('Dublin Core', 'Relation'));
           $collection_link = link_to_collection_for_item();

           //send everything to the view
           $this->view->imageUrl = $imageUrl;            
           $this->view->dc_file_title = $dc_file_title;            
           $this->view->dc_item_link = $dc_item_link;
           $this->view->collection_link = $collection_link; 
           $this->view->idl_link = $idl_link; 
           $this->view->collguide_link = $collguide_link; 

        }   

        //create a new Omeka form
        $form = new Omeka_Form;         
        $form->setMethod('post'); 

        $transcriptionArea = new Zend_Form_Element_Textarea('transcribebox');  

        $transcriptionArea  ->setRequired(true)       
                            ->setValue($transcription)
                            ->setAttrib('cols', 35)
                            ->setAttrib('rows', 25)
                            ->setAttrib('class', 'col-xs-12')
                            ->setAttrib('class', 'form-control');
       
        $form->addElement($transcriptionArea);

        $save = new Zend_Form_Element_Submit('save');
        $save ->setLabel('Save');
        $save->setAttrib('class', 'btn btn-primary');
        $save->setAttrib('data-loading-text', "Saving...");
        $save->setAttrib('id', 'save-button');
        $form->addElement($save);

        $this->view->form = $form;         
    }

     public function saveAction() 
     {        

        //get the record based on URL param
        $fileId = $this->getParam('file');
        $file = get_record_by_id("file", $fileId);

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