<?php

class db
{
	private static $init;
	private $connection;

	private function __construct(){
		$this->connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME, ENCODING);
	}

	public function __destruct(){
		$this->disconnect();
	}

	public static function init(){
		if (self::$init === null) {
			self::$init = new self();
		}
		return self::$init;
	}

	private function connect($host, $user, $pass, $db, $encoding){
		$this->connection = mysql_connect($host,$user,$pass);
		$this->connection = mysql_connect($host, $user, $pass, TRUE);
		mysql_select_db($db, $this->connection) or die(mysql_error());

// 		die(var_dump($this->connection));
		if(!$this->connection){
			return false;
		}
		else{
			mysql_select_db($db,$this->connection);
			mysql_query('SET NAMES '.$encoding);
			return true;
		}
	}

	private function disconnect(){
		mysql_close($this->connection);
	}

	public function query($fields = array()){
		$query = new query_result($fields);
		return $query;
	}

	public function exec($from){
		$exec = new exec_result($from);
		return $exec;
	}

	public function start_transaction(){
		mysql_query('SET AUTOCOMMIT=0');
		mysql_query('START TRANSACTION');
	}

	public function end_transaction(){
		mysql_query('COMMIT');
		mysql_query('SET AUTOCOMMIT=1');
	}

	public function rollback(){
		mysql_query('ROLLBACK');
	}
}

class exec_result
{
	const INSERT = 1;
	const UPDATE = 2;
	const DELETE = 3;
	const ISNULL = 'IS NULL';
	const BETWEEN = 'BETWEEN';
	const NOT_ISNULL = '!ISNULL';
	const LIKE = 'LIKE';
	const NOT_LIKE = 'NOT LIKE';
	const BEGINS = '$LIKE';
	const IN = 'IN';
	const NOT_IN = 'NOT IN';
	const NOW = 'NOW()';
	const NULL = 'NULL';

	private $where_signs = array('<>' => '!=', '!=' => '!=', '=' => '=', '>=' => '>=', '<=' => '<=', '>' => '>', '<' => '<', 'ISNULL' => 'IS NULL', 'IS NULL' => 'IS NULL', 'isnull' => 'IS NULL', 'is_null' => 'IS NULL', '!ISNULL' => '!ISNULL', '!IS NULL' => '!ISNULL', '!isnull' => '!ISNULL', '!is_null' => '!ISNULL', 'in' => 'IN', 'IN' => 'IN', 'not_in' => 'NOT IN', 'not in' => 'NOT IN', 'NOT_IN' => 'NOT IN', 'NOT IN' => 'NOT IN', 'notin' => 'NOT IN', 'NOTIN' => 'NOT IN', 'between' => 'BETWEEN');
	private $where_operators = array('OR' => 'OR', 'AND' => 'AND', 'or' => 'OR', 'and' => 'AND', '||' => 'OR', '&&' => 'AND', '&' => 'AND');
	private $nulls = array('null' => 'NULL', 'NULL' => 'NULL', 'Null' => 'NULL');

	private $from; // string - table_name
	private $where; // it's hard to explain, see code below
	private $limit; // array( 'limit' => 'limit', 'offset' => 'offset')
	private $values; // array('field1' => 'value1', 'field2' => 'value2', ...)
	private $primary_key; // field_name if need to return

	private $exec_type;

	private $skip_quotes = array();

	public function __construct($from){
		if (is_string($from)){
			$this->from = $from;
		}
		return $this;
	}

	public function where($where){
		$this->where[] = $this->get_condition($where);
		return $this;
	}

	private function get_condition($where)
	{
		$condition = array();

		if ($where){
			if (isset($where[0]) && is_string($where[0])){
				if (isset($where[1]) && in_array($where[1],array_keys($this->where_signs))){
					$where[1] = $this->where_signs[$where[1]];
					$condition = $where;
				}
			}
			elseif (is_array($where)){
				foreach ($where as $w){
					$condition[] = $this->get_condition($w);
				}
			}
		}

		return $condition;
	}

	public function values($values){
		if (is_array($values)){
			$this->values = $values;
		}
		return $this;
	}

