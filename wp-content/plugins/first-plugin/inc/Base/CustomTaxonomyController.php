<?php
/**
 * @package FirstPlugin
 */


namespace Inc\Base;

use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\TaxonomyCallbacks;

//by convention with psr - name the file and class the same
 

 class CustomTaxonomyController extends BaseController{

    public $settings;

    public $callbacks;

    public $tax_callbacks;

    public $subpages = array();

    public $taxonomies = array();


    public function register(){
        //create and activate a custom post type page

        //var_dump(($this->managers['cpt_manager'])); this shows we can activate the cpt_key from the basecontroller
        //$option = get_option("first_plugin");//Page name argument
        //$activated =isset($option["taxonomy_manager"]) ? $option["taxonomy_manager"]: false;
        //if activated is false, we want to interupt the activation process below

        if(!$this->activated("taxonomy_manager")){
          return;
      }

        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();

        $this->tax_callbacks = new TaxonomyCallbacks();

        $this -> setSubpages();
        $this->setSettings();
        $this->setSections();
        $this->setFields();

        $this->settings->addSubPages($this->subpages)->register();
        $this->storeCustomTaxonomies();

        if(!empty($this->taxonomies)){

          add_action("init", array($this, "registerCustomTaxonomy"));

        }
    

        add_action('init', array($this, "activate"));

    }

    public function setSubpages(){

        $this->subpages = 
      //each array is representative of a sub page we want to gnerate.
        [
            [
              "parent_slug"=>"first_plugin",
              "page_title"=> "Custom Taxonomies",
              "menu_title"=> "Custom Taxonomy Manager",
              "capability"=> "manage_options",
              "menu_slug"=> "firstplugin_CTM",
              "callback" => array($this->callbacks,"customTaxonomies")
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


    public function setSettings(){
      //multidimenstional array
      $args = array(
        array(
          "option_group" => "first_plugin_tax_settings",
          "option_name" => "first_plugin_tax",
          "callback"=>array($this->tax_callbacks, "taxSanitize")

        )
        );
  
        $this->settings->setSettings($args);
    }


    public function setSections()
      {
        $args = array(
          array(
            //needs to be reapeated for every option
            "id"=>"first_plugin_tax_index",
            "title"=>"Custom Taxonomy Manager",
            "callback"=>array($this->tax_callbacks,"taxSectionManager"),
            "page" => "firstplugin_CTM"//must match menu slug of subpage
            )
        );
      $this->settings->setSections($args);
      }


    public function setFields(){

      $args = array(
        array(

          "id" => "taxonomy",
          "title" => "Custom Taxonomy ID",
          "callback" => array($this->tax_callbacks, "textField"),
          "page" => "firstplugin_CTM",//must match menu slug of subpage
          "section" => "first_plugin_tax_index",//same as the section id
          "args" => array(
            "option_name"=> "first_plugin_tax", //page name of plugin
            "label_for"=>"taxonomy", //label always matches id
            "placeholder"=> "eg. Genre",
            "array" => "taxonomy"
                )

        ),
        array(
           //needs to be reapeated for every option
    
           "id"=>"singular_name", //option name from settings needs to be identical to name of field
           "title"=>"Singular Name",
           "callback"=>array($this->tax_callbacks,"textField"),
           "page" => "firstplugin_CTM",
           "section" => "first_plugin_tax_index",
           "args" => array(
             "option_name"=> "first_plugin_tax", //page name of plugin
             "label_for"=>"singular_name", //label always matches id
             "placeholder"=> "eg. Genre",
             "array" => "taxonomy"
     
                 )

           ),

           array(
            //needs to be reapeated for every option

            "id"=>"hierarchical", //option name from settings needs to be identical to name of field
            "title"=>"Hierarchical?",
            "callback"=>array($this->tax_callbacks,"checkboxField"),
            "page" => "firstplugin_CTM",
            "section" => "first_plugin_tax_index",
            "args" => array(
              "option_name"=> "first_plugin_tax", //page name of plugin
              "label_for"=>"hierarchical",
              "class" => "ui-toggle", //label always matches id
              "array"=> "taxonomy"
             )
          ),
          array(
            //needs to be reapeated for every option

            "id"=>"objects", //option name from settings needs to be identical to name of field
            "title"=>"Post Types",
            "callback"=>array($this->tax_callbacks,"checkboxPostTypeField"),
            "page" => "firstplugin_CTM",
            "section" => "first_plugin_tax_index",
            "args" => array(
              "option_name"=> "first_plugin_tax", //page name of plugin
              "label_for"=>"objects",
              "class" => "ui-toggle", //label always matches id
              "array"=> "taxonomy"
             )
          ),
      );
      $this->settings->setFields($args);

    }
    public function storeCustomTaxonomies(){
      //check for array
      //create it if not there
        if(!get_option("first_plugin_tax")){
          add_option("first_plugin_tax");
        }
        //assign the option variable to options, use an empty array if needed
        $options = get_option("first_plugin_tax")?: array();

        foreach($options as $option){
        //with each loop we add a new array of taxonomy options
        //then register taxonomy
        //access the multidimensional taxonomy array and start adding to it
        
              //this gets stored in the labels variable below
                $labels = array(
                'name'              => $option["singular_name"],
                'singular_name'     => $option["singular_name"],
                'search_items'      => 'Search '. $option["singular_name"].'s',
                'all_items'         => 'All '. $option["singular_name"].'s',
                'parent_item'       => 'Parent '. $option["singular_name"],
                'parent_item_colon' => 'Parent '. $option["singular_name"].":",
                'edit_item'         => 'Edit '. $option["singular_name"],
                'update_item'       => 'Update '. $option["singular_name"],
                'add_new_item'      => 'Add New ' . $option["singular_name"],
                'new_item_name'     => 'New ' .$option["singular_name"]. ' Name' ,
                'menu_name'         => $option["singular_name"],
                );
                //adds to the existing array
              $this->taxonomies[]=
                  array(
                    'hierarchical'      => isset($option["hierarchical"])?TRUE:FALSE,// make it hierarchical (like categories)
                    'labels'            => $labels,
                    'show_ui'           => true,
                    'show_admin_column' => true,
                    'query_var'         => true,
                    'rewrite'           => array('slug' => $option["taxonomy"]),
                    'objects'           => isset($option["objects"])? $option["objects"]:null
              );
        }
        
      }


      public function registerCustomTaxonomy(){

        foreach($this->taxonomies as $taxonomy){
         
          

         
          //in order to associate taxonomy with a post type need to associate it with the
          //correct object
          //array_keys removes values from keys, cannot work on a null value
          $objects = isset($taxonomy['objects'])?array_keys($taxonomy['objects']):null;


          register_taxonomy($taxonomy['rewrite']['slug'], $objects, $taxonomy);

      }
    }
  }