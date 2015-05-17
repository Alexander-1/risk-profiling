<?php
/**
 * VDC 24
 *
 * Cloud hosting interface
 * @author Alla Mamontova <alla.mamontova@ubn24.de>
 * @version 1.0 2011
 * @package vdc
 */

/**
 * Validator (to validate variable types)
 *
 * Every method is static and returns TRUE or FALSE
 * @author Alla Mamontova <alla.mamontova@ubn24.de>
 * @version 1.0 2011
 */
class validator
{
/**
 * Validate to unsigned integer
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_unsigned_integer($value)
  {
    if (filter_var($value,FILTER_VALIDATE_INT,array('options' => array('min_range' => 0))) !== false)
      return true;
    else return false;
  }
/**
 * Validate to integer
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_integer($value)
  {
    if (filter_var($value,FILTER_VALIDATE_INT) !== false)
      return true;
    else return false;
  }
/**
 * Validate to float
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_float($value)
  {
    if (filter_var($value,FILTER_VALIDATE_FLOAT) !== false)
      return true;
    else return false;
  }
/**
 * Validate to unsigned float
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_unsigned_float($value)
  {
    if (filter_var($value,FILTER_VALIDATE_FLOAT) !== false && $value >= 0)
      return true;
    else return false;
  }
/**
 * Validate to string
 *
 * WARNING: can be changed later
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_string($value){
    return true;
  }
/**
 * Validate to array of integer
 *
 * @param mixed $array array to validate
 * @return boolean
 */
  public static function is_array_of_integer($array)
  {
    if (is_array($array) && $array){
      foreach ($array as $a)
        if (!self::is_integer($a)) return false;
    }
    else{
      return false;
    }
    return true;
  }
/**
 * Validate to array of unsigned integer
 *
 * @param mixed $array array to validate
 * @return boolean
 */
  public static function is_array_of_unsigned_integer($array)
  {
    if (is_array($array) && $array){
      foreach ($array as $a)
        if (!self::is_unsigned_integer($a)) return false;
    }
    else{
      return false;
    }
    return true;
  }
/**
 * Validate to array of unsigned float
 *
 * @param mixed $array array to validate
 * @return boolean
 */
  public static function is_array_of_unsigned_float($array)
  {
    if (is_array($array)){
      foreach ($array as $key => $value){
        if (!self::is_unsigned_float($value)){
          return false;
        }
      }
      return true;
    }
    else{
      return false;
    }
  }
/**
 * Validate to array of string
 *
 * WARNING: can be changed later
 * @param mixed $array array to validate
 * @return boolean
 */
  public static function is_array_of_string($array)
  {
    if (is_array($array)){
      foreach ($array as $a)
        if (!self::is_string($a)) return false;
      return true;
    }
    else{
      return false;
    }
  }
/**
 * Validate to array of phones
 *
 * WARNING: can be changed later
 * @param mixed $array array to validate
 * @return boolean
 */
  public static function is_array_of_phones($array)
  {
    if (is_array($array)){
      foreach ($array as $a)
        if (!self::is_phone($a) & strnatcmp($a,'') != 0) return false;
      return true;
    }
    else{
      return false;
    }
  }
/**
 * Validate to array of strings which contains only chars (letters, digits, -, _)
 *
 * WARNING: can be changed later
 * @param mixed $array array to validate
 * @return boolean
 */
  public static function is_array_of_only_char($array)
  {
    if (is_array($array)){
      foreach ($array as $a)
        if (!self::is_only_char($a) || strnatcmp($a,'') == 0) return false;
      return true;
    }
    else{
      return false;
    }
  }
/**
 * Validate to array of not null values or not empty strings
 *
 * @param mixed $array array to validate
 * @return boolean
 */
  public static function is_not_empty_array($array)
  {
    foreach ($array as $a){
      if (strnatcmp($a,'') == 0){
        return false;
      }
    }
    return true;
  }
/**
 * Validate to time format like HH:MM
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_time($value)
  {
    $time = explode(':',$value);
    if (count($time) == 2){
      if (preg_match("/[0-9][0-9]/",$time[0]) && $time[0] < 24 && preg_match("/[0-9][0-9]/",$time[1]) && $time[1] < 60){
        foreach ($time as $key => $value){
          $time[$key] = $value + 0;
        }
        if (self::is_array_of_unsigned_integer($time)){
          return true;
        }
      }
    }
    return false;
  }
/**
 * Validate to date format like DD.MM.YYYY or DD/MM/YY etc.
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_date($value)
  {
    if (preg_match("/^((0[1-9])|([12][0-9])|(3[01]))[-.,\/]?((0[1-9])|(1[012]))[-.,\/]?((((19)|(2[012]))[0-9][0-9])|([0-9][0-9]))$/",$value)){
      return true;
    }
    else return false;
  }
/**
 * Validate to array of strings in date format
 *
 * @param mixed $array array to validate
 * @return boolean
 */
  public static function is_array_of_date($array)
  {
    foreach ($array as $a)
      if (!self::is_date($a)) return false;
    return true;
  }
/**
 * Validate to phone format (contains digits, (, ), - , +)
 *
 * @param mixed $phone value to validate
 * @return boolean
 */
  public static function is_phone($phone)
  {
//     if (!preg_match('/^[-0-9+() ]+$/', strtolower($phone))){
//     if (!preg_match('/^[+]?[0-9]{1,4}[ ]*[(]?[ ]*[0-9]{3,5}[ ]*[)]?[ ]*[0-9]{1,3}[ ]*[-]?[ ]*[0-9]{1,3}[ ]*[-]?[ ]*[0-9]{1,3}$/', strtolower($phone))){
    if (!preg_match('/^[+]?[0-9]{0,4}[ ]*[-(]?[ ]*[0-9]{0,5}[ ]*[-)]?[ ]*[0-9]{1,3}[ ]*[-]?[ ]*[0-9]{1,3}[ ]*[-]?[ ]*[0-9]{1,3}$/', strtolower($phone))){
      return false;
    }
    return true;
  }
/**
 * Validate to email format
 *
 * @param mixed $email value to validate
 * @return boolean
 */
  public static function is_email($email){
// if (filter_var($email,FILTER_VALIDATE_EMAIL) !== false){
    if (!preg_match('/^[a-zA-Z0-9][-a-zA-Z0-9._]*@([a-z-A-Z-0-9][-a-z-A-Z0-9_]*\.)+[a-zA-Z]{2,6}$/', strtolower($email))){
      return false;
    }
    return true;
  }
/**
 * Validate to email format without domain and @
 *
 * @param mixed $email value to validate
 * @return boolean
 */
  public static function is_email_without_domain($email){
// if (filter_var($email,FILTER_VALIDATE_EMAIL) !== false){
    if (!preg_match('/^[a-zA-Z0-9][-a-zA-Z0-9._]*$/', strtolower($email))){
      return false;
    }
    return true;
  }
/**
 * Validate to string which contains only chars (letters, digits, -, _)
 *
 * Cyrillic is not allowed
 * WARNING: can be changed later
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_only_char($value){
    if (!preg_match('/^[a-zA-Z0-9]+[-a-zA-Z_0-9]*$/', strtolower($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to user login
 *
 * Cyrillic is not allowed
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_login($value){
    if (!preg_match('/^[a-zA-Z0-9]+$/', strtolower($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to mac-address format
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_mac($value){
    if (!preg_match('/^([0-9A-Fa-f]{2}[:]?){6}$/', strtolower($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to network group name format
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_network_group_name($value){
    if (!preg_match('/^[a-z]{2}\-[a-z]{3}[0-9]{0,2}$/', strtolower($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to directory name name format
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_directory($value){
    if (!preg_match('/^(\/)|(\/[-a-zA-Z_0-9+]+(\/[-a-zA-Z_0-9+]+)*\/?)$/', strtolower($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to sex
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_sex($value){
    $sex = array(0, 1, '0', '1', 'man', 'woman', 'male', 'female');
    if (in_array($value,$sex)){
      return true;
    }
    return false;
  }

  // --- NIC VALIDATION ---

/**
 * Validate to country for nic.ru
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_nic_country($value){ // !!! list of countries !!!
    if (strnatcmp($value,'RU') == 0){
      return true;
    }
    return false;
  }
/**
 * Validate to string of emails
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_string_of_emails($value){
    $emails = explode(',',$value);
    foreach ($emails as $email){
      if (!validator::is_email(trim($email))){
        return false;
      }
    }
    return true;
  }
/**
 * Validate to string (letters, digits, punctuation marks) for nic.ru
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_nic_string($value){
    if (!preg_match('/^[-a-z0-9,.!? `"\']+$/', strtolower($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to latin string (latin letters, uses for public address fields) for nic.ru
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_nic_address_latin($value){
    if (!preg_match('/^[-a-z0-9., ]+$/', strtolower($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to zipcode (index)
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_zipcode($value){
    if (!preg_match('/^[0-9]{6}$/', $value)){
      return false;
    }
    return true;
  }
/**
 * Validate to string with cyrillic (letters, digits, punctuation marks) for nic.ru
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_nic_string_cyr($value){
    if (!preg_match('/^[-a-zA-Zа-яА-ЯЁё0-9 .&!,`"\']+$/u', $value)){
      return false;
    }
    return true;
  }
/**
 * Validate to string that contains only chars (letters&digits) for nic.ru
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_nic_only_char($value){
    if (!preg_match('/^[a-z0-9]+$/', strtolower($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to string that contains phone numbers separated by commas for nic.ru
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_string_of_nic_phones($value){
    $phones = explode(',',$value);
    if ($phones) foreach ($phones as $phone){
      if (!preg_match('/^\+[-0-9() ]{10,256}$/', trim($phone))){
        return false;
      }
    }
    return true;
  }
/**
 * Validate to INN code for organization
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_nic_code_org($value){
    if (!preg_match('/^[0-9]{10}$/', trim($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to INN code for individual ent
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_nic_code_ind($value){
    if (!preg_match('/^[0-9]{12}$/', trim($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to KPP
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_nic_kpp($value){
    if (!preg_match('/^[0-9]{9}$/', trim($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to name of organizaion in english
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_nic_org($value){
    $array = explode(' ',$value);
    if (count($array) < 2){
      return false;
    }
    foreach ($array as $str){
      if (!self::is_nic_string($str)){
        return false;
      }
    }
    return true;
  }
/**
 * Validate to name of organizaion in russian
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_nic_org_r($value){
    $array = explode(' ',$value);
    if (count($array) < 2){
      return false;
    }
    foreach ($array as $str){
      if (!self::is_nic_string_cyr($str)){
        return false;
      }
    }
    return true;
  }
/**
 * Validate to domain zone
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_domain_zone($value){
    if (preg_match('/^[-a-zA-Z0-9.]+$/', trim($value))){
      $row = db::init()->query('id')->from(TABLE_DOMAIN_ZONE)->where(array('zone','=',trim($value)))->get_row();
      if ($row){
        return true;
      }
    }
    return false;
  }
/**
 * Validate to domain without domain zone
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_domain_without_domain_zone($value){
    if (!preg_match('/^(([a-zA-Z0-9])|([a-zA-Z0-9][-a-zA-Z0-9.]*[a-zA-Z0-9])|([а-яА-ЯЁё0-9])|([а-яА-ЯЁё0-9][-а-яА-ЯЁё0-9.]*[а-яА-ЯЁё0-9]))$/u', trim($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to domain
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_domain($value){
    if (!preg_match('/^(([a-zA-Z0-9][-_a-zA-Z0-9]*\.)+([a-zA-Z0-9]+))|(([а-яА-ЯЁё0-9][-_а-яА-ЯЁё0-9]*\.)+([а-яА-ЯЁё0-9]+))$/u', trim($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to network address
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_network_address($value){
    if (!preg_match('/^[-_,. 0-9a-zA-Z]{1,15}$/', trim($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to network network
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_network_network($value){
    return self::is_integer($value);
  }
/**
 * Validate to network netmask
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_network_netmask($value){
    if (!preg_match('/^[-_,. 0-9a-zA-Z]{1,15}$/', trim($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to network gateway
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_network_gateway($value){
    if (!preg_match('/^[-_,. 0-9a-zA-Z]{1,15}$/', trim($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to ip4
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_ip4($value){ // !!! error
    if (!preg_match('/^(((1?[0-9]{1,2})|(2([0-4][0-9])|(5[0-5])))\.){3}((1?[0-9]{1,2})|(2([0-4][0-9])|(5[0-5])))$/', trim($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to ip6
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_ip6($value){
    if (!preg_match('/^([0-9a-fA-F]{4}:){7}[0-9a-fA-F]{4}$/', trim($value))){
      return false;
    }
    return true;
  }
/**
 * Validate to DNS entry TXT with google verification code
 *
 * @param mixed $value value to validate
 * @return boolean
 */
  public static function is_google_code($value){
    if (!preg_match('/^google-site-verification:/', trim($value))){
      return false;
    }
    return true;
  }

}

?>