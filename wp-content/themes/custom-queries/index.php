


<?php
get_header();

print "<pre>";
print_r(wp_get_registered_image_subsizes());
print "</pre>";


get_footer();

// global $post;
// echo "<pre>";
// print_r($post);
// echo "</pre>";


// $posts = get_posts([
//   "posts_per_page"=>-1,
//   "post_type"=>"debate",
//   "tax_query" => array(
//         array(
//             'taxonomy' => 'debate_topics',
//                 'field' => 'slug',
//                 'terms' => ["parent-debate-cat","business"]
//         )

//         )
  
// ]);
// echo "<pre>";
// print_r($posts);
// echo "</pre>";


// foreach($posts as $post){
//     setup_postdata($post);

//     echo "<h1>".get_the_title()."</h1>";
//     echo "<a href='".get_the_permalink()."'>READ More</a>";
//     echo "<hr>";
// wp_reset_postdata();
// }

// $posts_query = new WP_Query([
//   "posts_per_page"=>-1,
//   "post_type"=>"debate",
//   "tax_query" => array(
//         array(
//             'taxonomy' => 'debate_topics',
//                 'field' => 'slug',
//                 'terms' => ["parent-debate-cat","business"]
//         )

//         )
//         ]);

// echo "<pre>";
// print_r($posts_query);
// echo "</pre>";

// if($posts_query->have_posts()){
//     while($posts_query->have_posts()){
//         $posts_query->the_post();

//             echo "<h1>".get_the_title()."</h1>";
//             echo "<a href='".get_the_permalink()."'>Read More</a>";
//             echo "<hr>";
//     }
// }

?>




