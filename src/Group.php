<?php 
namespace Yare\ACF;

class Group
{
    protected $local;

    public $path;

    public $settings = array(

        'ID' => false,

        /* (string) Unique identifier for field group. Must begin with 'group_' */
        'key' => null,
        
        /* (string) Visible in metabox handle */
        'title' => null,

        /* (array) An array of fields */
        'fields' => array(),
        
        /* (array) An array containing 'rule groups' where each 'rule group' is an array containing 'rules'. 
        Each group is considered an 'or', and each rule is considered an 'and'. */
        'location' => array(),
        
        /* (int) Field groups are shown in order from lowest to highest. Defaults to 0 */
        'menu_order' => 0,
        
        /* (string) Determines the position on the edit screen. Defaults to normal. Choices of 'acf_after_title', 'normal' or 'side' */
        'position' => 'normal',
        
        /* (string) Determines the metabox style. Defaults to 'default'. Choices of 'default' or 'seamless' */
        'style' => 'default',
        
        /* (string) Determines where field labels are places in relation to fields. Defaults to 'top'. 
        Choices of 'top' (Above fields) or 'left' (Beside fields) */
        'label_placement' => 'top',
        
        /* (string) Determines where field instructions are places in relation to fields. Defaults to 'label'. 
        Choices of 'label' (Below labels) or 'field' (Below fields) */
        'instruction_placement' => 'label',
        
        /* (array) An array of elements to hide on the screen */
        'hide_on_screen' => array(),
    );

    public function __construct()
    {
        $this->key = uniqid('group_');
    }

    public function __set($key, $value)
    {
        $this->settings[$key] = $value;
    }

    public function __get($key)
    {
        return isset($this->settings[$key]) ? $this->settings[$key] : null;
    }

    public function __call($name, $args)
    {
        if (array_key_exists($name, $this->settings)) {
            $this->$name = array_shift($args);
        }
    }

    public function key($path)
    {
        $this->path($path);
        $this->key = 'group_' . substr(md5("yare_$path"), 0, 13);
        pc($this->key, "group.key[{$path}]");
        return $this;
    }

    public function path($path)
    {
        $this->path = str_replace('-', '_', sanitize_title($path));
        return $this;
    }

    public function title($title)
    {
        $this->key($title);
        $this->title = $title;
        return $this;
    }

    public function location()
    {
        $args = func_get_args();
        $locations = array();
        foreach ($args as $arg) {
            list($param, $operator, $value) = $arg;
            if ($param == 'page_template' and $value == 'Default') {
                $value = 'default';
            }
            $location['param'] = $param;
            $location['operator'] = $operator;
            $location['value'] = $value;
            $locations[] = $location;
        }
        $this->settings['location'][] = $locations;
        return $this;
    }

    public function hide_on_screen($screen)
    {
        if ( is_string( $screen ) ) {
            $screen = array( $screen );
        }
        if ( func_num_args() !== 1 ) {
            $screen = func_get_args();
        }
        $this->settings['hide_on_screen'] = $screen;
        return $this;
    }

    public function fields(callable $callback)
    {
        $fields = new Fields($this);
        $callback($fields);
        $this->fields = $fields->data();
        return $this;
    }

    public function settings()
    {
        return $this->settings;
    }

    public function local($local)
    {
        $this->local = $local;
        return $this;
    }

    public function register()
    {
        $this->destroy();
        if ($this->local) {
            acf_add_local_field_group($this->settings);
        } else {
            acf_import_field_group($this->settings);
        }
    }

    public function destroy()
    {
        acf_delete_field_group($this->key);
    }

    protected function prepare_field($field)
    {
        return $field->settings();
    }

}