	public function skip_quotes($fields){
		if (is_array($fields)) foreach ($fields as $field){
			if (is_string($field) && !in_array($field,$this->skip_quotes)){
				$this->skip_quotes[] = $field;
			}
		}
		elseif (is_string($fields) && !in_array($fields,$this->skip_quotes)){
			$this->skip_quotes[] = $fields;
		}
		return $this;
	}

	public function return_id($primary_key){
		if (is_string($primary_key)){
			$this->primary_key = $primary_key;
		}
		return $this;
	}

	public function insert($debug = 0){
		$this->exec_type = self::INSERT;
		$sql = $this->construct_exec();
		switch ($debug){
			case 1: die("$sql"); break;
			case 2: return $sql;
			default:
				$result = mysql_query($sql);
				if ($result && $this->primary_key){
					return mysql_insert_id();
				}
		}
	}

	public function update($debug = 0){
		$this->exec_type = self::UPDATE;
		$sql = $this->construct_exec();
		switch ($debug){
			case 1: die("$sql"); break;
			case 2: return $sql;
			default:
				$result = mysql_query($sql);
				if ($result && $this->primary_key){
					return mysql_insert_id();
				}
		}
	}

	public function delete($debug = 0){
		$this->exec_type = self::DELETE;
		$sql = $this->construct_exec();
		switch ($debug){
			case 1: die("$sql"); break;
			case 2: return $sql;
			default:
				$result = mysql_query($sql);
				if ($result && $this->primary_key){
					return mysql_insert_id();
				}
		}
	}

	private function construct_exec()
	{
		$sql = '';

		$from = $this->get_field_with_quotes(DB_PREFIX.$this->from);

		$primary_key = '';
//     if ($this->primary_key){
//       $primary_key = " RETURNING \"{$this->primary_key}\" ";
//     }

		$where = $this->get_condition_to_query();

		$limit = '';
		if ($this->limit){
			$limit = " LIMIT {$this->limit['offset']}, {$this->limit['limit']} ";
		}

		switch ($this->exec_type){
			case self::INSERT:
				$fields = array();
				$values = array();
				if ($this->values) foreach ($this->values as $field => $value){
					if (!in_array($field,$this->skip_quotes)){
						$value = str_replace('"','``',$value);
						$value = str_replace("'",'`',$value);
					}
					$fields[] = "`$field`";
					$values[] = "'$value'";
				}
				$fields = implode(', ',$fields);
				$values = implode(', ',$values);

				$sql = "INSERT INTO $from ($fields) VALUES ($values) $primary_key";
				break;
			case self::UPDATE:
				$values = '';
				if ($this->values){
					$arr = array();
					foreach ($this->values as $name => $value){
						if (!in_array($name,$this->skip_quotes)){
							$value = str_replace('"','``',$value);
							$value = str_replace("'",'`',$value);
						}
						if (in_array($value,array_keys($this->nulls))){
							$value = $this->nulls[$value];
						}

//             if (substr_count($value,'#')){
//               $value = str_replace('#','',$value);
//               $value = $this->get_field_with_quotes($value);
//             }
						elseif (strnatcmp($value,self::NULL) != 0){
							$value = "'$value'";
						}
						$arr[] = "`$name` = $value";
					}
					$values = implode(', ', $arr);
				}
				$sql = "UPDATE $from SET $values $where $primary_key $limit";
				break;
			case self::DELETE:
				$sql = "DELETE FROM $from $where $limit";
				break;
		}
		return $sql;
	}

