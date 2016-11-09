<?php 
namespace Yare\ACF;

class Layout
{
    public $path;

    protected $settings = array();

    public function __construct($args, $parent)
    {
        $name = array_shift($args);
        $this->name = str_replace('-', '_', sanitize_title($name));
        $this->label($this->name);
        $this->key($parent->path . '.' . $this->name);
        $this->set_second_argument(array_shift($args));
        if (!empty($args)) {
            $this->set_last_argument(array_shift($args));
        }
    }

    public function __get($key)
    {
        return isset($this->settings[$key]) ? $this->settings[$key] : null;
    }

    public function __set($key, $value)
    {
        $this->settings[$key] = $value;
    }

    public function __call($name, $args)
    {
        $this->$name = array_shift($args);
        return $this;
    }

    public function key($path)
    {
        $this->path = $path;
        $this->key = substr(md5("yare_$path"), 0, 13);
        return $this;
    }

    public function label($label)
    {
        $this->label = ucwords(str_replace(array('-', '_'), ' ', $label));
        return $this;
    }

    public function sub_fields(callable $callback)
    {
        $fields = new Fields($this);
        $callback($fields);
        $this->sub_fields = $fields->data();
        return $this;
    }

    public function settings()
    {
        return $this->settings;
    }

    protected function set_second_argument($argument)
    {
        if (is_callable($argument)) {
            $this->sub_fields($argument);
        } elseif (is_string($argument)) {
            $this->label($argument);
        }
    }

    protected function set_last_argument($argument)
    {
        if (is_callable($argument)) {
            $this->sub_fields($argument);
        }
    }

}
