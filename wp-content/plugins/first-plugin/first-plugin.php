<?php
/**
 * @package FirstPlugin
 */



 /* 
 * Plugin Name:       First Plugin
 * Plugin URI:        https://fakerpress.com
 * Description:       Drilling into plugins
 * Version:           0.5.3
 * Author:            Josh
 * Author URI:       
 * Text Domain:       first
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 
 */

 /*
*Put licence here
*/
//ABSPATH is a constant initialize by wordpress
//This constant is not defined is someone is trying to access this file from an 
//external path
//if this is not defined, they are looking at the file from outside of wordpress
//so we do not want to allow any functionallity
if(!defined("ABSPATH")){
    die("You can't access this file");
}
//Require the composer autoload once
if(file_exists(dirname(__FILE__)."/vendor/autoload.php")){

    require_once dirname(__FILE__)."/vendor/autoload.php";
}


//need to define activation and deactivation hook outside of classes
//Must be completely procedural
//the code below includdes the class

// use Inc\Base\Activate;
// use Inc\Base\Deactivate;

//Because we are using autoload, we can remove "Inc\Base" and just call the class
use Activate;
use Deactivate;

function activate_first_plugin(){
    //calls static method of class
    Inc\Base\Activate::activate();

}
register_activation_hook( __FILE__, "activate_first_plugin" );

function deactivate_first_plugin(){
    Inc\Base\Deactivate::deactivate();
}




register_deactivation_hook( __FILE__, "deactivate_first_plugin" );

if(class_exists('Inc\\Init')){
    Inc\Init::register_services();
}