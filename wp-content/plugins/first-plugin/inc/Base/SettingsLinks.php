<?php
/**
 * @package FirstPlugin
 */

 namespace Inc\Base;
 use \Inc\Base\BaseController;


//by convention with psr - name the file and class the same
 class SettingsLinks extends BaseController{

   
    
     public function register(){
        //set up settings link from plugins page
        //echo $this->plugin;
        add_filter("plugin_action_links_$this->plugin", array($this, "settings_link"));
        //add_filter("plugin_action_links_first-plugin", array($this, "settings_link"));


        
     }

     public function settings_link($links){
         //add custom settings link
        $settings_link ='<a href="admin.php?page=first_plugin">Settings</a>';
        array_push($links, $settings_link);
        return $links;

     }
 }