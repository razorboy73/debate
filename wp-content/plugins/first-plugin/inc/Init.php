<?php
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
/**
 * @package FirstPlugin
 */

namespace Inc;



//by convention with psr - name the file and class the same
 final class Init
 {

/*
*every time we create a new class with functionalities we need, 
*add it to this class
*Store all the classes in an array, 
*@return array full list of classes
*
*/
    public static function get_services(){
        return [
            //return the entire class
            //if we didnt include the class, we are returning the file
            //by returning the class, you can use the register_services()
            Pages\Dashboard::class,
            Base\Enqueue::class,
            Base\CustomPostTypeController::class,
            Base\CustomTaxonomyController::class,
            Base\WidgetController::class,
            Base\GalleryController::class,
            Base\TestimonialController::class,
            Base\TemplatesController::class,
            Base\LoginController::class,
            Base\MembershipController::class,
            Base\ChatController::class,
            Base\SettingsLinks::class
            //Cant include activation/deactivtion hooks as they need to  be outside
            //of any class in order for the triggering to work
        ];

    }
    public static function register_services()
    //loops thrue the classes and initializes them, registering them if they exist
    //@return Nothing
    //Calls register method
    {
        //use self and the function instead of $this as the class is not initialized
        foreach(self::get_services() as $class){
            $service = self::instantiate($class);
            if(method_exists($service, "register")){
                $service->register();
            }

        }
        
    }

    /**
     * Initiallizes the class
     * @param class $class class from the services array
     * @return class instance  new instance of the class
     * 
     */
    private static function instantiate($class)
    {   
        //this creates a new instance of the class
        //allows for the automation of instance creation by adding new classes to the static
        //function by adding classes to the array
        $service = new $class();
        return $service;

    }
    
 }
//default actions
    //activation
    //deactivation


/**
 * @package FirstPlugin
 */

//namespace Inc;

//by convention with psr - name the file and class the same
 //class init{
     
 //   function __construct{}
 //}
//     //uninstall
// if(file_exists(dirname(__FILE__)."/vendor/autoload.php")){

//     require_once dirname(__FILE__)."/vendor/autoload.php";
// }
 
// use Inc\Base\Activate;
// use Inc\Base\Deactivate;
// use Inc\Admin\AdminPages;

// class FirstPlugin{

//     //Public method: can be accessed everywhere; default
//     //Protected method:  accessed only in the class or in a class that extends the class
//     //private method: only accessed by class itself
// //    function __construct(){

// //     // add_action("init",[$this,"custom_post_type"]);
// //     // $this->print_stuff();

// //     // }


//     public $plugin;

//     function __construct(){
//         $this->plugin = plugin_basename(__FILE__);
//     }

//     //wp_enqueue_scripts would render front end scripts
//     function register(){


//         add_action("admin_enqueue_scripts", array($this,"enqueue"));
//         add_action("admin_menu", array($this, "add_admin_pages"));
    
//         //set up settings link from plugins page
//         //echo $this->plugin;
//         add_filter("plugin_action_links_$this->plugin", array($this, "settings_link"));
//         //add_filter("plugin_action_links_first-plugin", array($this, "settings_link"));

//     }
//     //settings link for plugin
//     public function settings_link($links){
//         //add custom settings link
//         $settings_link ='<a href="admin.php?page=first_plugin">Settings</a>';
//         array_push($links, $settings_link);
//         return $links;

//     }
//     //add menue page for administration
//     public function add_admin_pages(){
//         add_menu_page("First Plugin Configuration", "FirstPlug", "manage_options","first_plugin",array($this, "admin_index"), "dashicons-store");
//     }

//     public function admin_index(){
//         //require template
//         require_once plugin_dir_path(__FILE__).'templates/admin.php';

//     }
//    function enqueue(){
//         wp_enqueue_style( "mypluginstyle", plugins_url( "/assets/mystyle.css",__FILE__ ));
//         wp_enqueue_script( "mypluginscript", plugins_url( "/assets/myscript.js",__FILE__ ));
//     }
//     //Handled by their own files
//     // function activation(){
//     //     $this->custom_post_type();
//     //     //
//     //     //generate CPT
        
//     //     flush_rewrite_rules();
//     // }
//     // function deactivation(){
//     //     //flush rewrite rules
//     //     flush_rewrite_rules();

//     // }

   
//     // function uninstall(){

//     //     //delete CPT

//     // }

//     function custom_post_type(){
//         register_post_type("book",array(
//             "public" => true,
//             "label" => "Books"

//         ));
//     }

//     function activate(){
//         //require_once plugin_dir_path(__FILE__).'inc/first-plugin-activate.php';
//         Activate::activate();

//     }

//     function deactivate(){
//         //require_once plugin_dir_path(__FILE__).'inc/first-plugin-activate.php';
//         Deactivate::deactivate();

//     }

   
    
// }

// //check existance before initialization
// //standard safety process
// if(class_exists("FirstPlugin")){
//     $firstPlugin = new FirstPlugin();
//     $firstPlugin->register();
//     // $firstPlugin->custom_post_type();
//     // FirstPlugin::register();
//     }

// //activation 
// //connect to activation/deactivation files
// //uses static method of calling class
// //Which file to use - __FILE__ points to the current file
// //use class instance and the string of method
// //Example of calling a static method from a hook
// register_activation_hook(__FILE__, array($firstPlugin, "activate" ));
// //deactivation
// //do not need the require once due to the use of composer
// //require_once plugin_dir_path(__FILE__).'inc/first-plugin-deactivate.php';
// //change the class in register deactivation hook to deactivate as we are requiring and including the file
// // above with use Inc\Deactivate
// //register_deactivation_hook(__FILE__, array("FirstPluginDeactivate", "deactivate" ));
// register_deactivation_hook(__FILE__, array($firstPlugin, "deactivate" ));

