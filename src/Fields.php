<?php 
namespace Yare\ACF;

class Fields
{
    protected $fields = array();

    // group or field
    protected $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    public function __call($name, $args)
    {
        $field = new Field($name, $args, $this->parent);
        return $this->fields[] = $field;
    }

    public function data()
    {
        return array_map(array($this, 'field_settings'), $this->fields);
    }

    protected function field_settings($field)
    {
        return $field->settings();
    }

}
