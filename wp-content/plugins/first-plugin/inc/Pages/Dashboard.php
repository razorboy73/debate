<?php
/**
 * @package FirstPlugin
 */


namespace Inc\Pages;

//by convention with psr - name the file and class the same
//need to access file in another directory
// start with a slash to get to base of directory


use \Inc\Base\BaseController;
use \Inc\Api\SettingsApi;
use \Inc\Api\Callbacks\AdminCallbacks;
use \Inc\Api\Callbacks\ManagerCallbacks;
 

 class Dashboard extends BaseController{
   //by extending the base controller, we get access to the plugin path
   //extension initiallizes the BaseController
    
    //create a new instance of the settings API within admincontroller
    //when admin controller gets called

    //declare publically accessible variable
    public $settings;
    public $callbacks;
    public $callbacks_mngr;

    public $pages = array();
    //public $subpages = array();
    
  
  
    //if construct is empty, do not need to declare it
    //removing this contstruct means we do not override the basecontroller construct
    // public function __construct()
    // {
      //store a new instance of SettingsApi in the settings variable
      //construct will overwrite some global variables so we can move sime items from th __construct to
      //the register method.
      // $this->settings = new SettingsApi();

      // $this->pages = [
      //   [
      //     "page_title"=>"First Plugin Configuration",
      //     "menu_title"=> "FirstPlug",
      //     "capability"=> "manage_options",
      //     "menu_slug"=> "first_plugin",
      //     "callback" => function(){ return require_once("$this->plugin_path/templates/admin.php");},
      //     "icon" => "dashicons-store",
      //     "position"=>110
      //   ]
  
      // ];

      
    // }
    public function register(){
      $this->settings = new SettingsApi();
      $this->callbacks = new AdminCallbacks();
      $this->callbacks_mngr = new ManagerCallbacks();
      $this->setPages();
      //$this->setSubPages();
      $this->setSettings();
      $this->setSections();
      $this->setFields();

      //remove the add action method, use the addpages method inside the setting class
      //add_action("admin_menu", array($this, "add_admin_pages"));
      //after the addPages is returned, call register function within
      //the instance of SettingsApi
      //remember that addPages requires an array
      //$this->settings->addPages($this->pages)->register();
      //declare the page options in the pages array
      //the pages variable below needs to be an array in an array as it
      //is being called by a for each loop
      //withsubpage() adds subpage to the menu
      //$this->settings->addPages($this->pages)->withSubPage("Dashboard")->addSubPages($this->subpages)->register();
      //removed subpage creation
      
      $this->settings->addPages($this->pages)->withSubPage("Dashboard")->register();
   
    
    }

      public function setPages()
      {
      
        $this->pages = [
          [
            "page_title"=>"First Plugin Configuration",
            "menu_title"=> "FirstPlug",
            "capability"=> "manage_options",
            "menu_slug"=> "first_plugin",
            "callback" => array($this->callbacks, "adminDashboard"),
            "icon" => "dashicons-store",
            "position"=>110
          ]
    
        ];
      }

      // public function setSubpages(){

      //   $this->subpages = 
      // //each array is representative of a sub page we want to gnerate.
      //   [
      //       [
      //         "parent_slug"=>"first_plugin",
      //         "page_title"=> "Custom Post Types",
      //         "menu_title"=> "CPT",
      //         "capability"=> "manage_options",
      //         "menu_slug"=> "firstplugin_CPT",
      //         "callback" => array($this->callbacks,"customPostTypeManager")
      //       ],
      //       [
      //         "parent_slug"=>"first_plugin",
      //         "page_title"=> "Custom Taxonomies",
      //         "menu_title"=> "Taxonomies",
      //         "capability"=> "manage_options",
      //         "menu_slug"=> "firstplugin_Taxonomies",
      //         "callback" => array($this->callbacks,"customTaxonomies")
      //       ],
      //       [
      //         "parent_slug"=>"first_plugin",
      //         "page_title"=> "Custom Widgets",
      //         "menu_title"=> "widgets",
      //         "capability"=> "manage_options",
      //         "menu_slug"=> "firstplugin_widgets",
      //         "callback" => array($this->callbacks,"customWidgets")
      //       ],
      //   ];

      // }
      public function setSettings()
      {
        //make this more database effective by putting it in the args array
        //change the option name to the page name
        $args = array(
            array(
                  "option_group"=>"first_plugin_settings",
                  "option_name"=>"first_plugin",//replaces cpt_manager in db
                  "callback"=>array($this->callbacks_mngr,"checkboxSanitize")
                )
            );
        //this started out using an array, but we changed it to an associative array
        //this creates a single setting for every key.
        // foreach($this->managers as $key => $value){
        //     //need square brackets to add to array to an existing array
        //     //for each item, will create a new array
        //     //this loop allows us to delete all the code below
        //     $args[] = array(
        //       "option_group"=>"first_plugin_settings",
        //       "option_name"=>$key,
        //       "callback"=>array($this->callbacks_mngr,"checkboxSanitize")
        //     );

        // }
        
        // $args = array(
        //   array(
        //     //needs to be reapeated for every option
        //     "option_group"=>"first_plugin_settings",
        //     "option_name"=>"cpt_manager",
        //     "callback"=>array($this->callbacks_mngr,"checkboxSanitize")
        //   ),
        //   array(
        //     //needs to be reapeated for every option
        //     //keep the same option group
        //     "option_group"=>"first_plugin_settings",
        //     "option_name"=>"taxonomy_manager",
        //     "callback"=>array($this->callbacks_mngr,"checkboxSanitize")
        //   ),
        //   array(
        //     //needs to be reapeated for every option
        //     //keep the same option group
        //     "option_group"=>"first_plugin_settings",
        //     "option_name"=>"media_widget",
        //     "callback"=>array($this->callbacks_mngr,"checkboxSanitize")
        //   ),
        //   array(
        //     //needs to be reapeated for every option
        //     //keep the same option group
        //     "option_group"=>"first_plugin_settings",
        //     "option_name"=>"gallery_manager",
        //     "callback"=>array($this->callbacks_mngr,"checkboxSanitize")
        //   ),
        //   array(
        //     //needs to be reapeated for every option
        //     //keep the same option group
        //     "option_group"=>"first_plugin_settings",
        //     "option_name"=>"testimonial_manager",
        //     "callback"=>array($this->callbacks_mngr,"checkboxSanitize")
        //   ),
        //   array(
        //     //needs to be reapeated for every option
        //     //keep the same option group
        //     "option_group"=>"first_plugin_settings",
        //     "option_name"=>"templates_manager",
        //     "callback"=>array($this->callbacks_mngr,"checkboxSanitize")
        //   ),
        //   array(
        //     //needs to be reapeated for every option
        //     //keep the same option group
        //     "option_group"=>"first_plugin_settings",
        //     "option_name"=>"login_manager",
        //     "callback"=>array($this->callbacks_mngr,"checkboxSanitize")
        //   ),
        //   array(
        //     //needs to be reapeated for every option
        //     //keep the same option group
        //     "option_group"=>"first_plugin_settings",
        //     "option_name"=>"membership_manager",
        //     "callback"=>array($this->callbacks_mngr,"checkboxSanitize")
        //   ),
        //   array(
        //     //needs to be reapeated for every option
        //     //keep the same option group
        //     "option_group"=>"first_plugin_settings",
        //     "option_name"=>"chat_manager",
        //     "callback"=>array($this->callbacks_mngr,"checkboxSanitize")
        //   ),

        // );
      $this->settings->setSettings($args);
      }
 


      public function setSections()
      {
        $args = array(
          array(
            //needs to be reapeated for every option
            "id"=>"first_plugin_admin_index",
            "title"=>"Settings Manager",
            "callback"=>array($this->callbacks_mngr,"adminSectionManager"),
            "page" => "first_plugin"
            )
        );
      $this->settings->setSections($args);
      }
      public function setFields()
      {

        $args = array();
        //this started out using an array, but we changed it to an associative array
        //the array/declaration comes from basecontroller.php
        foreach($this->managers as $key => $value){
            //need square brackets to add to array to an existing array
            //for each item, will create a new array
            //this loop allows us to delete all the code below
            $args[] = array(
              //needs to be reapeated for every option
              "id"=>$key, //option name from settings needs to be identical to name of field
              "title"=>$value,
              "callback"=>array($this->callbacks_mngr,"checkboxField"),
              "page" => "first_plugin",
              "section" => "first_plugin_admin_index",
              "args" => array(
                "option_name"=> "first_plugin", //page name of plugin
                "label_for"=>$key, //label always matches id
                "class" => "ui-toggle"
        
                    )
              );

            }


        // $args = array(
        //   array(
        //     //needs to be reapeated for every option
        //     "id"=>"cpt_manager", //option name from settings needs to be identical to name of field
        //     "title"=>"Activate CPT manager",
        //     "callback"=>array($this->callbacks_mngr,"checkboxField"),
        //     "page" => "first_plugin",
        //     "section" => "first_plugin_admin_index",
        //     "args" => array(
      
        //       "label_for"=>"cpt_manager", //label always matches id
        //       "class" => "ui-toggle"
      
        //     )
        //     ),
        //     array(
        //       //needs to be reapeated for every option
        //       "id"=>"taxonomy_manager", //option name from settings needs to be identical to name of field
        //       "title"=>"Activate Taxonomy manager",
        //       "callback"=>array($this->callbacks_mngr,"checkboxField"),
        //       "page" => "first_plugin",
        //       "section" => "first_plugin_admin_index",
        //       "args" => array(
        
        //         "label_for"=>"taxonomy_manager", //label always matches id
        //         "class" => "ui-toggle"
        
        //       )
        //       ),
        //       array(
        //         //needs to be reapeated for every option
        //         "id"=>"media_widget", //option name from settings needs to be identical to name of field
        //         "title"=>"Activate Media Widget Manager",
        //         "callback"=>array($this->callbacks_mngr,"checkboxField"),
        //         "page" => "first_plugin",
        //         "section" => "first_plugin_admin_index",
        //         "args" => array(
          
        //           "label_for"=>"media_widget", //label always matches id
        //           "class" => "ui-toggle"
          
        //         )
        //         ),
        //         array(
        //           //needs to be reapeated for every option
        //           "id"=>"gallery_manager", //option name from settings needs to be identical to name of field
        //           "title"=>"Activate Gallery Manager",
        //           "callback"=>array($this->callbacks_mngr,"checkboxField"),
        //           "page" => "first_plugin",
        //           "section" => "first_plugin_admin_index",
        //           "args" => array(
            
        //             "label_for"=>"gallery_manager", //label always matches id
        //             "class" => "ui-toggle"
            
        //           )
        //           ),
        //           array(
        //             //needs to be reapeated for every option
        //             "id"=>"testimonial_manager", //option name from settings needs to be identical to name of field
        //             "title"=>"Activate Testimonial Manager",
        //             "callback"=>array($this->callbacks_mngr,"checkboxField"),
        //             "page" => "first_plugin",
        //             "section" => "first_plugin_admin_index",
        //             "args" => array(
              
        //               "label_for"=>"testimonial_manager", //label always matches id
        //               "class" => "ui-toggle"
              
        //             )
        //             ),
        //             array(
        //               //needs to be reapeated for every option
        //               "id"=>"templates_manager", //option name from settings needs to be identical to name of field
        //               "title"=>"Activate Template Manager",
        //               "callback"=>array($this->callbacks_mngr,"checkboxField"),
        //               "page" => "first_plugin",
        //               "section" => "first_plugin_admin_index",
        //               "args" => array(
                
        //                 "label_for"=>"templates_manager", //label always matches id
        //                 "class" => "ui-toggle"
                
        //               )
        //               ),
        //               array(
        //                 //needs to be reapeated for every option
        //                 "id"=>"login_manager", //option name from settings needs to be identical to name of field
        //                 "title"=>"Activate Login Manager",
        //                 "callback"=>array($this->callbacks_mngr,"checkboxField"),
        //                 "page" => "first_plugin",
        //                 "section" => "first_plugin_admin_index",
        //                 "args" => array(
                  
        //                   "label_for"=>"login_manager", //label always matches id
        //                   "class" => "ui-toggle"
                  
        //                 )
        //                 ),
        //                 array(
        //                   //needs to be reapeated for every option
        //                   "id"=>"membership_manager", //option name from settings needs to be identical to name of field
        //                   "title"=>"Activate Membership Manager",
        //                   "callback"=>array($this->callbacks_mngr,"checkboxField"),
        //                   "page" => "first_plugin",
        //                   "section" => "first_plugin_admin_index",
        //                   "args" => array(
                    
        //                     "label_for"=>"membership_manager", //label always matches id
        //                     "class" => "ui-toggle"
                    
        //                   )
        //                   ),
        //                   array(
        //                     //needs to be reapeated for every option
        //                     "id"=>"chat_manager", //option name from settings needs to be identical to name of field
        //                     "title"=>"Activate Chat Manager",
        //                     "callback"=>array($this->callbacks_mngr,"checkboxField"),
        //                     "page" => "first_plugin",
        //                     "section" => "first_plugin_admin_index",
        //                     "args" => array(
                      
        //                       "label_for"=>"chat_manager", //label always matches id
        //                       "class" => "ui-toggle"
                      
        //                     )
        //                     )
                    
        // );
      $this->settings->setFields($args);
      }
  }



   //add menue page for administration
   //removed this section after we added Settings API
    // public function add_admin_pages(){
    //     add_menu_page("First Plugin Configuration", "FirstPlug", "manage_options","first_plugin",array($this, "admin_index"), "dashicons-store");
    // }

     //removed this section after we added Settings API
    // public function admin_index(){
    //     //require template
    //     //as we moved files around, use a constant to define path
    //     //require_once plugin_dir_path(__FILE__).'templates/admin.php';
       
    //     require_once $this->plugin_path .'templates/admin.php';

    // }
 