# Example

```php
use \WPSW\ACF\Schema;

Schema::create('Boilerplate', function($group) {
  $group->location(array('page_template', '==', 'boilerplate.php')); 
  // location "AND" relationship
  $group->location(array('page_template', '==', 'home.php'), array('post_type', '==', 'page')); 
  // location "OR" relationship
  $group->location(array('post_type', '==', 'post'));

  $group->hide_on_screen('the_content', 'excerpt');

  $group->fields(function($fields) {
    // Basic
    $fields->text('text');
    $fields->textarea('textarea');
    $fields->number('number');
    $fields->email('email');
    $fields->url('url');
    $fields->password('password');

    // Content
    $fields->wysiwyg('wysiwyg');
    $fields->oembed('oembed');
    $fields->image('image');
    $fields->file('file');
    $fields->gallery('gallery');

    // Choice
    $fields->true_false('true_false');
    $fields->checkbox('checkbox', array('value1', 'value2'));
    $fields->radio('radio', array('value1', 'value2'));
    $fields->select('select', array('value1', 'value2'));

    // Relational
    $fields->page_link('page_link');
    $fields->post_object('post_object');
    $fields->relationship('relationship');
    $fields->taxonomy('taxonomy');
    $fields->user('user');

    // JQuery
    $fields->google_map('google_map');
    $fields->date_picker('date_picker');
    $fields->date_time_picker('date_time_picker');
    $fields->time_picker('time_picker');
    $fields->color_picker('color_picker');

    // Layout
    $fields->message('message')->message('Message Content');
    $fields->tab('tab');
    $fields->repeater('repeater', function($sub_fields) {
      $sub_fields->text('text');
    });
    //->layout('row') // table/row/block
    $fields->flex('flexible_content', function($flex) {
      $flex->layout('layout1', function($sub_fields) {
        $sub_fields->text('text');
      });
      //->display('block'); // table/row/block
      $flex->layout('layout2', function($sub_fields) {
        $sub_fields->image('image');
      });
      //->display('block'); // table/row/block
    });
  });
});
```


## text/textarea/number/email/url/password

```php
$fields->text('name', 'label') // label optional
  ->instructions('Intro')
  ->default_value('Value')
  ->placeholder('Text');
```

## wysiwyg

```php
$fields->wysiwyg('name', 'Label')
  ->instructions('Intro')
  ->default_value('Value')
  ->toolbar('full');
```