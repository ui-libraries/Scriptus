<?php

class ScriptusPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array('install', 
                              //'uninstall', 
                              'define_routes'
                              );    
  

    public function hookInstall()
    {  
        $db = $this->_db;
        print_r($db);
        $sql = "CREATE TABLE IF NOT EXISTS `Scriptus_changes` (`URL_changed` text collate utf8_unicode_ci NOT NULL, `username` text collate utf8_unicode_ci, `time_changed` date NOT NULL, `new_transcription` boolean) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        $db->query($sql);
     
    }
    public function hookDefineRoutes($array)
    {    

        $router = $array['router'];
        $router->addRoute(
            'transcribe',
            new Zend_Controller_Router_Route(
                'transcribe/:item/:file',
                array(
                    'module'       => 'scriptus',
                    'controller'   => 'index',
                    'action'       => 'transcribe',
                )
            )
        );

        $router->addRoute(
            'save',
            new Zend_Controller_Router_Route(
                'transcribe/:item/:file/save',
                array(
                    'module'       => 'scriptus',
                    'controller'   => 'index',
                    'action'       => 'save',
                )
            )
        );     
        
    }

}