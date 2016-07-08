<?php
/**
 * Display the date filters above the Donations table.
 *
 * @author  Studio 164a
 * @package Charitable/Admin View/Donations Page
 * @since   1.4.0
 */

$campaign_id = isset( $_GET['campaign_id'] ) ? intval( $_GET['campaign_id'] )   : '';

if ( empty( $view_args['actions'] ) )
	return;

$which = isset( $view_args['which'] ) ? $view_args['which'] : 'top';
$two = 'top' == $view_args['which'] ? '' : '2';

echo '<label for="bulk-action-selector-' . esc_attr( $view_args['which'] ) . '" class="screen-reader-text">' . __( 'Select bulk action', 'charitable' ) . '</label>';
echo '<select name="action' . $two . '" id="bulk-action-selector-' . esc_attr( $view_args['which'] ) . "\">\n";
echo '<option value="-1">' . __( 'Bulk Actions', 'charitable' ) . "</option>\n";

foreach ( $view_args['actions'] as $name => $title ) {
	$class = 'edit' === $name ? ' class="hide-if-no-js"' : '';

	echo "\t" . '<option value="' . $name . '"' . $class . '>' . $title . "</option>\n";
}

echo "</select>\n";

submit_button( __( 'Apply', 'charitable' ), 'action', '', false, array( 'id' => "doaction$two" ) );
echo "\n";