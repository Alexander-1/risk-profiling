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
 * Works with a global array $_POST
 *
 * @author Alla Mamontova <alla.mamontova@ubn24.de>
 * @version 1.0 2011
 */
class post {

	public static function passed($params) {
		if (is_array($params)) {
			if ($params)
				foreach ($params as $param) {
					if (!isset($_POST[$param])) {
						return false;
					}
				}
			return true;
		} else {
			if (isset($_POST[$params])) {
				return true;
			}
			return false;
		}
	}

	public static function passed_not_empty($params) {
		if (is_array($params)) {
			if ($params)
				foreach ($params as $param) {
					if (!isset($_POST[$param]) || $_POST[$param] === '') {
						return false;
					}
				}
			return true;
		} else {
			if (isset($_POST[$params]) && $_POST[$params] != '') {
				return true;
			}
			return false;
		}
	}

	public static function is_string($param) {
		return validator::is_string($_POST[$param]);
	}

	public static function is_only_char($param) {
		return validator::is_only_char($_POST[$param]);
	}

	public static function is_login($param) {
		return validator::is_login($_POST[$param]);
	}

	public static function is_integer($param) {
		return validator::is_integer($_POST[$param]);
	}

	public static function is_unsigned_integer($param) {
		return validator::is_unsigned_integer($_POST[$param]);
	}

	public static function is_float($param) {
		return validator::is_float($_POST[$param]);
	}

	public static function get_float($param) {
		return control::get_float($_POST[$param]);
	}

	public static function is_unsigned_float($param) {
		return validator::is_unsigned_float($_POST[$param]);
	}

	public static function is_array_of_integer($param) {
		return validator::is_array_of_integer($_POST[$param]);
	}

	public static function is_array_of_unsigned_integer($param) {
		return validator::is_array_of_unsigned_integer($_POST[$param]);
	}

	public static function is_array_of_unsigned_float($param) {
		return validator::is_array_of_unsigned_float($_POST[$param]);
	}

	public static function is_array_of_string($param) {
		return validator::is_array_of_string($_POST[$param]);
	}

	public static function is_array_of_phones($param) {
		return validator::is_array_of_phones($_POST[$param]);
	}

	public static function get_array_of_phones($param) {
		return control::get_array_of_phones($_POST[$param]);
	}

	public static function get_array_of_strings($param) {
		return control::get_array_of_strings($_POST[$param]);
	}

	public static function get_array_of_unsigned_integer($param) {
		return control::get_array_of_unsigned_integer($_POST[$param]);
	}

	public static function is_array_of_only_char($param) {
		return validator::is_array_of_only_char($_POST[$param]);
	}

	public static function is_not_empty_array($param) {
		return validator::is_not_empty_array($_POST[$param]);
	}

	public static function is_time($param) {
		return validator::is_time($_POST[$param]);
	}

	public static function is_date($param) {
		return validator::is_date($_POST[$param]);
	}

	public static function get_date($param) {
		return control::get_date($_POST[$param]);
	}

	public static function get_date_without_time($param) {
		return control::get_date_without_time($_POST[$param]);
	}

	public static function get_date_without_time_to_db($param) {
		return control::get_date_without_time_to_db($_POST[$param]);
	}

	public static function is_array_of_date($param) {
		return validator::is_array_of_date($_POST[$param]);
	}

	public static function is_phone($param) {
		return validator::is_phone($_POST[$param]);
	}

	public static function is_email($param) {
		return validator::is_email($_POST[$param]);
	}

	public static function get_email($param) {
		return control::get_email($_POST[$param]);
	}

	public static function is_email_without_domain($param) {
		return validator::is_email_without_domain($_POST[$param]);
	}

	public static function get_email_without_domain($param) {
		return control::get_email_without_domain($_POST[$param]);
	}

	public static function get_phone($param) {
		return control::get_phone($_POST[$param]);
	}

	public static function get_only_char($param) {
		return control::get_only_char($_POST[$param]);
	}

	public static function get_login($param) {
		return control::get_login($_POST[$param]);
	}

	public static function get_string($param) {
		return control::get_string($_POST[$param]);
	}

	public static function get_unsigned_integer($param) {
		return control::get_unsigned_integer($_POST[$param]);
	}

	public static function get_as_is($param) {
		return $_POST[$param];
	}

	public static function is_mac($param) {
		return validator::is_mac($_POST[$param]);
	}

	public static function get_mac($param) {
		return control::get_mac($_POST[$param]);
	}

