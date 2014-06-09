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
        //$itemId = $this->getParam('item');
        $fileId = $this->getParam('file');
        //$item = get_record_by_id('item', $itemId);
        $file = get_record_by_id('file', $fileId);
        $filename = $file['filename'];      
        set_current_record('file', $file);   
        $imageUrl = $file->getWebPath('original');   
        $metadata = metadata($file, array('Scripto', 'Transcription'));   

        //set js variable for image URL
        echo '  <script>
                    $("#transcribe-box").append("'.$metadata.'");
                    $("#ImageID").attr("src","'.$imageUrl.'");
                </script>'; 
    }
    

}