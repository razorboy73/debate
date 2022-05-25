<?php
/**
 * @package FirstPlugin
 */

namespace Inc\Base;

//purpose of the file is to define publically available
//variables

class BaseController{
    //not initialized in the Init file because it only needs to be extended
    //by other classes

    public $plugin_path;
    //base controller is publiclly accessible
    public $plugin_url;
    
    public $plugin;
    public $managers = array();
    
    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin = plugin_basename(dirname(__FILE__, 3)).'/first-plugin.php';
    
        $this->managers = array(
            //started as an array, then created an associatice array
            //now have keys and values
            "cpt_manager" =>"Activate CPT manager",
            "taxonomy_manager" => "Activate Taxonomy manager",
            "media_widget"=>"Activate Media Widget Manager",
            "gallery_manager" =>"Activate Gallery Manager",
            "testimonial_manager" =>"Activate Testimonial Manager",
            "templates_manager" => "Activate Template Manager",
            "login_manager" =>"Activate Login Manager",
            "membership_manager" =>"Activate Membership Manager",
            "chat_manager" =>"Activate Chat Manager",
            
        );
    
    
    }

    public function activated(string $key){

        $option = get_option("first_plugin");//Page name argument
        //is the key set, if so, return its value, if not, return false
        $activated =isset($option[$key]) ? $option[$key]: false;
        return $activated;
        

    }

}

//globaly define plugin path with a constant
// define("PLUGIN_PATH", plugin_dir_path(__FILE__));
// define("PLUGIN_URL", plugin_dir_url(__FILE__));
// define("PLUGIN", plugin_basename(__FILE__));