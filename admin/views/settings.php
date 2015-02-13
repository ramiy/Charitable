<?php
/**
 * Display the form for the settings pages in the dashboard. 
 */
?>
<form method="POST" action="post.php">
	<?php 
	settings_fields( 'charitable' );
	
	do_settings( 'charitable' );
	
	submit_button();
	?>
</form>