<?php
interface mysql_Interface {
    public function debug($debug = false);
    public function setAdapter($host = '', $user = '', $password = '', $database = '');
    public function table($table = '');
    public function find($order = NULL, $group = NULL, $limit = NULL);
    public function save($where = array());
    public function add();
    public function delete();
    public function bind_param($sql = NULL, $params = array(), $delimiter = '?');
    public function query();
}

class MySQL implements mysql_Interface {

	protected $debug = false;

	protected $adapter = NULL;
	protected $table = NULL;
	protected $columns = array();
	protected $sql = '';

	public function debug($debug = false) {
        $this->debug = $debug;
    }

	public function setAdapter($host = '', $user = '', $password = '', $database = '') {
		try {

			if('' === (string) trim($host) || '' === (string) trim($user) || '' === (string) trim($database)) {
				throw new Exception('Is null');
			}
			
			$this->adapter = @mysql_pconnect($host, $user, $password);

			if(!$this->adapter) {
				throw new Exception(mysql_error());
			}
		
			if(!mysql_select_db($database, $this->adapter)) {
				throw new Exception(mysql_error());
			}

			if(!mysql_query('SET NAMES utf8', $this->adapter) || !mysql_query('SET CHARACTER_SET_CLIENT  = utf8', $this->adapter) || !mysql_query('SET CHARACTER_SET_RESULTS = utf8', $this->adapter)) {
				throw new Exception(mysql_error());
			}
			
			return true;
			
		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('host' => $host, 'user' => $user, 'password' => $password, 'database' => $database)) : false;
		}
	}

	public function table($table = '') {
		try {

			$this->_free_table();

			if('' === (string) trim($table)) {
				throw new Exception('Is null');
			}

			$sql = sprintf('SHOW COLUMNS FROM %s', mysql_real_escape_string($table));
					
			$result = mysql_query($sql, $this->adapter);

			if(!$result) {
				throw new Exception(mysql_error());
			}

			while($record = mysql_fetch_assoc($result)) {
				$this->{$record['Field']} = NULL;
				$this->columns[] = $record['Field'];
			}

			mysql_free_result($result);

			$this->table = $table;

			return array($this->table => $this->columns);

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('Table' => $table)) : false;
		}
	}
	
	public function find($order = NULL, $group = NULL, $limit = NULL) {

		try {

			if(NULL !== $this->table) {

				$_where = '';
				$_order = '';
				$_group = '';
				$_limit = '';

				$sql = sprintf('SELECT * FROM %s ', mysql_real_escape_string($this->table));
				foreach($this->columns as $items) {
					if(NULL !== $this->{$items}) {
						if(is_numeric($this->{$items})) {
							$_where .= $_where != '' ? sprintf("AND {$items} = %s ", mysql_real_escape_string($this->{$items})) : sprintf("{$items} = %s ", mysql_real_escape_string($this->{$items}));
						} else {
							$_where .= $_where != '' ? sprintf("AND {$items} = '%s' ", mysql_real_escape_string($this->{$items})) : sprintf("{$items} = '%s' ", mysql_real_escape_string($this->{$items}));
						}
					}
				}
				if('' !== (string) trim($_where)) {
					$_where = "WHERE {$_where}";
				}
				if('' !== (string) trim($order)) {
					$_order .= "ORDER BY {$order} ";
				}
				if('' !== (string) trim($group)) {
					$_group .= "GROUP BY {$group} ";
				}
				if('' !== (string) trim($limit)) {
					$_limit .= "LIMIT {$limit} ";
				}
				$sql .= "{$_where}{$_group}{$_order}{$_limit}";

				$result = mysql_query($sql, $this->adapter);

				if(!$result) {
					throw new Exception(mysql_error());
				}

				$data = array();
				
				while($record = mysql_fetch_assoc($result)) {
					$data[] = $record;
				}

				mysql_free_result($result);

				$this->_clear();
				
				return $data;
			}

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('order' => $order, 'group' => $group, 'limit' => $limit)) : false;
		}
	}

	public function save($where = array()) {
		
		try {

			if(NULL !== $this->table) {

				$_set = '';
				$_where = '';

				$sql = sprintf('UPDATE %s SET ', mysql_real_escape_string($this->table));

				foreach($this->columns as $items) {
					if(NULL !== $this->{$items}) {
						if(is_numeric($this->{$items})) {
							$_set .= $_set != '' ? sprintf(", {$items} = %s ", mysql_real_escape_string($this->{$items})) : sprintf("{$items} = %s", mysql_real_escape_string($this->{$items}));
						} else {
							$_set .= $_set != '' ? sprintf(", {$items} = '%s' ", mysql_real_escape_string($this->{$items})) : sprintf("{$items} = '%s'", mysql_real_escape_string($this->{$items}));
						}
					}
				}

				foreach($where as $key => $value) {
					if(is_numeric($value)) {
						$_where .= $_where != '' ? sprintf("AND %s = %s ", mysql_real_escape_string($key), mysql_real_escape_string($value)) : sprintf("%s = %s ", mysql_real_escape_string($key), mysql_real_escape_string($value));
					} else {
						$_where .= $_where != '' ? sprintf("AND %s = '%s' ", mysql_real_escape_string($key), mysql_real_escape_string($value)) : sprintf("%s = '%s' ", mysql_real_escape_string($key), mysql_real_escape_string($value));
					}
				}

				if('' !== (string) trim($_set) ||'' !==  (string) trim($_where)) {
					throw new Exception('Is null');
				}

				$sql .= "{$_set} {$_where}";

				$this->_clear();

				$result = mysql_query($sql, $this->adapter);

				if(!$result) {
					throw new Exception(mysql_error());
				}

				return true;
			}

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('where' => $where)) : false;
		}
	}
	
	public function add() {

		try {

			if($this->table != NULL) {

				$_column = '';
				$_value = '';
				
				$sql = sprintf('INSERT INTO %s ', mysql_real_escape_string($this->table));

				foreach($this->columns as $items) {
					if(NULL !== $this->{$items}) {
						if(is_numeric($this->{$items})) {
							$_column .= $_column != '' ? sprintf(", %s", mysql_real_escape_string($items)) : sprintf("%s", mysql_real_escape_string($items));
							$_value .= $_value != '' ? sprintf(", %s", mysql_real_escape_string($this->{$items})) : sprintf("%s", mysql_real_escape_string($this->{$items}));
						} else {
							$_column .= $_column != '' ? sprintf(", %s", mysql_real_escape_string($items)) : sprintf("%s", mysql_real_escape_string($items));
							$_value .= $_value != '' ? sprintf(", '%s'", mysql_real_escape_string($this->{$items})) : sprintf("'%s'", mysql_real_escape_string($this->{$items}));
						}
					}
				}

				if('' !== (string) trim($_column) || '' !== (string) trim($_value)) {
					throw new Exception('Is null');
				}

				$sql .= "({$_column}) VALUES ({$_value})";

				$result = mysql_query($sql, $this->adapter);

				if(!$result) {
					throw new Exception(mysql_error());
				}

				$primary_id = mysql_insert_id($this->adapter);

				$this->_clear();

				return $primary_id;

			}

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('_column' => $_column, '_value' => $_value)) : false;
		}	
	}

	public function delete() {

		try {

			if(NULL !== $this->table) {

				$_where = '';

				$sql = sprintf('DELETE FROM %s ', mysql_real_escape_string($this->table));
				foreach($this->columns as $items) {
					if(NULL !== $this->{$items}) {
						if(is_numeric($this->{$items})) {
							$_where .= $_where != '' ? sprintf("AND {$items} = %s ", mysql_real_escape_string($this->{$items})) : sprintf("{$items} = %s ", mysql_real_escape_string($this->{$items}));
						} else {
							$_where .= $_where != '' ? sprintf("AND {$items} = '%s' ", mysql_real_escape_string($this->{$items})) : sprintf("{$items} = '%s' ", mysql_real_escape_string($this->{$items}));
						}
					}
				}
				if('' !== (string) trim($_where)) {
					$_where = "WHERE {$_where}";
				}
				
				$sql .= "{$_where}";

				$this->_clear();

				$result = mysql_query($sql, $this->adapter);

				if(!$result) {
					throw new Exception(mysql_error());
				}

				return true;

			}

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('_where' => $_where)) : false;
		}
	}

	public function bind_param($sql = NULL, $params = array(), $delimiter = '?') {

		try {

			$_sql = '';

			$split = explode($delimiter, $sql);
			
			$count = count($params);
			
			if((count($split) -1) !== $count) {
				throw new Exception('params error');
			}

			for($i = 0; $i < $count; $i++) {
				if(is_numeric($params[$i])) {
					$_sql .= sprintf("%s%s", $split[$i], mysql_real_escape_string($params[$i]));
				} else {
					$_sql .= sprintf("%s'%s'", $split[$i], mysql_real_escape_string($params[$i]));
				}
			}

			if($i < count($split)) {
				$_sql .= $split[$i];
			}

			$this->sql = $_sql;

			return $_sql;

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('sql' => $sql, 'params' => $params, 'delimiter' => $delimiter)) : false;
		}
	}

	public function query() {

		try {
			
			if($this->sql == '') {
				throw new Exception('Is null');
			}

			$mode = strtoupper(trim($this->sql));
			$data = array();

			if('SELECT' === (string) substr($mode, 0, 6) || 'SHOW' === (string) substr($mode, 0, 4)) {

				$result = mysql_query($this->sql, $this->adapter);

				if(!$result) {
					throw new Exception(mysql_error());
				}

				while($record = mysql_fetch_assoc($result)) {
					$data[] = $record;
				}

				mysql_free_result($result);

			} else if('INSERT' === substr($mode, 0, 6)) {

				$result = mysql_query($this->sql, $this->adapter);

				if(!$result) {
					throw new Exception(mysql_error());
				}

				$data = mysql_insert_id($this->adapter);

			} else {

				$result = mysql_query($this->sql, $this->adapter);

				if(!$result) {
					throw new Exception(mysql_error());
				}

				$data = true;

			} 

			return $data;

		} catch(Exception $e) {
			return $this->debug ? array('message' => $e->getMessage(), 'status' => false, 'value' => array('sql' => $this->sql)) : false;
		}	
	}

	protected function _clear() {
		$this->sql = '';
		foreach($this->columns as $items) {
			$this->{$items} = NULL;
		}
	}

	protected function _free_table() {
		if(0 === count($this->columns)) {
			return false;
		}

		foreach($this->columns as $items) {
			unset($this->{$items});
		}

		$this->columns = array();

		return true;
	}
}