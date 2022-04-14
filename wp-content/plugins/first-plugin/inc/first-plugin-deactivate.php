<?php
/**
 * @package FirstPlugin
 */


 class FirstPluginDeactivate{
     public static function deactivate(){

        flush_rewrite_rules();
     }
 }