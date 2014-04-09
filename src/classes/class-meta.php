<?php

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Cuztom Meta for handling meta data
 *
 * @author 	Gijs Jorissen
 * @since 	1.5
 *
 */
class Cuztom_Meta
{
	var $id;
	var $title;
	var $callback;
	var $data;
	var $fields;
	var $description;

	/**
	 * Construct for all meta types, creates title (and description)
	 * 
	 * @param 	string|array 	$title
	 *
	 * @author  Gijs Jorissen
	 * @since 	1.6.4
	 * 
	 */
	function __construct( $title )
	{
		if( is_array( $title ) )
		{
			$this->title 		= Cuztom::beautify( $title[0] );
			$this->description 	= $title[1];
		}
		else
		{
			$this->title 		= Cuztom::beautify( $title );
		}
	}

	/**
	 * Main callback for meta
	 *
	 * @param 	object 			$post
	 * @param 	object 			$data
	 * @return 	mixed
	 *
	 * @author 	Gijs Jorissen
	 * @since 	0.2
	 *
	 */	
	function callback( $object, $data = array(), $args = array() )
	{
		// Nonce field for validation
		wp_nonce_field( 'cuztom_meta', 'cuztom_nonce' );

		// Get useful data
		$data 		= $this->data;
		$meta_type 	= $this->get_meta_type();
		$object_id 	= $this->object;

		if( ! empty( $data ) )
		{
			echo '<input type="hidden" name="cuztom[__activate]" />';
			echo '<div class="cuztom cuztom-' . $meta_type . '-meta cuztom-meta-' . $object_id . '" data-object-id="' . $object_id . '" data-meta-type="' . $meta_type . '">';
				echo '<table border="0" cellading="0" cellspacing="0" class="form-table cuztom-table cuztom-main-table">';
					if( ! empty( $this->description ) ) 
					{
						echo '<tr><td colspan="2"><div class="cuztom-box-description">' . $this->description . '</div></td></tr>';
						echo '<tr class="cuztom-divider"><td colspan="2"><hr /></td></tr>';
					}

					foreach( $data as $id => $field )
					{
						$this->output_row( $field );
					}
				echo '</table>';
			echo '</div>';
		}
	}

	/**
	 * Outputs a row in a meta table
	 *
	 * @author 	Gijs Jorissen
	 * @since 	3.0
	 */
	function output_row( $field )
	{
		if( ( $field instanceof Cuztom_Tabs ) || ( $field instanceof Cuztom_Accordion ) || ( $field instanceof Cuztom_Bundle ) )
		{
			echo '<tr class="cuztom-divider"><td colspan="2"><hr /></td></tr>';

			echo '<tr class="cuztom-tr">';
				echo '<td class="cuztom-td js-cuztom-field-selector" id="' . $field->id . '" colspan="2">';
					$field->output( $field->value );
				echo '</td>';
			echo '</tr>';

			echo '<tr class="cuztom-divider"><td colspan="2"><hr /></td></tr>';
		}
		else
		{
			if( ! $field instanceof Cuztom_Field_Hidden )
			{
				echo '<tr class="cuztom-tr">';
					echo '<th class="cuztom-th">';
						echo '<label for="' . $field->id . '" class="cuztom-label">' . $field->label . '</label>';
						echo $field->required ? ' <span class="cuztom-required">*</span>' : '';
						echo '<div class="cuztom-field-description">' . $field->description . '</div>';
					echo '</th>';
					echo '<td class="cuztom-td js-cuztom-field-selector" id="' . $field->id . '">';

						if( $field->repeatable && $field->_supports_repeatable )
						{
							echo '<a class="button-secondary cuztom-button js-cuztom-add-sortable" href="#">' . sprintf( '+ %s', __( 'Add', 'cuztom' ) ) . '</a>';
							echo '<ul class="js-cuztom-sortable cuztom-sortable">';
								echo $field->output( $field->value );
							echo '</ul>';
						}
						else
						{
							echo $field->output( $field->value );
						}

					echo '</td>';
				echo '</tr>';

				$divider = true;
			}
			else
			{
				echo $field->output( $field->value );
			}
		}
	}

	/**
	 * Normal save method to save all the fields in a metabox
	 * Metabox and User Meta rely on this method
	 *
	 * @author 	Gijs Jorissen
	 * @since 	2.6
	 */
	function save( $object, $values )
	{
		// Loop through each meta box
		if( ! empty( $this->data ) && isset( $_POST['cuztom'] ) )
		{
			foreach( $this->data as $id => $field )
			{
				if( ( $field instanceof Cuztom_Tabs || $field instanceof Cuztom_Accordion ) && $tabs = $field )
				{
					$tabs->save( $object, $values );
				}
				elseif( $field instanceof Cuztom_Bundle )
				{
					// Get value from values (and apply filters)
					$value 	= isset( $values[$id] ) ? $values[$id] : '';

					// Save
					$field->save( $object, $value );
				}
				else
				{
					if( isset( $field->in_bundle ) && $field->in_bundle ) continue;
				
					// Get value from values (and apply filters)
					$value 	= isset( $values[$id] ) ? $values[$id] : '';

					// Save
					$field->save( $object, $value );
				}
			}
		}
	}

	/**
	 * Check what kind of meta we're dealing with
	 * 
	 * @return  string
	 *
	 * @author 	Gijs Jorissen
	 * @since 	1.5
	 * 
	 */
	function get_meta_type()
	{
		switch( get_class( $this ) ) :
			case 'Cuztom_Meta_Box' : 
				return 'post'; 
				break;
			case 'Cuztom_User_Meta' : 
				return 'user'; 
				break;
			case 'Cuztom_Term_Meta' : 
				return 'term'; 
				break;
			default :
				return false; 
				break;
		endswitch;
	}

