<div class="wrap">
    <h1> Ideaprop Plugin </h1>
    <?php settings_errors(); ?>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-1">Manage settings</a></li>
        <li><a href="#tab-2">Updates</a></li>
        <li><a href="#tab-3">About</a></li>
    </ul>
    
    <div class = "tab-content">
        <div id="tab-1" class="tab-pane-active">

            <form method="post" actions="options.php">
                <?php
                //id of options group
                    settings_fields( 'ideaProp_options_group' );
                //slug of page where setting page is applied to
                    do_settings_sections('ideaProp_plugin');
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