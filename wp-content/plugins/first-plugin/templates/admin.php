<div class="wrap">
    <h1>First Plugin Admin</h1>
    <?php settings_errors();?>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-1">Manage Settings</a></li>
        <li><a href="#tab-2">Updates</a></li>
        <li><a href="#tab-3">About</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <form method="post" action="options.php">

                <?php

                //print out the settings fields by specifying the Id of the options group

                settings_fields("first_plugin_settings");
                // do settings uses the slug of the page, not the Id of the section
                do_settings_sections("first_plugin");
                submit_button();
                ?>


            </form>
        </div>
        <div id="tab-2" class="tab-pane">
            <h3>Updates</h3>
        </div>
        <div id="tab-3" class="tab-pane">
            <h3>About</h3>
        </div>

    </div>

<!-- Point the form at options.php, the built in page that  handles the updates -->
    
</div>