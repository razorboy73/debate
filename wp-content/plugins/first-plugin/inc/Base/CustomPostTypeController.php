<?php
/**
 * @package FirstPlugin
 */


namespace Inc\Base;


use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\CptCallbacks;

//by convention with psr - name the file and class the same
 

 class CustomPostTypeController extends BaseController{

    public $settings;
    public $callbacks;
    //adding in a file CptCallbacks
    public $cpt_callbacks;

    public $subpages = array();
    //gets a list of custom post types
    public $custom_post_types = array();

    public function register(){
        //create and activate a custom post type page

        //var_dump(($this->managers['cpt_manager'])); this shows we can activate the cpt_key from the basecontroller
        // Moved to baseController
        //$option = get_option("first_plugin");//Page name argument
        // $activated =isset($option["cpt_manager"]) ? $option["cpt_manager"]: false;
        // //if activated is false, we want to interupt the activation process below


        if(!$this->activated("cpt_manager")){
        
            return;
        }
        //this is the activation process
        
        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();
        //cpt_callbacks calls a new instance
        $this->cpt_callbacks = new CptCallbacks();

        $this -> setSubpages();
    
       
        //now we add the various parts of the page
        //will not interfere with instances of the following that are included
        //in the dashboard class
        $this->setSettings();
        $this->setSections();
        $this->setFields();
         //after setting registration, then add fields
         $this->settings->addSubPages($this->subpages)->register();

        //if this is empty, nothing get registeredd
        $this->storeCustomPostTypes();
    
        //if list of custom post types is empty, we do not want to activate the registration
        if(!empty($this->custom_post_types)){
            add_action('init', array($this, "registerCustomPostType"));
        }

    }

    public function setSubpages(){

        $this->subpages = 
      //each array is representative of a sub page we want to gnerate.
        [
            [
              "parent_slug"=>"first_plugin",
              "page_title"=> "Custom Post Types",
              "menu_title"=> "CPT Manager",
              "capability"=> "manage_options",
              "menu_slug"=> "firstplugin_CPT",
              "callback" => array($this->callbacks,"customPostTypeManager")
            ]
        ];

      }

      public function setSettings()
      {
       
        $args = array(
            array(
                  "option_group"=>"first_plugin_cpt_settings",
                  "option_name"=>"firstplugin_CPT",//replaces cpt_manager in db
                  "callback"=>array($this->cpt_callbacks,"cptSanitize")
                )
            );
     //now we set the sections
      $this->settings->setSettings($args);
      }

      public function setSections()
      {
        $args = array(
          array(
            //needs to be reapeated for every option
            "id"=>"first_plugin_cpt_index",
            "title"=>"Custom Post Manager",
            "callback"=>array($this->cpt_callbacks,"cptSectionManager"),
            "page" => "firstplugin_CPT"//must match menu slug of subpage
            )
        );
      $this->settings->setSections($args);
      }
    //Need to generate a field for every option used to create a custom posted type
      public function setFields()

      {
        //Post TypeID
        //singular name
        //plural name
        //public
        //has_archive
        //Need to set a field for each field of the the custom post type - long!

        $args = array(
            array(
                //needs to be reapeated for every paramter

                "id"=>"post_type", //option name from settings needs to be identical to name of field
                "title"=>"Custom Post Type ID",
                "callback"=>array($this->cpt_callbacks,"textField"),
                "page" => "firstplugin_CPT",//this attached field to the page, not via option group
                "section" => "first_plugin_cpt_index",
                "args" => array(
                  "option_name"=> "firstplugin_CPT", //option name from set settings
                  "label_for"=>"post_type", //label always matches id
                   "placeholder"=> "eg. Product"
          
                      )
                ),
                array(
                    //needs to be reapeated for every option
    
                    "id"=>"singular_name", //option name from settings needs to be identical to name of field
                    "title"=>"Singular Name",
                    "callback"=>array($this->cpt_callbacks,"textField"),
                    "page" => "firstplugin_CPT",
                    "section" => "first_plugin_cpt_index",
                    "args" => array(
                      "option_name"=> "firstplugin_CPT", //page name of plugin
                      "label_for"=>"singular_name", //label always matches id
                      "placeholder"=> "eg. Product"
              
                          )
                    ),
                    array(
                        //needs to be reapeated for every option
        
                        "id"=>"plural_name", //option name from settings needs to be identical to name of field
                        "title"=>"Plural Name",
                        "callback"=>array($this->cpt_callbacks,"textField"),
                        "page" => "firstplugin_CPT",
                        "section" => "first_plugin_cpt_index",
                        "args" => array(
                          "option_name"=> "firstplugin_CPT", //page name of plugin
                          "label_for"=>"plural_name",
                          "placeholder"=> "eg. Products" //label always matches id
                         
                  
                              )
                        ),
                        array(
                            //needs to be reapeated for every option
            
                            "id"=>"public", //option name from settings needs to be identical to name of field
                            "title"=>"Public?",
                            "callback"=>array($this->cpt_callbacks,"checkboxField"),
                            "page" => "firstplugin_CPT",
                            "section" => "first_plugin_cpt_index",
                            "args" => array(
                              "option_name"=> "firstplugin_CPT", //page name of plugin
                              "label_for"=>"public",
                              "class" => "ui-toggle" //label always matches id
                             
                      
                                  )
                            ),
                        array(
                            //needs to be reapeated for every option
            
                            "id"=>"has_archive", //option name from settings needs to be identical to name of field
                            "title"=>"Has Archive?",
                            "callback"=>array($this->cpt_callbacks,"checkboxField"),
                            "page" => "firstplugin_CPT",
                            "section" => "first_plugin_cpt_index",
                            "args" => array(
                              "option_name"=> "firstplugin_CPT", //page name of plugin
                              "label_for"=>"has_archive",
                              "class" => "ui-toggle" //label always matches id
                             
                      
                                  )
                            )
        );
        

      $this->settings->setFields($args);
      }
      
    public function storeCustomPostTypes(){
        //lets add to the custom post type.  Notice the square brackets
        //add in the information need to register a custom post type
        //for every CPT we define, we want to register a post type
        // $this->custom_post_types  
        //means going into the database and using the options associated
        //with firstplugin_CPT and filling this array
       
        //if this is false because it hasnt been set
        //it will throw an error
        //so we have to break out of this function

        if(!get_option("firstplugin_CPT")){
    
            add_option("firstplugin_CPT", array());
        }
        
       $options = get_option("firstplugin_CPT");
        //print("<pre> options ".print_r($options,true)."</pre>");
        // if($options== false){
        //     $default = array();
        //     update_option("firstplugin_CPT", $default);
        //     $options = get_option("firstplugin_CPT");
        // }


        //loop through the values in a multidimenation array, 
        //pull in all the k/v pairs
        foreach($options as $option){

            //with each loop, we add a new array
            //of page options to the array
            $this->custom_post_types[]  = 
            array(
                'post_type'             => $option["post_type"],
                'name'                  => $option["plural_name"],
                'singular_name'         => $option["singular_name"],
                'menu_name'             => $option["plural_name"],
                'name_admin_bar'        => $option["singular_name"],
                'archives'              => $option["singular_name"]." Archives",
                'attributes'            => $option["singular_name"]." Attributes",
                'parent_item_colon'     => 'Parent '. $option["singular_name"],
                'all_items'             => 'All '.$option["singular_name"],
                'add_new_item'          => 'Add New '. $option["singular_name"],
                'add_new'               => 'Add New',
                'new_item'              => 'New '. $option["singular_name"],
                'edit_item'             => 'Edit '.$option["singular_name"],
                'update_item'           => 'Update '.$option["singular_name"],
                'view_item'             => 'View '.$option["singular_name"],
                'view_items'            => 'View '. $option["plural_name"],
                'search_items'          => 'Search '. $option["plural_name"],
                'not_found'             => 'No '. $option["singular_name"] .' Found',
                'not_found_in_trash'    => 'No '. $option["singular_name"] .' Found in Trash',
                'featured_image'        => 'Featured Image',
                'set_featured_image'    => 'Set Featured Image',
                'remove_featured_image' => 'Remove Featured Image',
                'use_featured_image'    => 'Use Featured Image',
                'insert_into_item'      => 'Insert into '.$option["singular_name"],
                'uploaded_to_this_item' => 'Upload to this '.$option["singular_name"],
                'items_list'            => $option["plural_name"].' List',
                'items_list_navigation' => $option["plural_name"].' List Navigation',
                'filter_items_list'     => 'Filter '. $option["plural_name"].' List',
                'label'                 => $option["singular_name"],
                'description'           => $option["plural_name"].' Custom Post Type',
                'supports'              => array("title","editor", "thumbnail"),
                'taxonomies'            => array("category", "post_tag"),
                'hierarchical'          => false,
                'public'                => isset($option["public"])?: false,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'menu_position'         => 5,
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => true,
                'can_export'            => true,
                'has_archive'           => isset($option["has_archive"])?: false,
                'exclude_from_search'   => false,
                'publicly_queryable'    => true,
                'capability_type'       => 'post'
            );

        }

      
            
        
        
       
        

    

    }

    public function registerCustomPostType(){
       
        //foreach($this->custom_post_types as $key => $value
        //Dont do the key value method,  but store the whole multidimensional array
        //give you access to all the keys and values

        foreach($this->custom_post_types as $post_type){

            register_post_type($post_type["post_type"],
                array(
                    "labels"=> array(
                        'name'                  => $post_type['name'],
						'singular_name'         => $post_type['singular_name'],
						'menu_name'             => $post_type['menu_name'],
						'name_admin_bar'        => $post_type['name_admin_bar'],
						'archives'              => $post_type['archives'],
						'attributes'            => $post_type['attributes'],
						'parent_item_colon'     => $post_type['parent_item_colon'],
						'all_items'             => $post_type['all_items'],
						'add_new_item'          => $post_type['add_new_item'],
						'add_new'               => $post_type['add_new'],
						'new_item'              => $post_type['new_item'],
						'edit_item'             => $post_type['edit_item'],
						'update_item'           => $post_type['update_item'],
						'view_item'             => $post_type['view_item'],
						'view_items'            => $post_type['view_items'],
						'search_items'          => $post_type['search_items'],
						'not_found'             => $post_type['not_found'],
						'not_found_in_trash'    => $post_type['not_found_in_trash'],
						'featured_image'        => $post_type['featured_image'],
						'set_featured_image'    => $post_type['set_featured_image'],
						'remove_featured_image' => $post_type['remove_featured_image'],
						'use_featured_image'    => $post_type['use_featured_image'],
						'insert_into_item'      => $post_type['insert_into_item'],
						'uploaded_to_this_item' => $post_type['uploaded_to_this_item'],
						'items_list'            => $post_type['items_list'],
						'items_list_navigation' => $post_type['items_list_navigation'],
						'filter_items_list'     => $post_type['filter_items_list']
                        ),
                    'label'                     => $post_type['label'],
                    'description'               => $post_type['description'],
                    'supports'                  => $post_type['supports'],
                    'taxonomies'                => $post_type['taxonomies'],
                    'hierarchical'              => $post_type['hierarchical'],
                    "public" => $post_type["public"],
                    'show_ui'                   => $post_type['show_ui'],
					'show_in_menu'              => $post_type['show_in_menu'],
					'menu_position'             => $post_type['menu_position'],
					'show_in_admin_bar'         => $post_type['show_in_admin_bar'],
					'show_in_nav_menus'         => $post_type['show_in_nav_menus'],
					'can_export'                => $post_type['can_export'],
                    "has_archive"               =>$post_type["has_archive"],
                    'exclude_from_search'       => $post_type['exclude_from_search'],
					'publicly_queryable'        => $post_type['publicly_queryable'],
					'capability_type'           => $post_type['capability_type']
                    )
            );
        }   
    }
}


