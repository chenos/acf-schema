<?php 
namespace Yare\ACF;

class Field
{
    public $settings = array(

        'ID' => false,

        /* (string) Unique identifier for the field. Must begin with 'field_' */
        'key' => null,

        /* (string) Visible when editing the field value */
        'label' => null,
        
        /* (string) Used to save and load data. Single word, no spaces. Underscores and dashes allowed */
        'name' => null,
        
        /* (string) Type of field (text, textarea, image, etc) */
        'type' => null,
        
        /* (string) Instructions for authors. Shown when submitting data */
        'instructions' => '',
        
        /* (int) Whether or not the field value is required. Defaults to 0 */
        'required' => 0,
        
        /* (mixed) Conditionally hide or show this field based on other field's values. 
        Best to use the ACF UI and export to understand the array structure. Defaults to 0 */
        'conditional_logic' => 0,
        
        /* (array) An array of attributes given to the field element */
        'wrapper' => array (
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        
        /* (mixed) A default value used by ACF if no value has yet been saved */
        'default_value' => '',
    );

    public $path;

    public function __construct($type, $args, $parent)
    {
        if ($type == 'flex' || $type == 'flexible') {
            $type = 'flexible_content';
        }
        $this->type = $type;
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
        $value = array_shift($args);
        if ($name === 'default') {
            return $this->default_value($value);
        }
        if ($name == 'clone') {
            return $this->clone_fields($value);
        }
        $this->$name = $value;
        return $this;
    }

    public function key($path)
    {
        $this->path = $path;
        $this->key = 'field_' . substr(md5("yare_$path"), 0, 13);
        pc($this->key, "field.key[$path]");
        return $this;
    }

    public function label($label)
    {
        $this->label = ucwords(str_replace(array('-', '_'), ' ', $label));
        return $this;
    }

    public function choices($choices)
    {
        foreach ($choices as $key => $value) {
            $key = is_numeric($key) ? $value : $key;
            $this->settings['choices'][$key] = $value;
        }
        return $this;
    }

    public function clone_fields($clone)
    {
        foreach ($clone as $path) {
            $this->settings['clone'][] = 'field_' . substr(md5("yare_$path"), 0, 13);
        }
        return $this;
    }

    public function post_type() {
        $this->post_type = func_get_args();
        return $this;
    }

    public function sub_fields(callable $callback)
    {
        $fields = new Fields($this);
        $callback($fields);
        $this->sub_fields = $fields->data();
        return $this;
    }

    public function layouts(callable $callback)
    {
        $layouts = new Layouts($this);
        $callback($layouts);
        $this->layouts = $layouts->data();
        return $this;
    }

    public function settings()
    {
        return $this->settings;
    }

    protected function set_second_argument($argument)
    {
        if ($this->type == 'flexible_content' && is_callable($argument)) {
            $this->layouts($argument);
            $this->layout('block');
        } elseif ($this->type == 'repeater' && is_callable($argument)) {
            $this->sub_fields($argument);
            $this->layout('block');
        } elseif (is_array($argument)) {
            if ($this->type == 'clone') {
                $this->clone($argument);
            } else {
                $this->choices($argument);
            }
        } elseif (is_string($argument)) {
            $this->label($argument);
        }
    }

    protected function set_last_argument($argument)
    {
        if (is_array($argument)) {
            if ($this->type == 'clone') {
                $this->clone($argument);
            } else {
                $this->choices($argument);
            }
        } elseif (is_callable($argument)) {
            $this->sub_fields($argument);
            $this->layout('block');
        }
    }

}
