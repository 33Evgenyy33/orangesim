<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options Field: Color
 *
 * Simple color picker
 *
 * @var   $name  string Field name
 * @var   $id    string Field ID
 * @var   $field array Field options
 *
 * @param $field ['title'] string Field title
 * @param $field ['description'] string Field title
 * @param $field ['text'] string Field additional text
 *
 * @var   $value string Current value
 */

if ( preg_match( '~^\#([\da-f])([\da-f])([\da-f])$~', $value, $matches ) ) {
	$value = '#' . $matches[1] . $matches[1] . $matches[2] . $matches[2] . $matches[3] . $matches[3];
}

$theme = wp_get_theme();
if ( is_child_theme() ) {
	$theme = wp_get_theme( $theme->get( 'Template' ) );
}

$theme_name = $theme->get( 'Name' );
$palette = get_option( 'usof_color_palette_' . $theme_name );
if ( ! is_array( $palette ) ) {
	$palette = array();
}
unset( $theme, $theme_name );
$output = '<div class="usof-color">';
$output .= '<div class="usof-color-preview" style="background: ' . $value . '"></div>';
$output .= '<input class="usof-color-value" type="text" name="' . $name . '" value="' . esc_attr( $value ) . '" />';
$output .= '<div class="usof-color-clear" title="' . us_translate( 'Clear' ) . '"></div>';

$output .= '<div class="colpick_wrap">';
$output .= '<div class="colpick_palette">';
foreach ( $palette as $color ) {
	$output .= '<div class="colpick_palette_value"><span style="background:' . $color . '" title="' . $color . '"></span><div class="colpick_palette_delete" title="' . us_translate( 'Delete' ) . '"></div></div>';
}
$output .= '<div class="colpick_palette_add" title="' . __( 'Add the current color to the palette', 'us' ) . '"></div>';
$output .= '</div></div></div>';

if ( isset( $field['text'] ) AND ! empty( $field['text'] ) ) {
	$output .= '<div class="usof-color-text">' . $field['text'] . '</div>';
}
echo $output;
