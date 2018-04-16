<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * Theme Options Field: Group
 *
 * Grouped options
 *
 * @var   $name  string Group name
 * @var   $field array Group options
 * @var   $params_values array Group values
 * @var   $index int Params in group index
 *
 */

$result_html = '<div class="usof-form-wrapper" data-index="' . $index . '">';
if ( ! empty( $field['title'] ) ) {
	$param_title = $field['title'];
	foreach ( $field['params'] as $param_name => $param ) {
	if ( strpos( $param_title, '{{' . $param_name . '}}' ) !== false ) {
			$param_value = isset( $params_values[$param_name] ) ? $params_values[$param_name] : $field['params'][$param_name]['std'];
			$param_title = str_replace( '{{' . $param_name . '}}', $param_value, $param_title );
		}
	}
	$result_html .= '<div class="usof-form-wrapper-title">' . $param_title . '</div>';
// Output index to avoid empty titles in accordion
} elseif ( isset( $field['is_accordion'] ) AND $field['is_accordion'] ) {
	$result_html .= '<div class="usof-form-wrapper-title">' . $index . '</div>';
}
$param_content_styles = '';
if ( isset( $field['is_accordion'] ) AND $field['is_accordion'] ) {
	$param_content_styles = ' style="display: none;"';
}
$result_html .= '<div class="usof-form-wrapper-content"' . $param_content_styles . '>';
ob_start();
foreach ( $field['params'] as $param_name => $param ) {
	us_load_template(
		'vendor/usof/templates/field', array(
			'name' => $name . '_' . $index . '_' . $param_name,
			'id' => 'usof_' . $name . '_' . $index . '_' . $param_name,
			'field' => $param,
			'values' => $params_values,
		)
	);
}
$result_html .= ob_get_clean();
$result_html .= '</div>';
if ( isset( $field['is_sortable'] ) AND $field['is_sortable'] ) {
	$result_html .= '<div class="usof-form-group-drag"></div>';
}
$result_html .= '<div class="usof-form-group-delete" title="' . us_translate( 'Delete' ) . '"></div>';
$result_html .= '</div>';

echo $result_html;
