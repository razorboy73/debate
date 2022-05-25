<?php
/**
 * @package FirstPlugin
 */


namespace Inc\Base;

use Inc\Api\Callbacks\AdminCallbacks;
use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;

//by convention with psr - name the file and class the same
 

 class ChatController extends BaseController{

    public $callbacks;

    public $subpages = array();

    public function register(){
        //create and activate a custom post type page

        //var_dump(($this->managers['cpt_manager'])); this shows we can activate the cpt_key from the basecontroller
        // Moved to baseController
        //$option = get_option("first_plugin");//Page name argument
        // $activated =isset($option["cpt_manager"]) ? $option["cpt_manager"]: false;
        // //if activated is false, we want to interupt the activation process below

        if(!$this->activated("chat_manager")){
            return;
        }

        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();

        $this -> setSubpages();

        $this->settings->addSubPages($this->subpages)->register();
    

        add_action('init', array($this, "activate"));

    }

    public function setSubpages(){

        $this->subpages = 
      //each array is representative of a sub page we want to gnerate.
        [
            [
              "parent_slug"=>"first_plugin",
              "page_title"=> "Chat",
              "menu_title"=> "Chat Manager",
              "capability"=> "manage_options",
              "menu_slug"=> "firstplugin_CM",
              "callback" => array($this->callbacks,"adminChat")
            ]
        ];

      }

    public function activate(){

    
        // $labels = array(
        //     "name" => "Mulitiple Debates",
        //     'show_in_rest'   => true,
        //     "singular_name" => "Single Silly Debate",//used  when  viewing the category
        //     'search_items'      => "Search Da Debates",//in the widget
        //     'all_items'         => 'All Dat Debate Categories',
        //     'parent_item'       => 'Main Crazy Category', //category widget
        //     'parent_item_colon' => 'Parent Debate Category?Sdfdsfsd:',
        //     'name_field_description' => "Pick Wisely",
        //     'edit_item'         => 'Edit Debate Category Name', 
        //     'update_item'       => 'Update The Debate Category',//shows when using quick edit
        //     'add_new_item'      => 'Add New Debate Topic', // 
        //     'new_item_name'     => 'New Groovy Debate Topic',
        //     'menu_name'         => __( 'Arugment Topics' ), // overrides name in menu
        // );
        
        // $args = array(

        //     "labels" => $labels,
        //     'hierarchical' => true,
        //     'rewrite' => array('slug'=> "debate-topics"),
        //     'show_in_rest'          => true
        // );

        // register_taxonomy( 'debate_topics', 'debate', $args );
    }
}


