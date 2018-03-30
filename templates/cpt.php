<div class="wrap">
    <h1>CPT Manager</h1>
    <?php settings_errors(); ?>

    	<form method="post" action="options.php">
            <?php
                //options group name
                settings_fields( 'ideaProp_plugin_cpt_settings' );
                //menu slug
                do_settings_sections( 'ideaProp_cpt' );
                submit_button();
            ?>
	</form>
</div>