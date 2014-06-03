<?php

class Scriptus_IndexController extends Omeka_Controller_AbstractActionController
{
        
    public function init()
    {        
        $this->_helper->db->setDefaultModelName('Scriptus');
    }
    
    public function transcribeAction()
    {
        $itemId = $this->getParam('id');
        
        echo "Transcribing files for item " . $itemId;
    }
    

}