	private function get_condition_to_query($where = null, $i = 0)
	{
		$i++;
		$condition = '';

		if (is_null($where)){
			$where = $this->where;
			$condition = 'WHERE ';
		}
		$array = array();
		if (isset($where[0]) && is_array($where[0]) && $where[0]){
			foreach ($where as $w){
				$array[] = $this->get_condition_to_query($w,$i);
			}
			$condition .= '( '.implode(' '.$this->get_current_operator($i).' ',$array).' )';
		}
		elseif ($where && is_string($where[0])){
			$where[0] = $this->get_field_with_quotes($where[0]);

			if ($where[1] == self::ISNULL){
				$where[2] = '';
			}
			elseif ($where[1] == self::BETWEEN){
				$where[0] = 'DATE('.$where[0].')';
				$where[1] = self::BETWEEN;
				$where[2] = "'".$where[2]."' AND '".(isset($where[3]) ? $where[3] : date('Y-m-d'))."'";
			}
			elseif ($where[1] == self::NOT_ISNULL){
				$where[0] = 'NOT '.$where[0];
				$where[1] = self::ISNULL;
				$where[2] = '';
			}
			elseif ($where[1] == self::LIKE){
				$where[2] = "'%".$where[2]."%'";
			}
			elseif ($where[1] == self::NOT_LIKE){
				$where[2] = "'%".$where[2]."%'";
			}
			elseif ($where[1] == self::BEGINS){
				$where[2] = "'".$where[2]."%'";
			}
			elseif ($where[1] == self::IN){
				if (is_array($where[2])){
					$where[2] = "('".implode("','",$where[2])."')";
				}
				elseif (is_string($where[2])){
					if ($where[2] == self::NOW){
						$where[2] = "(".$where[2].")";
					}
					else{
						$where[2] = "('".$where[2]."')";
					}
				}
				elseif ($where[2] instanceof query_result){
					$where[2] = '('.$where[2]->get_all(2).')';
				}
			}
			elseif ($where[1] == self::NOT_IN){
				if (is_array($where[2])){
					$where[2] = "('".implode("','",$where[2])."')";
				}
				elseif (is_string($where[2])){
					$where[2] = "('".$where[2]."')";
				}
				elseif ($where[2] instanceof query_result){
					$where[2] = '('.$where[2]->get_all(2).')';
				}
			}
//       elseif (is_string($where[2]) && substr_count($where[2],'#')){
//         $where[2] = str_replace('#','',$where[2]);
//         $where[2] = $this->get_field_with_quotes($where[2]);
//       }
			else{
				if ($where[2] == self::NOW){
					$where[2] = "{$where[2]}";
				}
				else{
					$where[2] = "'{$where[2]}'";
				}
			}
			$condition .= " ({$where[0]} {$where[1]} {$where[2]}) ";
		}
		else{
			$condition = '';
		}

		return $condition;
	}

	private function get_current_operator($i)
	{
		$operators = array_unique(array_values($this->where_operators));
		$current_operator = $operators[$i % count($operators)];
		return $current_operator;
	}

	private function get_field_with_quotes($field)
	{
		if (!substr_count($field,'+') && !substr_count($field,'-') && !substr_count($field,'*') && !substr_count($field,"'") && !substr_count($field,'"')){
			$arr = explode('.',$field);
			foreach ($arr as $k => $a){
				$arr[$k] = "`{$arr[$k]}`";
			}
			$field = implode('.',$arr);
		}
		return $field;
	}
}


class query_result
{
	const INNER_JOIN = 'INNER JOIN';
	const LEFT_JOIN = 'LEFT OUTER JOIN';
	const RIGHT_JOIN = 'RIGHT OUTER JOIN';
	const DEFAULT_OPERATOR = 'AND';
	const DEFAULT_ORDER = 'ASC';
	const ISNULL = 'IS NULL';
	const BETWEEN = 'BETWEEN';
	const NOT_ISNULL = '!ISNULL';
	const LIKE = 'LIKE';
	const NOT_LIKE = 'NOT LIKE';
	const BEGINS = '$LIKE';
	const IN = 'IN';
	const NOT_IN = 'NOT IN';
	const NOW = 'NOW()';

