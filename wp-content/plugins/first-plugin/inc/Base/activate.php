<?php
/**
 * @package FirstPlugin
 */

 namespace Inc\Base;

//by convention with psr - name the file and class the same
 class Activate{
     public static function activate(){
        
        flush_rewrite_rules();
         //define default array where everthing is zero
     //checks if the option exists and if not, creates it from scratch
     //pass it a default array
        //lets check if options exist so we dont overwrite settings with activation/deactivation
       
       $default = array();
        if(!get_option("first_plugin")){
            update_option("first_plugin", $default);
     }

     //add empty array to avoid error in the controller
     //should move this to the custom post type controller
//      if(!get_option("firstplugin_CPT")){
//         update_option("firstplugin_CPT", $default);

        if(!get_option("first_plugin_tax")){
            update_option("first_plugin_tax", $default);
        }

     
    }
 }