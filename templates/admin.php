<div class="wrapper">
    <h1> Ideaprop Plugin </h1>
    <?php settings_errors(); ?>
<section class="row">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-1">Manage settings</a></li>
        <li class=""><a href="#tab-2">Updates</a></li>
        <li class=""><a href="#tab-3">About</a></li>
    </ul>
    
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active"> 
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
            <div class="tab-pane" id="tab-2">
                <h3>Update
            </div>
            <div class="tab-pane" id="tab-3">
                <h3>About</h3>
            </div>
        </div>
</section>
</div>