	private $where_signs = array('<>' => '!=', '!=' => '!=', '=' => '=', '>=' => '>=', '<=' => '<=', '>' => '>', '<' => '<', 'ISNULL' => 'IS NULL', 'IS NULL' => 'IS NULL', 'isnull' => 'IS NULL', 'is_null' => 'IS NULL', '!ISNULL' => '!ISNULL', '!IS NULL' => '!ISNULL', '!isnull' => '!ISNULL', '!is_null' => '!ISNULL', 'in' => 'IN', 'IN' => 'IN', 'not in' => 'NOT IN', 'not_in' => 'NOT IN', 'notin' => 'NOT IN', 'NOT IN' => 'NOT IN', 'NOT_IN' => 'NOT IN', 'NOTIN' => 'NOT IN', 'LIKE' => 'LIKE', 'like' => 'LIKE', 'NOTLIKE' => 'NOT LIKE', 'NOT LIKE' => 'NOT LIKE', 'NOT_LIKE' => 'NOT LIKE', 'notlike' => 'NOT LIKE', 'not like' => 'NOT LIKE', 'not_like' => 'NOT LIKE', 'BEGINS' => '$LIKE', 'begins' => '$LIKE', 'between' => 'BETWEEN');
	private $where_operators = array('OR' => 'OR', 'AND' => 'AND', 'or' => 'OR', 'and' => 'AND', '||' => 'OR', '&&' => 'AND', '&' => 'AND');
	private $orders = array('ASC' => 'ASC', 'DESC' => 'DESC', 'asc' => 'ASC', 'desc' => 'DESC');

	private $fields; // array of name_fields
	private $from; // array('alias_1' => 'table_name_1', 'alias_2' => 'table_name_2', ...)
	private $where; // it's hard to explain, see code below
	private $join; // array of arrays like (0 => array('alias' => 'table_name'), 1 => array(0 => 'field_join_on_1', 1 => 'field_join_on_2'))
	private $limit; // array( 'limit' => 'limit', 'offset' => 'offset')
	private $order; // array of arrays like ('field' => 'field', 'order' => 'order')
	private $group; // array of name_fields
	private $distinct = false;

	public function __construct($fields){
		if (is_string($fields)){
			$this->fields = array($fields => $fields);
		}
		elseif (is_array($fields)){
			if ($fields) foreach ($fields as $alias => $field){
				if (is_string($alias)){
					$this->fields[$alias] = $field;
				}
				else{
					$this->fields[$field] = $field;
				}
			}
		}
	}

	public function from($from){ // string = name table or array of names tables (strings), keys - aliases
		if (is_string($from)){
			$this->from = array($from => $from);
		}
		elseif (is_array($from)){
			foreach ($from as $alias => $name){
				if (is_string($alias)){
					$this->from[$alias] = $name;
				}
				else{
					$this->from[$name] = $name;
				}
			}
		}
		return $this;
	}

	public function inner_join($table, $fields){
		return $this->join($table, $fields, self::INNER_JOIN);
	}

	public function left_join($table, $fields){
		return $this->join($table, $fields, self::LEFT_JOIN);
	}

	public function right_join($table, $fields){
		return $this->join($table, $fields, self::RIGHT_JOIN);
	}

	private function join($table, $fields, $type_join){
		if (is_string($table)){
			$join = array(array($table => $table), $fields, $type_join);
			$this->join[] = $join;
		}
		elseif (is_array($table)){
			foreach ($table as $alias => $name){
				if (is_string($alias)){
					$join[0] = array($alias => $name);
				}
				else{
					$join[0] = array($name => $name);
				}
				$join[] = $fields;
				$join[] = $type_join;
				$this->join[] = $join;
				break;
			}
		}
		return $this;
	}

	private function get_condition($where)
	{
		$condition = array();

		if ($where){
			if (isset($where[0]) && is_string($where[0])){
				if (isset($where[1]) && in_array($where[1],array_keys($this->where_signs))){
					$where[0] = str_replace('MIN(','LEAST(',$where[0]);
					$where[1] = $this->where_signs[$where[1]];
					$condition = $where;
				}
			}
			elseif (is_array($where)){
				foreach ($where as $w){
					$condition[] = $this->get_condition($w);
				}
			}
		}

		return $condition;
	}

	public function where($where){
		$this->where[] = $this->get_condition($where);
		return $this;
	}

	public function limit($limit, $offset = 0){
		if (is_array($limit)){
			if (isset($limit[0]) && isset($limit[1])){
				$this->limit = array('limit' => $limit[0], 'offset' => $limit[1]);
			}
			elseif (isset($limit[0])){
				$this->limit = array('limit' => $limit[0], 'offset' => 0);
			}
			elseif (isset($limit['limit']) && isset($limit['offset'])){
				$this->limit = array('limit' => $limit['limit'], 'offset' => $limit['offset']);
			}
			elseif (isset($limit['limit'])){
				$this->limit = array('limit' => $limit['limit'], 'offset' => 0);
			}
		}
		elseif ($offset){
			$this->limit = array('limit' => $limit, 'offset' => $offset);
		}
		else{
			$this->limit = array('limit' => $limit, 'offset' => 0);
		}
		return $this;
	}

