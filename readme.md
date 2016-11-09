## composer.json

```json
{
  "name": "package/name",
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:chenos/acf-schema.git"
    }
  ],
  "require": {
    "wpsw/acf-schema": "dev-master"
  },
  "config": {
    "preferred-install": "dist",
    "secure-http": false
  }
}
```

## Example

```php
use Yare\ACF\Group;

$group = new Group;

$group->title('Group 1');
$group->location(array('post_type', '==', 'post'));
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
  $fields->flex('flexible_content', function($layouts) {
    $layouts->add('layout1', function($sub_fields) {
      $sub_fields->text('text');
    });
    $layouts->add('layout2', function($sub_fields) {
      $sub_fields->image('image');
    });
  });
  $fields->clone('clone', array('group_1.text'));

});

$group->local(true);
$group->register();
$group->destroy();
```
