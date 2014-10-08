<?php

class ScriptusPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array('install', 
                              //'uninstall', 
                              'define_routes'
                              );    


    protected $_filters = array('guest_user_widgets', 'guest_user_links');

     public function filterGuestUserLinks($links)
    {
        
        //$url = url('guest-user/user/me');
        //$logoutUrl = url('users/logout');
        
        $user = current_user();
        $sql = "select * from Scriptus_changes where username = '" . $user->username . "'order by time_changed DESC;";
        $db = get_db();
        $result = $db->query($sql);

        //The first row
        $row = $result->fetch();
        $lastTranscribed = $row['URL_changed'];
        
        
        $lastTranscribed = (string)$lastTranscribed;
        $someLink = array('id'=>'transcribe',
                    'uri'=>url('' . $lastTranscribed),
                    'label' => 'Last transcribed');
        //$links[] = "<a href='$logoutUrl'>Logout</a>";
        //$links[] = "<a href='$url'>My Dashboard</a>";
        $links[] = $someLink;
        //$links[] = $logoutUrl;
        return $links;
    
    }

    public function filterGuestUserWidgets($widgets)
    {

        $widget = array('label'=>'Most Recent Transcriptions');
        $html = "<ul>";


        $user = current_user();
        $sql = "select * from Scriptus_changes where username = '" . $user->username . "'order by time_changed DESC limit 3;";
        $db = get_db();
        $result = $db->query($sql);

        //The first row
        while ($row = $result->fetch()){
            $lastTranscribed = $row['URL_changed'];
            $timeChanged = $row['time_changed'];
            $displayTitle = $row['file_name'] . ', ' . $row['item_name'];
            
            $lastTranscribed = (string)$lastTranscribed;
            $someLink = array('id'=>'transcribe',
                        'uri'=>url('' . $lastTranscribed),
                        'label' => 'Last transcribed');

            $html .= "<li><a href='$lastTranscribed'>$displayTitle</a></li>"; 

        }



        $html .= "</ul>";
        $widget['content'] = $html;
        $widgets[] = $widget;




        return $widgets;
    }

    public function hookInstall()
    {  
        $db = $this->_db;
        print_r($db);
        $sql = "CREATE TABLE IF NOT EXISTS `Scriptus_changes` (`URL_changed` text collate utf8_unicode_ci NOT NULL, `username` text collate utf8_unicode_ci, `time_changed` datetime NOT NULL, `new_transcription` boolean, `collection_name` text collate utf8_unicode_ci, `item_name` text collate utf8_unicode_ci, `file_name` text collate utf8_unicode_ci ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
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
                
)            )
        );   

        $router->addRoute(
            'recentcomments',
            new Zend_Controller_Router_Route(
                'recent-comments',
                array(
                    'module'       => 'scriptus',
                    'controller'   => 'index',
                    'action'       => 'recentcomments',
                )
            )
        ); 

        $router->addRoute(
            'submissionstats',
            new Zend_Controller_Router_Route(
                'submission-stats',
                array(
                    'module'       => 'scriptus',
                    'controller'   => 'index',
                    'action'       => 'submissionstats',
                )
            )
        ); 
    }
}