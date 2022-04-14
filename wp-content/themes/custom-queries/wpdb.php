<?php

global $wpdb;


// $posts = $wpdb->get_results("
// SELECT post_title FROM $wpdb->posts WHERE post_status = 'publish' and post_type ='post' ORDER BY
// post_date ASC LIMIT 0,4
// ");

// echo "<pre>";
// print_r($post);
// echo "</pre>";


$row= $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_status = 'publish' and post_type ='post' ORDER BY
post_date ASC LIMIT 0,4
");

var_dump($row);