<?php
/**
 * @package FirstPlugin
 */

namespace Inc\Api\Callbacks;



//this is not inclucderd in "init" as we do not need to initialize it
//we will initialize it in the admin.php file

 class TaxonomyCallbacks{


    public function taxSectionManager(){

        echo "Create as many custom taxonomies as you like";

     }

    public function taxSanitize($input){
        print("<pre> input ".print_r($_POST,true)."</pre>");
        //using $_POST shows the hidden fields
        //ar_dump($input);
        $output = get_option("first_plugin_tax");
        if(isset($_POST["remove"])){
          //delete a record from the array
          // specify the key to remove in the output
            unset($output [$_POST["remove"]]);

            return $output;
        }

        // if (!$output){
        //     print "Empty";
        //     $output[$input['taxonomy']] = $input;
        //     print("<pre> output ".print_r($output,true)."</pre>");
         
        //     return $output;
        // }
        //generate an array that has post type as a key and the original array as the value
        //$new_input = array($input["taxonomy"]=> $input);
        //print("<pre>new_input ".print_r($new_input,true)."</pre>");

        //need to cycle through  the output to see if key of array matches post type
        //if it does, update the assocated array
        //otherwise append it
        //w
        print("<pre> output ".print_r($input,true)."</pre>");

        

        

        if (count( $output )==0) {
            // first cpt save, as $output is empty single array
            $output[$input['taxonomy']] = $input;
            print("<pre> output 1 ".print_r($input,true)."</pre>");
            return $output;
            //print("<pre> output 2 ".print_r($output,true)."</pre>");
          }
            // all other saves as $output is multi-dimensional array
        foreach ($output as $key => $value) {
            if ($input['taxonomy'] === $key) {
              $output[$key] = $input;
              print("<pre> output 2 ".print_r($input,true)."</pre>");

            } else {
              $output[$input['taxonomy']] = $input;
              print("<pre> output 3".print_r($input,true)."</pre>");
            }
          }
         
       
        // foreach($output as $key => $value){
        //     print("<pre>type ".print_r($key,true)."</pre>");
        //     //if this exists, the key will exist in database already
        //     //and will match the input
        //     if($input['taxonomy'] === $key ){
        //         $output[$key] = $input;
        //     }else{
        //         //add a new arry with a key equal to post type
        //         $output[$input['taxonomy']] = $input;
        //     }
        // }
        //print("<pre> input ".print_r($input,true)."</pre>");
        //print("<pre> output ".print_r($output,true)."</pre>");
      
        
      return $output;

     }

     public function textField($args){

        $name = $args["label_for"];//Key in the options
        $option_name = $args["option_name"];//key in database
        $value = " ";//insert this so even if value is blank, it doesnt error out
       
        //if the "edit post" is set, we should use it to prepopulated
        if(isset($_POST["edit_taxonomy"])){
          //input is getting the option name
          // input carries the entire array of custom post type
          $input = get_option($option_name);//Page name argument
          // access the edit post key and then the name 
          $value = $input[$_POST["edit_taxonomy"]][$name];
          //firstplugin_CPT["comicbooks"]["post_type which is commicbooks"]
          // name is the value we want to access
          //then use this data to prepopulate the field
        }
  
        echo '<input type="text" class="regular-text" id="' .$name .'" name="'.$option_name.'['.$name .']" value="'.$value.'" placeholder="'.$args['placeholder'].'"required>';
        
  
       }

       public function checkboxField($args)
      {
         $name = $args["label_for"];//pulls from admin.php and basecontroller.php
        
         $classes = $args["class"];
         $option_name = $args["option_name"];
         $checked = false;//make sure checked has a default value
      
         if(isset($_POST["edit_taxonomy"])){
          $checkbox = get_option($option_name);//Page name argument
          //var_dump($checkbox);
         
          $checked =isset($checkbox[$_POST["edit_taxonomy"]][$name]) ?: false;
         
          
          // die();
        }
        
         echo '<input type ="checkbox" id="'.$name.'" name="'.$option_name.'['.$name.']" value="1" '.($checked ?'checked':'').'>';
      
        }

        public function checkboxPostTypeField($args)
        {
           
          $output = "";
          $name = $args["label_for"];//pulls from admin.php and basecontroller.php
          
           $classes = $args["class"];
           $option_name = $args["option_name"];
           $checked = false;//make sure checked has a default value
        
           if(isset($_POST["edit_taxonomy"])){
            $checkbox = get_option($option_name);//Page name argument
    
          }
            $post_types = get_post_types(array(
              "show_ui" => true,
              "exclude_from_search"=>false,
          ));
            
           
            foreach($post_types as $post){
              if($checkbox){
                $checked =isset($checkbox[$_POST["edit_taxonomy"]][$name][$post]) ?: false;
              }
              $output .= '<div class="mb-10"><strong>'.$post.'</strong> <input type ="checkbox" id="'.$post.'" name="'.$option_name.'['.$name.']['.$post.']" value="1" '.($checked ?'checked':'').'></div>';
        
            }
            
            // die();
        
          
          //  echo '<input type ="checkbox" id="'.$name.'" name="'.$option_name.'['.$name.']" value="1" '.($checked ?'checked':'').'>';
        
          echo $output;
          }



      
 }