	public function order($order, $asc = ''){
		if (is_array($order) && ((isset($order[1]) && in_array($order[1], array_keys($this->orders))) || (isset($order['order']) && in_array($order['order'], array_keys($this->orders)))) ){
			$order['field'] = $order[0];
			$order['order'] = (isset($order[1]) && in_array($order[1], array_keys($this->orders))) ? $this->orders[$order[1]] : $this->orders[$order['order']];
			$this->order[] = array('field' => $order['field'], 'order' => $order['order']);
		}
		elseif (is_array($order) && isset($order[0])){
			$this->order[] = array('field' => $order[0], 'order' => self::DEFAULT_ORDER);
		}
		elseif (is_string($order)){
			$ord = self::DEFAULT_ORDER;
			if (strnatcmp($asc,'') != 0 && isset($this->orders[$asc])){
				$ord = $this->orders[$asc];
			}
			$this->order[] = array('field' => $order, 'order' => $ord);
		}
		return $this;
	}

	public function group($group){
		if (is_array($group)) foreach ($group as $g){
			if (is_string($g)){
				$this->group[] = $g;
			}
		}
		elseif (is_string($group)){
			$this->group[] = $group;
		}
		return $this;
	}

	public function distinct(){
		$this->distinct = true;
		return $this;
	}

	public function get_all($debug = 0)
	{
		$sql = $this->construct_select();
		switch ($debug){
			case 1: die("$sql"); break;
			case 2:  return $sql;
			default:
				$rows = array();
				$result = mysql_query($sql);
				if ($result) while ($row = mysql_fetch_assoc($result)) $rows[] = $row;
				return $rows;
		}
	}

	public function get_row($debug = 0)
	{
		$sql = $this->construct_select();
		switch ($debug){
			case 1: die("$sql"); break;
			case 2:  return $sql;
			default:
				$result = mysql_query($sql);
				if ($result && $row = mysql_fetch_assoc($result)) return $row;
				else return false;
		}
	}

	private function construct_select()
	{
		$sql = '';

		if (!$this->fields){
			$fields = '*';
		}
		else{
			$fields = array();
			foreach ($this->fields as $alias => $name){
				$name = $this->get_field_with_quotes($name);
				$alias = $this->get_field_with_quotes($alias);
				if ($alias === $name){
					$fields[] = $name;
				}
				else{
					$fields[] = "$name AS $alias";
				}
			}
			$fields = implode(', ',$fields);
		}

		$from = array();
		foreach ($this->from as $alias => $table){
			if ($table === $alias){
				$from[] = "`".DB_PREFIX."$table`";
			}
			else{
				$from[] = "`".DB_PREFIX."$table` `$alias`";
			}
		}
		$from = implode(', ',$from);

		$join = '';
		if ($this->join) foreach ($this->join as $j){
			$table = $j[0];
			$fields_on = $j[1];
			foreach ($fields_on as $key => $field){
				$fields_on[$key] = $this->get_field_with_quotes($fields_on[$key]);
			}
			$type_join = $j[2];
			foreach ($table as $alias => $name){
				if ($name === $alias){
					$join .= " $type_join `".DB_PREFIX."$name` ON {$fields_on[0]} = {$fields_on[1]}";
				}
				else{
					$join .= " $type_join `".DB_PREFIX."$name` `$alias` ON {$fields_on[0]} = {$fields_on[1]}";
				}
			}
		}

		$where = $this->get_condition_to_query();

		$limit = '';
		if ($this->limit){
			$limit = " LIMIT {$this->limit['offset']}, {$this->limit['limit']} ";
		}

		$order = '';
		if ($this->order){
			foreach ($this->order as $ord){
				$order .= ', '.$this->get_field_with_quotes($ord['field'])." {$ord['order']}";
			}
			$order = 'ORDER BY '.substr($order,1);
		}

		$group = '';
		if ($this->group){
			foreach ($this->group as $g){
				$gr[] = $this->get_field_with_quotes($g);
			}
			$group = ' GROUP BY '.implode(', ',$gr);
		}
		$distinct = '';
		if ($this->distinct){
			$distinct = 'DISTINCT';
		}

		$sql = "SELECT $distinct $fields FROM $from $join $where $group $order $limit";

		return $sql;
	}

