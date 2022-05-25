<?php
/**
 * @package FirstPlugin
 */

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

//this is not inclucderd in "init" as we do not need to initialize it
//we will initialize it in the admin.php file

 class ManagerCallbacks extends BaseController{


 

      public function checkboxSanitize($input){
            //First Generation 
            //two ways to do this
            //First Way
            //use a filter that only cares about integers - vs strings
            //return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
            //Second way 
            //Look for checkbox passed through http requiest of form
            //if the input is set, return true, else false
            //will tell database to store either true or false (1 or 0), not the input field
            //return(isset($input)?true:false);
            //second generation - work with array
            //loop through array for the specific manager keys
            //update database

            $output = array();
            foreach($this->managers as $key => $value){
               $output[$key] = isset($input[$key])?true:false;

            }
            return $output;
      }

      public function adminSectionManager(){

         echo "Manage the Sections and Features of this Plugin by activating the checkboxes from the following list";

      }
      //this function generates checkboxes for the settings fields automatically
      public function checkboxField($args)
      {
         $name = $args["label_for"];//pulls from admin.php and basecontroller.php
         $classes = $args["class"];
         $option_name = $args["option_name"];
         $checkbox = get_option($option_name);//Page name argument
         $checked =isset($checkbox[$name]) ? ($checkbox[$name] ? true:false): false;
         echo '<input type ="checkbox" id="'.$name.'" name="'.$option_name.'['.$name.']" value="1" class="'.$classes.'" '.($checked? 'checked': '').'>';
      }


 }