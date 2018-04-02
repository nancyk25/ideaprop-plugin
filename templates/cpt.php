<div class="wrap">
	<h1>Property Manager</h1>
	<?php settings_errors(); ?>

	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-1">Your Properties</a></li>
		<li><a href="#tab-2">Add Properties</a></li>
		<li><a href="#tab-3">Export</a></li>
	</ul>

	<div class="tab-content">
		<div id="tab-1" class="tab-pane active">

			<h3>Manage Your Properties</h3>

			
		</div>

		<div id="tab-2" class="tab-pane">
			<form method="post" action="options.php">
				<?php 
					settings_fields( 'ideaProp_plugin_cpt_settings' );
					do_settings_sections( 'ideaProp_cpt' );
					submit_button();
				?>
			</form>
		</div>

		<div id="tab-3" class="tab-pane">
			<h3>Export Your Properties</h3>
		</div>
	</div>
</div>


