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
 

 class Admin extends BaseController{
   //by extending the base controller, we get access to the plugin path
   //extension initiallizes the BaseController
    
    //create a new instance of the settings API within admincontroller
    //when admin controller gets called

    //declare publically accessible variable
    public $settings;
    public $callbacks;

    public $pages = array();
    public $subpages = array();
    
  
  
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
      $this->setPages();
      $this->setSubPages();
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
      $this->settings->addPages($this->pages)->withSubPage("Dashboard")->addSubPages($this->subpages)->register();
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

      public function setSubpages(){

        $this->subpages = 
      //each array is representative of a sub page we want to gnerate.
      [
            [
              "parent_slug"=>"first_plugin",
              "page_title"=> "Custom Post Types",
              "menu_title"=> "CPT",
              "capability"=> "manage_options",
              "menu_slug"=> "firstplugin_CPT",
              "callback" => array($this->callbacks,"customPostTypeManager")

            ],
            [
              "parent_slug"=>"first_plugin",
              "page_title"=> "Custom Taxonomies",
              "menu_title"=> "Taxonomies",
              "capability"=> "manage_options",
              "menu_slug"=> "firstplugin_Taxonomies",
              "callback" => array($this->callbacks,"customTaxonomies")

            ],
            [
              "parent_slug"=>"first_plugin",
              "page_title"=> "Custom Widgets",
              "menu_title"=> "widgets",
              "capability"=> "manage_options",
              "menu_slug"=> "firstplugin_widgets",
              "callback" => array($this->callbacks,"customWidgets")

            ],



      ];

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
 