<?php 
namespace WPSW\Schema;

class Field {

  public static $enum = array( 'checkbox', 'radio', 'select' );

  public $container = array();

  public function __construct( $name, $args, $parent = null ) {

    $container = array('type' => $name, 'key' => uniqid('field_'));

    if ( $parent ) {
      $container['parent'] = $parent;
    }

    switch (count($args)) {
      case 1:
        $container['name'] = $container['label'] = array_shift($args);
        break;

      case 2:
        $container['name'] = array_shift($args);
        $last_value = array_pop($args);

        if ( is_string($last_value) ) {
        $container['label'] = $last_value;
        }

        if ( in_array( $name, static::$enum ) && is_array( $last_value ) ) {
          $container['label'] = $container['name'];
          foreach ($last_value as $key => $value) {
            $key = is_string($key) ? $key : $value;
            $container['choices'][ sanitize_title( $key ) ] = $value;
          }
          //$container['choices'] = acf_decode_choices( $last_value );
        }
        break;

      case 3:
        $container['name'] = array_shift( $args );
        $container['label'] = array_shift( $args );
        $last_value = array_pop($args);
        if ( in_array( $name, static::$enum ) && is_array( $last_value ) ) {
          $container['label'] = $container['name'];
          foreach ($last_value as $key => $value) {
            $key = is_string($key) ? $key : $value;
            $container['choices'][ sanitize_title( $key ) ] = $value;
          }
        }
        break;

      default:
        # code...
        break;
    }

    $this->container = acf_prepare_field( acf_get_valid_field($container) );
    unset($this->container['parent']);
  }

  public function default_value( $value ) {
    $this->container['default_value'] = $value;
    return $this;
  }

  public function post_type() {
    $this->container['post_type'] = func_get_args();
  }

  public function layout($layout) {
    $this->container['layout'] = $layout;
    return $this;
  }

  public function layouts( $callback ) {
    call_user_func( $callback, $layouts = new layouts() );
    $this->container['layouts'] = $layouts->getAll();
    return $this;
  }

  public function sub_fields( $callback ) {
    call_user_func( $callback, $fields = new fields( $this->container['key'] ) );
    $this->container['sub_fields'] = $fields->getAll();
    return $this;
  }

  public function __call($name, $args) {
    if ($name === 'default') {
      return $this->default_value(array_shift($args));
    }
  }
}
