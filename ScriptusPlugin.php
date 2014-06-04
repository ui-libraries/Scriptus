<?php

class ScriptusPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(//'install', 
                              //'uninstall', 
                              'define_routes'
                              );    
  
    /*
    public function hookInstall()
    {
        $db = $this->_db;
        $sql = "            
            CREATE TABLE IF NOT EXISTS `$db->Scriptus` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `item_id` int(10) unsigned NOT NULL,
              `transcription` text NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM ; ";
        $db->query($sql);
    }
    
    public function hookUninstall()
    {
        $db = $this->_db;
        $sql = "DROP TABLE IF EXISTS `$db->Scriptus`; ";
        $db->query($sql);
    }
    */
    
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
        
    }

}