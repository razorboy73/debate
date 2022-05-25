<div class="wrap">

<h1>Custom Post Type Manager As A Callback Function</h1>
<?php settings_errors(); if(isset($_POST["edit_post"])) echo $_POST["edit_post"];?>


            <ul class="nav nav-tabs">
        <li class="<?php echo !isset($_POST["edit_post"])?'active': ''?>"><a href="#tab-1">Your Custom Posts</a></li>
        <li class="<?php echo isset($_POST["edit_post"])?'active': ''?>">
                <a href="#tab-2">
                <?php echo isset($_POST["edit_post"])?'Edit': 'Add'?> Custom Post Types
                </a>
            </li>
        <li><a href="#tab-3">Export</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane  <?php echo !isset($_POST["edit_post"])?'active': ''?>">
                   <h3>Manage Your Custom Post Types</h3>
                   <?php
                        // if this returns false, set an array, else pull the value out of the db
                    //this syntax uses the test as the true value

                    if(!get_option("firstplugin_CPT")){
                        $options = array();
                    }else{
                        $options = get_option("firstplugin_CPT");
                    }
                    
                    // if(!get_option("firstplugin_CPT")){
                    //     $options = array();
                    // }else{
                    //     $options = get_option("firstplugin_CPT");
                    // };

                    // echo "<table><tr><th>ID</th><th>Singular Name</th><th>Plural Name</th><th>Public</th><th>Archive</th></tr>";
                    
                    // foreach($options as $option) {
                    //         echo "<tr><td>{$option['post_type']}</td><td>{$option['plural_name']}</td><td>{$option['public']}</td><td>{$option['has_archive']}</td>]</tr>";
                    // }
                    // echo "</table>"

                        echo '<table class="cpt-table"><tr><th>ID</th><th>Singular Name</th><th>Plural Name</th><th class="text-center">Public</th><th class="text-center">Archive</th><th class="text-center">Actions</th></tr>';
                        //this will throw an error on initialization
                        foreach ($options as $option) {

                            $public = isset($option["public"])?"TRUE":"FALSE";
                            $has_archive = isset($option["has_archive"])?"TRUE":"FALSE";

                            echo "<tr><td>{$option['post_type']}</td><td>{$option['singular_name']}</td><td>{$option['plural_name']}</td><td class=\"text-center\">{$public}</td><td class=\"text-center\">{$has_archive}</td><td class=\"text-center\">";
                            //Edit Function
                            // want it to refresh in place - so action is blank
                                echo '<form method="post" action="" class="inline-block">';
                                //edit post type - make sure the value is the correct post
                                echo '<input type ="hidden" name="edit_post" value="'.$option['post_type'].'">';
                                submit_button("Edit", "primary small", "submit", false);
                                
                            // because we are not pointing to settings field, we dont need the
                            // sanitization of  settings_fields( $option_group:string )
                            echo "</form>   ";

                            // Delete function
                            echo '<form method="post" action="options.php" class="inline-block">';
                            
                                settings_fields("first_plugin_cpt_settings");//generates the code needed for the edit action
                                echo '<input type ="hidden" name="remove" value="'.$option['post_type'].'">';
                                //add an array to the button to store the relevant attributes
                                submit_button("Delete", "delete small", "submit", false, array(
                                    'onclick' => 'return confirm("Are you sure you want to delete this custom post type.  The associated data will not be deleted");'
                                ));
                                //in its initial state. this call the cptsanitize function and submits an empty cpt
                                //add a hidden fields above the submit button
                                //set the value to the post type
                           echo "</form></td></tr>";
                        }

                        echo '</table>';

                    ?>
        
        </div>
        <div id="tab-2" class="tab-pane <?php echo isset($_POST["edit_post"])?'active': ''?>">
           
                <form method="post" action="options.php">

                    <?php

                    //print out the settings fields by specifying the Id of the options group
                    settings_fields("first_plugin_cpt_settings");//get the options group
                    // do settings uses the slug of the page, not the Id of the section
                    do_settings_sections("firstplugin_CPT");//menu slug of page are we printing to
                    
                    //to print the sections, we need to tap the sections page
                    //Use this in a settings page callback function to output all the sections 
                    //and fields that were added to that $page with add_settings_section() 
                    //and add_settings_field()
                    submit_button();
                    ?>


                </form>
        </div>
        <div id="tab-3" class="tab-pane">
            <h3>Export Your Custom Post Types</h3>
        </div>

    </div>

</div>