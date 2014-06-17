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
           $transcription = preg_replace('#[^0-9a-z .,\'"-/]#i', '', $transcription);  
                      
        }         

        //set js variable for image URL
        echo '  <script>
                    $("#transcribe-box").append("'.$transcription.'");
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