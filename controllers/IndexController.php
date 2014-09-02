<?php

class Scriptus_IndexController extends Omeka_Controller_AbstractActionController
{
        
    public function transcribeAction()
    {

        $itemId = $this->getParam('item');
        $fileId = $this->getParam('file');

        $scriptus = new Scriptus($itemId, $fileId);

        $this->transcription = $scriptus->getTranscription(); 

        $this->view->imageUrl = $scriptus->getImageUrl();                           
        $this->view->file_title = $scriptus->getFileTitle();            
        $this->view->item_link = $scriptus->getItemLink();
        $this->view->collection_link = $scriptus->getCollectionLink(); 
        $this->view->idl_link = $scriptus->getIdlLink(); 
        $this->view->collguide_link = $scriptus->getCollguideLink();           

        $this->view->form = $this->_buildForm();

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

        //check if there was old transcription data (this is used for analytics purposes)
        $element = $file->getElementTexts('Scriptus', 'Transcription');
        $firstElement = $element[0]; //getElementTexts returns array, element[0] is first element

        $oldTranscription = $firstElement->text; 
        
        //set newTranscription, which will be used at bottom to update the Scriptus_changes table    
        if ($oldTranscription){
            $newTranscription = 0;
        }
        else {
            $newTranscription = 1;
        }


        //Update file with new transcription information
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

        /*save URL last edited by user*/
        $uri = getenv("REQUEST_URI");

        //Chop save off of end of URL
        $uri = substr($uri, 0, -5);

        
        $user = current_user();

        //Get username, or define as empty string if user isn't logged in -- this will be saved to Scriptus_changes
        if ($user){
            $username = $user->username;
        }
        else{
            $username = '';
        }

        //Get database
        $db = get_db();

        //Timestamp format YYYY-MM-DD HH:MM:SS
        $timestamp = date('Y-m-d H:i:s');


        //Insert information about change into Scriptus_changes
        $sql = "insert into Scriptus_changes VALUES ('" . $uri . "', '" . $username . "', '" . $timestamp .  "', '" . $newTranscription . "')";
        $stmt = new Zend_Db_Statement_Mysqli($db, $sql);
        $stmt->execute(array($uri, $user->username, $timestamp));
    


    }

    //Get the most recent transcriptions from the database.  The view also makes a query to the Disqus API to get most recent comments
    public function recentcommentsAction(){
        $sql = "select * from Scriptus_changes order by time_changed DESC;";
        $db = get_db();
        $stmt = new Zend_Db_Statement_Mysqli($db, $sql);
        $stmt->execute();

        $html = '';

        $numberOfRecentTranscriptions = 0;
        $recentlyTranscribed = array();
        while ($numberOfRecentTranscriptions < 5) {

            $row = $stmt->fetch();
            $transcribeItem = array();
            //$html .= '<p>Link to page: ' . $row["URL_changed"] . '</p><p>Username: ' . $row["username"] . '</p><p>Changed :' . $row["time_changed"] . "</p>"; 

            $transcribeItem["URL_changed"] = $row["URL_changed"];

            //Ok, this is a really ugly way to do this, but it also involved changing the least amount of things:
            $saveItem = 1;
            foreach($recentlyTranscribed as $recent){
                if ($transcribeItem["URL_changed"] == $recent["URL_changed"]){
                    $saveItem = 0;
                }
            }
            if ($saveItem == 1){
                $transcribeItem["username"] = $row["username"];
                $transcribeItem["time_changed"] = $row["time_changed"];
                $numberOfRecentTranscriptions++;
                array_push($recentlyTranscribed, $transcribeItem);
            }

            //print_r($row);
        }

        $this->view->recentTranscriptions = $recentlyTranscribed;
    
    }


    private function _buildForm() {
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