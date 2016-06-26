<?php
namespace Swp\Schema;

class Schema {

  public static $force = true;

  public static function create($slug, $callback) {
    if ( static::$force ) {
      static::deleteFieldGroup( $slug );
    }
    call_user_func( $callback, $group = new Group( $slug ) );
    if (!static::exists($slug)) {
      acf_import_field_group( $group->container );
    }
    return $group;
  }

  public static function dropAndCreate($slug, $callback) {
    static::deleteFieldGroup( $slug );
    static::group( $slug, $callback );
  }

  public static function deleteFieldGroup( $slug ) {
    $IDs = (array) static::getFieldGroup( $slug );
    foreach ($IDs as $id) {
      acf_delete_field_group($id);
    }
  }

  public static function getFieldGroup( $slug ) {
    global $wpdb;
    $IDs = $wpdb->get_results( "select ID from {$wpdb->posts}
      where post_type = 'acf-field-group'
      and post_title='{$slug}'
      and post_status = 'publish'" );
    return $IDs;
  }

  public static function exists($slug) {
    $group = static::getFieldGroup( $slug );
    return ! empty( $group );
  }

}