	public static function is_network_group_name($param) {
		return validator::is_network_group_name($_POST[$param]);
	}

	public static function get_network_group_name($param) {
		return control::get_network_group_name($_POST[$param]);
	}

	public static function is_network_address($param) {
		return validator::is_network_address($_POST[$param]);
	}

	public static function get_network_address($param) {
		return control::get_network_address($_POST[$param]);
	}

	public static function is_network_network($param) {
		return validator::is_network_network($_POST[$param]);
	}

	public static function get_network_network($param) {
		return control::get_network_network($_POST[$param]);
	}

	public static function is_network_netmask($param) {
		return validator::is_network_netmask($_POST[$param]);
	}

	public static function get_network_netmask($param) {
		return control::get_network_netmask($_POST[$param]);
	}

	public static function is_network_gateway($param) {
		return validator::is_network_gateway($_POST[$param]);
	}

	public static function get_network_gateway($param) {
		return control::get_network_gateway($_POST[$param]);
	}

	public static function is_directory($param) {
		return validator::is_directory($_POST[$param]);
	}

	public static function get_directory($param) {
		return control::get_directory($_POST[$param]);
	}

	public static function is_sex($param) {
		return validator::is_sex($_POST[$param]);
	}

	public static function get_sex($param) {
		return control::get_sex($_POST[$param]);
	}

	// nic

	public static function is_nic_country($param) {
		return validator::is_nic_country($_POST[$param]);
	}

	public static function get_nic_country($param) {
		return control::get_nic_country($_POST[$param]);
	}

	public static function is_string_of_emails($param) {
		return validator::is_string_of_emails($_POST[$param]);
	}

	public static function get_array_of_emails($param) {
		return control::get_array_of_emails($_POST[$param]);
	}

	public static function is_nic_string_cyr($param) {
		return validator::is_nic_string_cyr($_POST[$param]);
	}

	public static function get_nic_string_cyr($param) {
		return control::get_nic_string_cyr($_POST[$param]);
	}

	public static function is_nic_only_char($param) {
		return validator::is_nic_only_char($_POST[$param]);
	}

	public static function get_nic_only_char($param) {
		return control::get_nic_only_char($_POST[$param]);
	}

	public static function is_nic_string($param) {
		return validator::is_nic_string($_POST[$param]);
	}

	public static function get_nic_string($param) {
		return control::get_nic_string($_POST[$param]);
	}

	public static function is_string_of_nic_phones($param) {
		return validator::is_string_of_nic_phones($_POST[$param]);
	}

	public static function get_array_of_nic_phones($param) {
		return control::get_array_of_nic_phones($_POST[$param]);
	}

	public static function is_domain_zone($param) {
		return validator::is_domain_zone($_POST[$param]);
	}

	public static function get_domain_zone($param) {
		return control::get_domain_zone($_POST[$param]);
	}

	public static function is_domain($param) {
		return validator::is_domain($_POST[$param]);
	}

	public static function get_domain($param) {
		return control::get_domain($_POST[$param]);
	}

	public static function is_domain_without_domain_zone($param) {
		return validator::is_domain_without_domain_zone($_POST[$param]);
	}

	public static function get_domain_without_domain_zone($param) {
		return control::get_domain_without_domain_zone($_POST[$param]);
	}

	public static function is_nic_kpp($param) {
		return validator::is_nic_kpp($_POST[$param]);
	}

	public static function is_nic_org($param) {
		return validator::is_nic_org($_POST[$param]);
	}

	public static function is_nic_org_r($param) {
		return validator::is_nic_org_r($_POST[$param]);
	}

	public static function is_array_to_ip4($param) {
		return validator::is_ip4(implode('.', $_POST[$param]));
	}

	public static function is_array_to_ip6($param) {
		return validator::is_ip6(implode(':', $_POST[$param]));
	}

	public static function is_ip4($param) {
		return validator::is_ip4($_GET[$param]);
	}

	public static function is_ip6($param) {
		return validator::is_ip6($_GET[$param]);
	}

	public static function get_ip4_from_array($param) {
		return control::get_ip4(implode('.', $_POST[$param]));
	}

	public static function get_ip6_from_array($param) {
		return control::get_ip6(implode(':', $_POST[$param]));
	}

	public static function get_ip4($param) {
		return control::get_ip4($_GET[$param]);
	}

	public static function get_ip6($param) {
		return control::get_ip6($_GET[$param]);
	}

}

?>