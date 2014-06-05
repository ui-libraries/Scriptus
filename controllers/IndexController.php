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
        $itemId = $this->getParam('item');
        $fileId = $this->getParam('file');
        $item = get_record_by_id('item', $itemId);
        $file = get_record_by_id('file', $fileId);
        $filename = $file['filename'];
        set_current_record('item', $item);
        $imageUrl = $file->getWebPath('original');
        //echo "Transcribing files for item " . $itemId.'/'.$fileId;
        //echo '<img src="/omeka2/files/original/'.$filename.'"">';
        set_current_record('item', $item);       

        echo'   <body>
                    <div id="lab"></div>
                    <img id="ImageID" src="'.$imageUrl.'"/>
                </body>
                </html>
            ';

        

    }
    

}