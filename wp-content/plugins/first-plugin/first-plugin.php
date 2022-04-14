<?php
/**
 * @package FirstPlugin
 */
 
 /* 
 * Plugin Name:       First Plugin
 * Plugin URI:        https://fakerpress.com
 * Description:       Drilling into plugins
 * Version:           0.5.3
 * Author:            Josh
 * Author URI:       
 * Text Domain:       first
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 
 */

 /*
*Put licence here
*/
//ABSPATH is a constant initialize by wordpress
//This constant is not defined is someone is trying to access this file from an 
//external path
//if this is not defined, they are looking at the file from outside of wordpress
//so we do not want to allow any functionallity
if(!defined("ABSPATH")){
    die("You can't access this file");
}

//alternative ways of doing this is (chckint for a global variable or function)
//One
//define("ABSPATH") or die("You can't access this file");
//Two
//can also check for add action
//if it doesnt exist, means wordpress was not initialized properly
// if(!function_exists("add_action")){
//      echo "You can't access this file";
//      exit
// }

//default actions
    //activation
    //deactivation
    //uninstall


class FirstPlugin{

    //Public method: can be accessed everywhere; default
    //Protected method:  accessed only in the class or in a class that extends the class
    //private method: only accessed by class itself
//    function __construct(){

//     // add_action("init",[$this,"custom_post_type"]);
//     // $this->print_stuff();

//     // }


    public $plugin;

    function __construct(){
        $this->plugin = plugin_basename(__FILE__);
    }

    //wp_enqueue_scripts would render front end scripts
    function register(){


        add_action("admin_enqueue_scripts", array($this,"enqueue"));
        add_action("admin_menu", array($this, "add_admin_pages"));
    
        //set up settings link from plugins page
        //echo $this->plugin;
        add_filter("plugin_action_links_$this->plugin", array($this, "settings_link"));
        //add_filter("plugin_action_links_first-plugin", array($this, "settings_link"));

    }
    //settings link for plugin
    public function settings_link($links){
        //add custom settings link
        $settings_link ='<a href="admin.php?page=first_plugin">Settings</a>';
        array_push($links, $settings_link);
        return $links;

    }
    //add menue page for administration
    public function add_admin_pages(){
        add_menu_page("First Plugin Configuration", "FirstPlug", "manage_options","first_plugin",array($this, "admin_index"), "dashicons-store");
    }

    public function admin_index(){
        //require template
        require_once plugin_dir_path(__FILE__).'templates/admin.php';

    }
   function enqueue(){
        wp_enqueue_style( "mypluginstyle", plugins_url( "/assets/mystyle.css",__FILE__ ));
        wp_enqueue_script( "mypluginscript", plugins_url( "/assets/myscript.js",__FILE__ ));
    }
    //Handled by their own files
    // function activation(){
    //     $this->custom_post_type();
    //     //
    //     //generate CPT
        
    //     flush_rewrite_rules();
    // }
    // function deactivation(){
    //     //flush rewrite rules
    //     flush_rewrite_rules();

    // }

   
    // function uninstall(){

    //     //delete CPT

    // }

    function custom_post_type(){
        register_post_type("book",array(
            "public" => true,
            "label" => "Books"

        ));
    }

    function activate(){
        require_once plugin_dir_path(__FILE__).'inc/first-plugin-activate.php';
        FirstPluginActivate::activate();

    }

   
    
}

//check existance before initialization
//standard safety process
if(class_exists("FirstPlugin")){
    $firstPlugin = new FirstPlugin();
    $firstPlugin->register();
    // $firstPlugin->custom_post_type();
    // FirstPlugin::register();
    }

//activation 
//connect to activation/deactivation files
//uses static method of calling class


//Which file to use - __FILE__ points to the current file
//use class instance and the string of method
//Example of calling a static method from a hook
register_activation_hook(__FILE__, array($firstPlugin, "activate" ));
//deactivation
require_once plugin_dir_path(__FILE__).'inc/first-plugin-deactivate.php';
register_deactivation_hook(__FILE__, array("FirstPluginDeactivate", "deactivate" ));

