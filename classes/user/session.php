<?php
class session
{
  private $id;
  private $params = array();
  private $session_lifetime;

  public function __construct(){
    $this->session_lifetime = 3600 * 24;
    $this->delete_old_sessions();
  }

  public function start($new = false) // can be called only from user construct function, just once
  {
    if (!isset($_COOKIE[COOKIE_NAME]) || $new){
      $code = $this->get_code();
      setcookie(COOKIE_NAME, $code, time() + 365 * 24 * 3600, '/', SITE_HOST);
      $this->id = db::init()->exec('session')->values(array('code' => $code, 'last_activity_ts' => time(), 'session' => serialize($this->params)))->return_id('id')->skip_quotes('session')->insert();
    }
    else{
      $row = db::init()->query(array('id', 'session'))->from('session')->where(array('code','=',$_COOKIE[COOKIE_NAME]))->get_row();
      if ($row){
        $this->id = $row['id'];
        $this->params = unserialize($row['session']);
      }
      else{
        $this->start(true);
      }
    }
    if ($this->id){
      $this->touch();
    }
  }

  public function set_id_client_user($id){
    db::init()->exec('session')->values(array('id_client_user' => $id))->where(array('code','=',$_COOKIE[COOKIE_NAME]))->update();
  }

  public function clear_id_client_user($id){
    db::init()->exec('session')->values(array('id_client_user' => 'NULL'))->where(array('code','=',$_COOKIE[COOKIE_NAME]))->update();
  }

  private function touch(){
    db::init()->exec('session')->values(array('last_activity_ts' => time()))->where(array('id','=',$this->id))->update();
  }

  private function get_code(){
    return md5(rand(0,1000000));
  }

  public function get_id(){
    return $this->id;
  }

  private function delete_old_sessions(){
    db::init()->exec('session')->where(array('last_activity_ts','<',time() - $this->session_lifetime))->delete();
  }

  public function get($name){
    $this->touch();
    if (isset($this->params[$name])){
      return $this->params[$name];
    }
    return false;
  }

  public function set($name, $value){
    $this->params[$name] = $value;
    $this->remember();
  }

  public function del($name){
    if (isset($this->params[$name])){
      unset($this->params[$name]);
      $this->remember();
    }
  }

  private function remember(){
    db::init()->exec('session')->values(array('session' => serialize($this->params)))->where(array('id','=',$this->id))->skip_quotes('session')->update();
    $this->touch();
  }

  public function clear(){
    $this->params = array();
    $this->remember();
  }
}

?>
