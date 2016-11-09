<?php 
namespace Yare\ACF;

class Layouts
{

    protected $layouts = array();

    // field
    protected $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    public function add()
    {
        $args = func_get_args();
        $layout = new Layout($args, $this->parent);
        return $this->layouts[] = $layout;
    }

    public function data()
    {
        return array_map(array($this, 'layout_settings'), $this->layouts);
    }

    protected function layout_settings($layout)
    {
        return $layout->settings();
    }

}
