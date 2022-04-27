<?php
/**
 * @package FirstPlugin
 */

 namespace Inc\Base;

//by convention with psr - name the file and class the same
 class Activate{
     public static function activate(){
        
        flush_rewrite_rules();
     }
 }