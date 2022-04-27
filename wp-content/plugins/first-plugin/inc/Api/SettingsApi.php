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

    public function register(){
        //every time we initialize  class, we dont use construct
        if(!empty($this->admin_pages)){
            //if the array isnt empty, call another class
            //hooks the default menu activation method
            add_action("admin_menu", array($this,"addAdminMenu"));
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
              "callback" => function(){ echo"<h1>Submenu Title</h1>";},
             
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

 }