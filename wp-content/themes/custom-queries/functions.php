


<?php


add_action("init", function(){
   add_image_size("jk_medium", 469,469, true);
    }
);
//global $wpdb; 


// $posts = $wpdb->get_results("
// SELECT post_title FROM $wpdb->posts WHERE post_status = 'publish' and post_type ='post' ORDER BY
// post_date ASC LIMIT 0,4
// ");

// echo "<pre>";
// print_r($post);
// echo "</pre>";


// $row= $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_status = 'publish' and post_type ='post' ORDER BY
// post_date ASC LIMIT 0,9");

// var_dump($row);