	private function get_condition_to_query($where = null, $i = 0)
	{
		$i++;
		$condition = '';

		if (is_null($where)){
			$where = $this->where;
			$condition = 'WHERE ';
		}
		$array = array();
		if (isset($where[0]) && is_array($where[0]) && $where[0]){
			foreach ($where as $w){
				$array[] = $this->get_condition_to_query($w,$i);
			}
			$condition .= '( '.implode(' '.$this->get_current_operator($i).' ',$array).' )';
		}
		elseif ($where && is_string($where[0])){
			$where[0] = $this->get_field_with_quotes($where[0]);

			if ($where[1] == self::ISNULL){
				$where[2] = '';
			}
			elseif ($where[1] == self::NOT_ISNULL){
				$where[0] = 'NOT '.$where[0];
				$where[1] = self::ISNULL;
				$where[2] = '';
			}
			elseif ($where[1] == self::BETWEEN){
				$where[0] = 'DATE('.$where[0].')';
				$where[1] = self::BETWEEN;
				$where[2] = "'".$where[2]."' AND '".(isset($where[3]) ? $where[3] : date('Y-m-d'))."'";
			}
			elseif ($where[1] == self::LIKE){
				$where[2] = "'%".$where[2]."%'";
			}
			elseif ($where[1] == self::NOT_LIKE){
				$where[2] = "'%".$where[2]."%'";
			}
			elseif ($where[1] == self::BEGINS){
				$where[2] = "'".$where[2]."%'";
			}
			elseif ($where[1] == self::IN){
				if (is_array($where[2])){
					$where[2] = "('".implode("','",$where[2])."')";
				}
				elseif (is_string($where[2])){
					$where[2] = "('".$where[2]."')";
				}
				elseif ($where[2] instanceof query_result){
					$where[2] = '('.$where[2]->get_all(2).')';
				}
			}
			elseif ($where[1] == self::NOT_IN){
				if (is_array($where[2])){
					$where[2] = "('".implode("','",$where[2])."')";
				}
				elseif (is_string($where[2])){
					$where[2] = "('".$where[2]."')";
				}
				elseif ($where[2] instanceof query_result){
					$where[2] = '('.$where[2]->get_all(2).')';
				}
			}
//       elseif (substr_count($where[2],'#')){
//         $where[2] = str_replace('#','',$where[2]);
//         $where[2] = $this->get_field_with_quotes($where[2]);
//       }
			else{
				if ($where[2] == self::NOW){
					$where[2] = "{$where[2]}";
				}
				else{
					$where[2] = "'{$where[2]}'";
				}
			}
			$where[1] = str_replace('$','',$where[1]);
			$condition .= " ({$where[0]} {$where[1]} {$where[2]}) ";
		}
		else{
			$condition = '';
		}

		return $condition;
	}

	private function get_current_operator($i)
	{
		$operators = array_unique(array_values($this->where_operators));
		$current_operator = $operators[$i % count($operators)];
		return $current_operator;
	}

	private function get_field_with_quotes($field)
	{
		if (!substr_count($field,'+') && !substr_count($field,'-') && !substr_count($field,'*') && !substr_count($field,"'") && !substr_count($field,'"')){
			$groups = array('SUM' => 'SUM','COUNT' => 'COUNT','MIN' => 'LEAST','MAX' => 'MAX');
			$group = null;
			foreach ($groups as $g => $gr){
				if (substr_count($field,$g)){
					$field = str_replace($g,'',$field);
					$field = substr($field,strpos($field,'(')+1);
					$field = substr($field,0,(strripos($field,')')));
					$group = $gr;
					break;
				}
			}
			$arr = explode('.',$field);
			foreach ($arr as $k => $a){
				$arr[$k] = "`{$arr[$k]}`";
			}
			$field = implode('.',$arr);
			if (!is_null($group)){
				$field = $group."($field)";
			}
		}
		return $field;
	}

}
?>
