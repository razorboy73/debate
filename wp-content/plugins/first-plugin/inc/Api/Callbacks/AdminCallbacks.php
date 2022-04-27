<?php
/**
 * @package FirstPlugin
 */

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

//this is not inclucderd in "init" as we do not need to initialize it
//we will initialize it in the admin.php file

 class AdminCallbacks extends BaseController{


    public function adminDashboard()
    {
    //this call back used to be inline in the basecontroller.php file
    return require_once("$this->plugin_path/templates/admin.php");
    } 

    public function customPostTypeManager()
    {
    //this call back used to be inline in the basecontroller.php file
    return require_once("$this->plugin_path/templates/customPostTypeManager.php");
    } 

    public function customTaxonomies()
    {
    //this call back used to be inline in the basecontroller.php file
    return require_once("$this->plugin_path/templates/customTaxonomies.php");
    } 

    public function customWidgets()
    {
    //this call back used to be inline in the basecontroller.php file
    return require_once("$this->plugin_path/templates/customWidgets.php");
    } 

   public function firstPluginOptionGroup($input){
      return $input;

   }

   public function firstPluginAdminSection(){
     echo "This is a great section";

   }

   public function firstPluginTextExample(){
      $value = esc_attr(get_option("text_example"));
      echo "<input type='text' class='regular-text' name='text_example' value='".$value."' placeholder='text example'>";

   }


 }