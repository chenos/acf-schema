<?php 
namespace Swp\Schema;

class Layouts {

  public $layouts;

  public function getAll() {
    return array_map( function( $layout ) {
      return $layout->keys;
    }, $this->layouts );
  }

  public function layout() {
    $args = func_get_args();
    $callback = array_pop( $args );
    call_user_func( $callback, $fields = new Fields() );
    $arguments['name'] = array_shift($args);
    $arguments['label'] = count($args) ? array_shift($args) : $arguments['name'];
    $layout = new Layout($arguments);
    return $this->layouts[] = $layout->sub_fields($fields->getAll());
  }
}
