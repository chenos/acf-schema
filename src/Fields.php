<?php 
namespace WPSW\ACF;

class Fields {

  public function __construct( $parent = null ) {
    $this->parent = $parent;
  }

  public function getAll() {
    return array_map( function( $field ) {
      return $field->container;
    }, $this->fields );
  }

  public function repeater() {
    $args = func_get_args();
    $callback = array_pop( $args );
    $field = new Field( 'repeater', $args, $this->parent );
    $field->layout('row');
    return $this->fields[] = $field->sub_fields( $callback );
  }

  public function flex() {
    return call_user_func_array( array( $this, 'flexible_content' ), func_get_args() );
  }

  public function flexible_content() {
    $args = func_get_args();
    $callback = array_pop( $args );
    $field = new Field( 'flexible_content', $args, $this->parent );
    return $this->fields[] = $field->layouts( $callback );
  }

  public function __call( $name, $args ) {
    return $this->fields[] = new Field( $name, $args, $this->parent );
  }

}
