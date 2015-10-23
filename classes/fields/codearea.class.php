<?php

if( ! defined( 'ABSPATH' ) ) exit;

class Cuztom_Field_Codearea extends Cuztom_Field
{
	var $_supports_repeatable 	= false;
	var $_supports_bundle		= true;
	var $_supports_ajax			= true;

	var $css_classes 			= array( 'codearea','cuztom-input' );
	
	function _output( $value )
	{
		return '<textarea ' . $this->output_name() . ' ' . $this->output_id() . ' ' . $this->output_css_class() . '>' . ( strlen( $value ) > 0 ? $value : $this->default_value ) . '</textarea>' . $this->output_explanation();
	}
}