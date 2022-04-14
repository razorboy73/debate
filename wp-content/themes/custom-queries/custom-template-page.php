<?php
/*
* Template name: Simple Page Template
*/

get_header();
global $post;
global $user_ID;
global $wp_version;
global $manifest_version; //(string) The cache manifest version
global $required_php_version; //(string) The version of PHP this install of WordPress requires
global $required_mysql_version; //(string) The version of MySQL this install of WordPress requires






echo "<pre>";
echo "The wordress version is: ". $wp_version;
echo "<hr>";
echo $manifest_version; //(string) The cache manifest version
echo "<hr>";
echo $required_php_version; //(string) The version of PHP this install of WordPress requires
echo "<hr>";
echo $required_mysql_version;
echo "</pre>";
echo "<hr>";
echo "<pre>";
print_r($post);
echo "</pre>";
echo "<hr>";
echo "<pre>";
print_r($GLOBALS['post']);
echo "</pre>";
get_footer();
?>