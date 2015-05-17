<?php

class action_log
{
  private $id, $id_user, $name_user, $last_name_client, $first_name_client, $id_client, $id_client_form, $action, $post, $date;

  public function __construct($id = 0){
    if ($id){
      $row = self::get_query_to_construct()->where(array('l.id','=',$id))->get_row();
      if ($row){
        $this->init_from_array($row);
      }
    }
  }

  public static function get_query_to_construct(){
    return db::init()->query(array('l.id', 'l.id_user', 'name_user' => 'u.name', 'l.id_client', 'l.id_client_form', 'l.action', 'l.post', 'l.date', 'first_name_client' => 'c.first_name', 'last_name_client' => 'c.last_name'))
      ->from(array('l' => 'action_log'))
        ->inner_join(array('u' => 'user'), array('u.id', 'l.id_user'))
        ->left_join(array('c' => 'client'), array('c.id', 'l.id_client'))
        ;
  }

  public function init_from_array($array){
    if (is_array($array) && $array){
      foreach ($array as $name => $value){
        $method = 'set_'.$name;
        if (method_exists(get_class($this),$method)){
          $this->$method($value);
        }
      }
    }
  }

  public function set_id($id){
    $this->id = $id;
  }

  public function set_id_user($id_user){
    $this->id_user = $id_user;
  }

  public function set_id_client($id_client){
    $this->id_client = $id_client;
  }

  public function set_id_client_form($id_client_form){
    $this->id_client_form = $id_client_form;
  }

  public function set_name_user($name_user){
    $this->name_user = $name_user;
  }

  public function set_first_name_client($name_client){
    $this->first_name_client = $name_client;
  }

  public function set_last_name_client($name_client){
    $this->last_name_client = $name_client;
  }

  public function set_action($action){
    $this->action = $action;
  }

  public function set_post($post){
    $this->post = $post;
  }

  public function set_date($date){
    $this->date = $date;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_id_user(){
    return $this->id_user;
  }

  public function get_id_client(){
    return $this->id_client;
  }

  public function get_id_client_form(){
    return $this->id_client_form;
  }

  public function get_name_user(){
    return $this->name_user;
  }

  public function get_first_name_client(){
    return $this->first_name_client;
  }

  public function get_last_name_client(){
    return $this->last_name_client;
  }

  public function get_action(){
    return $this->action;
  }

  public function get_post(){
    return $this->post;
  }

  public function get_date(){
    return $this->date;
  }

}

?>