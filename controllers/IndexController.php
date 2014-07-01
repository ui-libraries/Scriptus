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

           $filename = $file['filename'];      
           set_current_record('item', $item);   
           $imageUrl = $file->getWebPath('original');
           $transcription = metadata($file, array('Scriptus', 'Transcription'));
           $dc_file_title = metadata($file, array('Dublin Core', 'Title') );
           $dc_item_link = link_to($item, 'show', metadata($item, array('Dublin Core', 'Title') )); 
           $idl_link = metadata($file, array('Dublin Core', 'Source'));
           $collguide_link = metadata($item, array('Dublin Core', 'Relation'));
           $collection_link = link_to_collection_for_item();            
           $this->view->dc_file_title = $dc_file_title;            
           $this->view->dc_item_link = $dc_item_link;
           $this->view->collection_link = $collection_link; 
           $this->view->idl_link = $idl_link; 
           $this->view->collguide_link = $collguide_link; 

        }   

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

        //$info = new Zend_Form_Element_Button('info');
        //$info   ->setLabel('More information on this item');
        //$info   ->setAttrib('class', 'btn btn-info');
        //$form   ->addElement($info);

        $this->view->form = $form;      

        //set js variable for image URL
        echo '  <script>                    
                    $("#ImageID").attr("src","'.$imageUrl.'");                    
                </script>'; 
    }

     public function saveAction() 
     {        

        $itemId = $this->getParam('item');
        $fileId = $this->getParam('file');
        
        $transcription = $_POST["transcription"];
        $file = get_record_by_id("file", $fileId);

        $scriptus_elements = array('Scriptus' => array('Transcription' => array(array('text' => $transcription, 'html' => false))));
        $dc_elements = array('Dublin Core');
        
        //add the two element sets we need
        $file->addElementTextsByArray($scriptus_elements);
        $file->addElementTextsByArray($dc_elements);

        //Replace text, not append
        $file->setReplaceElementTexts();

        //Save text
        $file->saveElementTexts();        
    }
    

}