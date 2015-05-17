<?php

class log
{
  private $id, $id_user, $name_user, $start_ts, $end_ts, $ip, $id_session;
  
  public function __construct($id = 0){
    if ($id){
      $row = self::get_query_to_construct()->where(array('l.id','=',$id))->get_row();
      if ($row){
        $this->init_from_array($row);
      }
    }
  }
  
  public static function get_query_to_construct(){
    return db::init()->query(array('l.id', 'l.id_user', 'name_user' => 'u.name', 'l.start_ts', 'l.end_ts', 'l.ip', 'l.id_session'))
      ->from(array('l' => 'logs'))
      ->inner_join(array('u' => 'user'), array('u.id', 'l.id_user'));
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
  
//   public function get_day(){
//     $date = explode('.',$this->creation_date);
//     if (isset($date[0])){
//       return $date[0]+0;
//     }
//     return date('j');
//   }
//   
//   public function get_month(){
//     $date = explode('.',$this->creation_date);
//     if (isset($date[1])){
//       return $date[1]+0;
//     }
//     return date('n');
//   }
//   
//   public function get_year(){
//     $date = explode('.',$this->creation_date);
//     if (isset($date[2])){
//       return $date[2]+0;
//     }
//     return date('Y');
//   }
  
  public function set_id($id){
    $this->id = $id;
  }
  
  public function set_id_user($id_user){
    $this->id_user = $id_user;
  }
  
  public function set_name_user($name_user){
    $this->name_user = $name_user;
  }
  
  public function set_start_ts($start_ts){
    $this->start_ts = $start_ts;
  }
  
  public function set_end_ts($end_ts){
    $this->end_ts = $end_ts;
  }
  
  public function set_ip($ip){
    $this->ip = $ip;
  }
  
  public function set_id_session($id_session){
    $this->id_session = $id_session;
  }
  
  public function get_id(){
    return $this->id;
  }

  public function get_id_user(){
    return $this->id_user;
  }

  public function get_name_user(){
    return $this->name_user;
  }

  public function get_start_ts(){
    return $this->start_ts;
  }

  public function get_end_ts(){
    return $this->end_ts;
  }

  public function get_ip(){
    return $this->ip;
  }

  public function get_id_session(){
    return $this->id_session;
  }

}

?>