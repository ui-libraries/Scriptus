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
           set_current_record('file', $file);   
           $imageUrl = $file->getWebPath('original');
           $transcription = metadata($file, array('Scriptus', 'Transcription'));

        }   

        $form = new Omeka_Form;         
        $form->setMethod('post');

        $transcriptionArea = new Zend_Form_Element_Textarea('transcribebox');  

        $transcriptionArea  ->setRequired(true)       
                            ->setValue($transcription)
                            ->setAttrib('cols', 35)
                            ->setAttrib('rows', 25);

        $form->addElement($transcriptionArea);

        $save = new Zend_Form_Element_Submit('save');
        $save ->setLabel('Save');
        $form->addElement($save);

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

        $data = array('Scriptus' => array('Transcription' => array(array('text' => $transcription, 'html' => false))));
        
        $file->addElementTextsByArray($data);
        
        //Replace text, not append
        $file->setReplaceElementTexts();
        
        //Save text
        $file->saveElementTexts();        
    }
    

}