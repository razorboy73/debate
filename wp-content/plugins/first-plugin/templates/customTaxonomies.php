
<div class="wrap">

<h1>Taxonomy Manager As A Callback Function</h1>
<?php settings_errors(); if(isset($_POST["edit_taxonomy"])) echo $_POST["edit_taxonomy"];?>


            <ul class="nav nav-tabs">
        <li class="<?php echo !isset($_POST["edit_taxonomy"])?'active': ''?>"><a href="#tab-1">Your Custom Taxonomies</a></li>
        <li class="<?php echo isset($_POST["edit_taxonomy"])?'active': ''?>">
                <a href="#tab-2">
                <?php echo isset($_POST["edit_taxonomy"])?'Edit': 'Add'?> Custom Taxonomies
                </a>
            </li>
        <li><a href="#tab-3">Export</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane  <?php echo !isset($_POST["edit_taxonomy"])?'active': ''?>">
                   <h3>Manage Your Custom Taxonomy</h3>
                   <?php
                        // if this returns false, set an array, else pull the value out of the db
                    //this syntax uses the test as the true value

                    if(!get_option("first_plugin_tax")){
                        $options = array();
                    }else{
                        $options = get_option("first_plugin_tax");
                    }
                    
                   

                    echo "<table><tr><th>ID</th><th>Singular Name</th><th>Hierarchical</th><th>Actions</th></tr>";
                
                    foreach($options as $option) {
                    //         echo "<tr><td>{$option['post_type']}</td><td>{$option['plural_name']}</td><td>{$option['public']}</td><td>{$option['has_archive']}</td>]</tr>";
                    // }
                    // echo "</table>"

                        
                            $hierarchical = isset($option["hierarchical"])?"TRUE":"FALSE";
                           

                            echo "<tr><td>{$option['taxonomy']}</td><td>{$option['singular_name']}</td><td>{$hierarchical}</td><td class=\"text-center\">";
                            //Edit Function
                            // want it to refresh in place - so action is blank
                                echo '<form method="post" action="" class="inline-block">';
                                //edit post type - make sure the value is the correct post
                                echo '<input type ="hidden" name="edit_taxonomy" value="'.$option['taxonomy'].'">';
                                submit_button("Edit", "primary small", "submit", false);
                                
                            // because we are not pointing to settings field, we dont need the
                            // sanitization of  settings_fields( $option_group:string )
                            echo "</form>   ";

                            // Delete function
                            echo '<form method="post" action="options.php" class="inline-block">';
                                //this is from the id on setSections
                                settings_fields("first_plugin_tax_settings");//generates the code needed for the edit action
                                echo '<input type ="hidden" name="remove" value="'.$option['taxonomy'].'">';
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
        <div id="tab-2" class="tab-pane <?php echo isset($_POST["edit_taxonomy"])?'active': ''?>">
           
                <form method="post" action="options.php">

                    <?php

                    //print out the settings fields by specifying the Id of the options group
                    settings_fields("first_plugin_tax_settings");//get the options group
                    // do settings uses the slug of the page, not the Id of the section
                    do_settings_sections("firstplugin_CTM");//menu slug of page are we printing to
                    
                    //to print the sections, we need to tap the sections page
                    //Use this in a settings page callback function to output all the sections 
                    //and fields that were added to that $page with add_settings_section() 
                    //and add_settings_field()
                    submit_button();
                    ?>


                </form>
        </div>
        <div id="tab-3" class="tab-pane">
            <h3>Export Your Taxonomies</h3>
        </div>

    </div>

</div>