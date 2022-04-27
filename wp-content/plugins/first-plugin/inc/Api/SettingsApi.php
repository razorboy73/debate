<?php
/**
 * @package FirstPlugin
 */

 namespace Inc\Api;



 class SettingsApi{

    //doesnt need to be included in init.php as this class
    //doesnt need to be initialized
    //initialize an empty array as a property of the class

    public $admin_pages = array();

    public $admin_subpages = array();
    //only usable by the class itself
    public $settings = array();
    public $sections = array();
    public $fields = array();



    public function register(){
        //every time we initialize  class, we dont use construct
        if(!empty($this->admin_pages)){
            //if the array isnt empty, call another class
            //hooks the default menu activation method
            add_action("admin_menu", array($this,"addAdminMenu"));
        }
        //let's register settings
        //make sure settings are not empty
    if(!empty($this->settings)){
        add_action("admin_init", array($this, "registerCustomFields"));
    }

    }

    public function addPages(array $pages){
        // has type hinting - paramater must be of a certain type
        //errors out if it isnt passed an array
        $this->admin_pages = $pages;
        return $this;
    }

    public function withSubPage(string $title=null){
        //if there are no admin pages, just return the instance
        //we dont do anything, just keep method chaining in admin.php
        //working
        if(empty($this->admin_pages)){
            return $this;
        }
        //get the first admin page from the list of arrays
        $admin_page = $this->admin_pages[0];
        $subpage = [
            [
              "parent_slug"=>$admin_page['menu_slug'],
              "page_title"=> $admin_page['page_title'],
              "menu_title"=> ($title)? $title: $admin_page["menu_title"],
              "capability"=> $admin_page["capability"],
              "menu_slug"=> $admin_page['menu_slug'],
              "callback" => function(){ echo"<h1>Submenu Title</h1>";}
             
            ]
      
          ];

          $this->admin_subpages = $subpage;

          return $this;

    }

    public function addSubPages(array $pages){
        //the below works because admin_subpages is declaried as an empty array
        $this->admin_subpages = array_merge($this->admin_subpages, $pages);
        //this function allows for adding of subpages in method chain in admin.php file

        return $this;
    }


    public function addAdminMenu(){
        //loop through all the pages we add to the addPages method and
        //trigger the default admin pages to automatically create those pages at once

        foreach($this->admin_pages as $page){
            add_menu_page($page["page_title"], $page['menu_title'], $page['capability'], $page['menu_slug'],
            $page['callback'], $page['icon_url'], $page['position']);
        }

        foreach($this->admin_subpages as $page){
            add_submenu_page($page["parent_slug"], $page['page_title'], $page['menu_title'], $page['capability'],
            $page['menu_slug'], $page['callback']);
        }

    }

    public function setSettings(array $settings){
        // has type hinting - paramater must be of a certain type
        //errors out if it isnt passed an array
        $this->settings = $settings;
        return $this;
    }

    public function setSections(array $sections){
        // has type hinting - paramater must be of a certain type
        //errors out if it isnt passed an array
        $this->sections = $sections;
        return $this;
    }

    public function setFields(array $fields){
        // has type hinting - paramater must be of a certain type
        //errors out if it isnt passed an array
        $this->fields = $fields;
        return $this;
    }



    public function registerCustomFields()
    {
          /**one field needs 3 actions
         * register the settings
         * add settings sections generates a setting section uses a callback
         * add settings field - adds the custom field attached to the register settings
         *  using a public variable that stores all the settings, sections and fields
         * we then loop through arrays with attribute with a foreach loop
         * in order to register as many settings as their are in the multidimensional array
         * register settings
         *  callback can be optional use isset to detect data 
         * */
        
        foreach($this->settings as $setting){
            register_setting($setting["option_group"], $setting["option_name"], (isset($setting["callback"])?$setting["callback"]:""));
        
        }
        //add settings section; remember callback is optional
        foreach($this->sections as $section){
            add_settings_section($section["id"], $section["title"], (isset($section["callback"])?$section["callback"]:""), $section["page"]);
        }
        
        //add settings; callback is optional as is array of parameters
        foreach($this->fields as $field){
            add_settings_field($field["id"], $field["title"],(isset($field["callback"])?$field["callback"]:""), $field["page"], $field["section"], (isset($field["args"])?$field["args"]:""));
        }
    }

 }