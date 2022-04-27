<?php
/**
 * @package FirstPlugin
 */


namespace Inc\Base;
use \Inc\Base\BaseController;

//by convention with psr - name the file and class the same
 

 class Enqueue extends BaseController{
     
    
    public function register(){

        add_action("admin_enqueue_scripts", array($this,"enqueue"));
        //        
      
    }

    function enqueue(){
        //cant use the plugins url
        //cant use the global PLUGIN_PATH as that is for the requires
        //need to define a new constant
                wp_enqueue_style( "mypluginstyle", $this->plugin_url."/assets/mystyle.css" );
                wp_enqueue_script( "mypluginscript", $this->plugin_url."/assets/myscript.js");
            }

   
 }