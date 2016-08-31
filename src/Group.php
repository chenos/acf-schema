<?php 
namespace WPSW\Schema;

class Group {

  public $container;

  public function __construct( $title ) {
    $this->container['ID'] = false;
    $this->container['key'] = uniqid('group_');
    $this->container['title'] = $title;
    $this->container['menu_order'] = 0;
    $this->container['position'] = 'normal';
    $this->container['style'] = 'default';
    $this->container['label_placement'] = 'top';
    $this->container['instruction_placement'] = 'label';
    $this->container['active'] = 1;
  }

  public function __call($name, $args) {
    return $this;
  }

  public function fields($callback) {
    call_user_func( $callback, $fields = new Fields() );
    $this->container['fields'] = $fields->getAll();
    return $this;
  }

  public function location() {
    $args = func_get_args();
    $locations = array();
    foreach ($args as $arg) {
      list($param, $operator, $value) = $arg;
      if ($param == 'page_template' and $value != 'default') {
        $path = theme_config( 'theme.templates' ) . '/' . $value;
        if ( file_exists( theme_path( $path ) ) ) {
          $value = $path;
        }
      }
      $location['param'] = $param;
      $location['operator'] = $operator;
      $location['value'] = $value;
      $locations[] = $location;
    }
    $this->container['location'][] = $locations;
    return $this;
  }

  public function position($position) {
    $this->container['position'] = $position;
    return $this;
  }

  public function style($style) {
    $this->container['style'] = $style;
    return $this;
  }

  public function label_placement($placement) {
    $this->container['label_placement'] = $placement;
    return $this;
  }

  public function instruction_placement($placement) {
    $this->container['instruction_placement'] = $placement;
    return $this;
  }

  public function hide_on_screen($screen) {
    $this->container['hide_on_screen'] = $screen;
    return $this;
  }

}
