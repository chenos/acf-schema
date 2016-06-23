<?php 
namespace Swp\Schema;

class Layout {
  public $keys = array();

  public function __construct( $keys = array() ) {
    $this->keys['key'] = uniqid();
    $this->keys['display'] = 'block';
    if (empty($keys)) {
      return $this;
    }
    $this->keys = array_merge( $this->keys, $keys );
  }

  public function sub_fields($fields) {
    $this->keys['sub_fields'] = $fields;
    return $this;
  }

  public function display($display) {
    $this->keys['display'] = $display;
    return $this;
  }
}
