<?php
/**
 * @package FirstPlugin
 */

namespace Inc\Base;

//purpose of the file is to define publically available
//variables

class BaseController{
    //not initialized in the Init file because it only needs to be extended
    //by other classes

    public $plugin_path;
    //base controller is publiclly accessible
    public $plugin_url;
    
    public $plugin;
    
    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin = plugin_basename(dirname(__FILE__, 3)).'/first-plugin.php';
    }

}

//globaly define plugin path with a constant
// define("PLUGIN_PATH", plugin_dir_path(__FILE__));
// define("PLUGIN_URL", plugin_dir_url(__FILE__));
// define("PLUGIN", plugin_basename(__FILE__));