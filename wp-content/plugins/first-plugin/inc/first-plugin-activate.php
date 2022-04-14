<?php
/**
 * @package FirstPlugin
 */


 class FirstPluginActivate{
     public static function activate(){
        
        flush_rewrite_rules();
     }
 }