	/**
	 * Check what kind of meta we're dealing with
	 * 
	 * @return  string
	 *
	 * @author 	Gijs Jorissen
	 * @since 	2.3
	 * 
	 */
	function is_meta_type( $meta_type )
	{
		return $this->get_meta_type() == $meta_type;
	}

	/**
	 * Get object ID
	 *
	 * @author 	Gijs Jorissen
	 * @since 	3.0
	 * 
	 */
	function get_object_id()
	{
		if( isset( $_GET['post'] ) ) :
			return $_GET['post'];
		elseif( isset( $_GET['user_id'] ) ) :
			return $_GET['user_id'];
		elseif( isset( $_GET['tag_ID'] ) ) :
			return $_GET['tag_ID'];
		else :
			return null;
		endif;

		// TODO: Use get_current_screen()
	}

	/**
	 * Get value bases on field id
	 * 
	 * @return  field
	 *
	 * @author 	Gijs Jorissen
	 * @since 	3.0
	 * 
	 */
	function get_meta_value( $field, $args = array( 'taxonomy' => '' ) )
	{
		switch( $this->get_meta_type() ) :
			case 'post' :
				return get_post_meta( $this->object, $field, true );
				break;
			case 'user' :
				return get_user_meta( $this->object, $field, true );
				break;
			case 'term' :
				return get_cuztom_term_meta( $this->object, isset( $object->taxonomy ) ? $object->taxonomy : null, $field );
				break;
			default :
				return false;
				break;
		endswitch;

		// TODO: Use global getters, so we get a complete array with all values
	}

	/**
	 * This array builds the complete array with the right key => value pairs
	 *
	 * @param 	array 			$data
	 * @return 	array
	 *
	 * @author 	Gijs Jorissen
	 * @since 	1.1
	 *
	 */
	function build( $data, $parent = null )
	{
		$object 		= $this->get_object_id();
		$this->object 	= $object;
		$return 		= array();

		if( is_array( $data ) && ! empty( $data ) )
		{
			foreach( $data as $type => $field )
			{
				// Tabs / accordion
				if( is_string( $type ) && ( $type == 'tabs' || $type == 'accordion' ) )
				{
					$tabs 				= $type == 'tabs' ? new Cuztom_Tabs( $field ) : new Cuztom_Accordion( $field );
					$tabs->meta_type 	= $this->get_meta_type();
					$tabs->object 		= $this->object;

					foreach( $field['fields'] as $title => $fields )
					{
						$tab 				= new Cuztom_Tab( $title );
						$tab->meta_type 	= $this->get_meta_type();
						$tab->object 		= $this->object;

						foreach( $fields as $type => $field )
						{
							if( is_string( $type ) && $type == 'bundle' )
							{
								$bundle 		= $field;
								$tab->fields 	= $this->build( $bundle );
							}
							else
							{
								$class = 'Cuztom_Field_' . str_replace( ' ', '_', ucwords( str_replace( '_', ' ', $field['type'] ) ) );
								if( class_exists( $class ) )
								{
									$field 						= new $class( $field );
									$field->meta_type 			= $this->get_meta_type();
									$field->object 				= $this->object;
									$field->value 				= $this->get_meta_value( $field->id );

									$this->fields[$field->id] 	= $field;
									$tab->fields[$field->id] 	= $field;
								}
							}
						}

						$tabs->tabs[$title] = $tab;
					}

					$return[$tabs->id] = $tabs;
				}

				// Bundle
				elseif( is_string( $type ) && $type == 'bundle' )
				{
					$field 				= array_merge( array( 'id' => $field['id'] ), (array) $field );
					$bundle 			= new Cuztom_Bundle( $field );
					$bundle->meta_type 	= $this->get_meta_type();
					$bundle->object 	= $this->object;

					foreach( $field['fields'] as $type => $field )
					{
						if( is_string( $type ) && $type == 'tabs' )
						{
							$tabs 			= $fields;
							$tab->fields 	= $this->build( $tabs );
						}
						else
						{
							$class = 'Cuztom_Field_' . str_replace( ' ', '_', ucwords( str_replace( '_', ' ', $field['type'] ) ) );
							if( class_exists( $class ) )
							{
								$field = new $class( $field );
								$field->repeatable 		= false;
								$field->ajax 			= false;
								$field->in_bundle 		= true;
								
								$field->meta_type 		= $this->get_meta_type();
								$field->object 			= $this->object;
								$field->value 			= $this->get_meta_value( $field->id );

								$this->fields[$field->id] 	= $field;
								$bundle->fields[$field->id] = $field;
								$bundle->meta_type 			= $this->get_meta_type();
							}
						}
					}

					$return[$bundle->id] = $bundle;
				}

				// Fields
				else
				{
					$class = 'Cuztom_Field_' . str_replace( ' ', '_', ucwords( str_replace( '_', ' ', $field['type'] ) ) );
					if( class_exists( $class ) )
					{
						$field 						= new $class( $field );
						$field->meta_type 			= $this->get_meta_type();
						$field->object 				= $this->object;
						$field->value 				= $this->get_meta_value( $field->id );

						$this->fields[$field->id] 	= $field;
						$return[$field->id] 		= $field;
					}
				}
			}
		}

		return $return;
	}

	/**
	 * Adds multipart support to form
	 *
	 * @return 	mixed
	 *
	 * @author 	Gijs Jorissen
	 * @since 	0.2
	 *
	 */
	static function edit_form_tag()
	{
		echo ' enctype="multipart/form-data"';
	}
}