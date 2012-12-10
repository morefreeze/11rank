<?php
class Db{
	public function __construct($dbname, $conf){
		$this->_db = new mysqli($conf['host'], $conf['user'], $conf['pass'],
			$dbname, $conf['port']);
		$this->_db->set_charset('utf8');
	}

	public function __get($property) {
		$property = "_".$property;
    	if (property_exists($this, $property)) {
      		return $this->$property;
    	}
  	}

	public function query($sql){
		$ret = array();
		if (null == $this->_db){
			return false;
		}
		$this->_last_sql = $sql;
		if ("" == $sql){
			return $ret;
		}
		$res = $this->_db->query($sql);
		$this->_insert_id = $this->_db->insert_id;
		if (is_bool($res)){
			return $res;
		}
		while($tmp = $res->fetch_assoc()){
			$ret[] = $tmp;
		}
		return $ret;
	}

	public function escape_string($str = ""){
		return $this->_db->escape_string($str);
	}
	private $_db = null;
	private $_last_sql = "";
	private $_insert_id = 0;
}