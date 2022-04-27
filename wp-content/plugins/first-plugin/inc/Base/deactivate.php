<?php
/**
 * @package FirstPlugin
 */


namespace Inc\Base;

//by convention with psr - name the file and class the same
 

 class Deactivate{
     public static function deactivate(){

        flush_rewrite_rules();
     }
 }