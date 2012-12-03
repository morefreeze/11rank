<?php
class Db{
	public function __construct($dbname, $conf){
		$this->_db = new mysqli($conf['host'], $conf['user'], $conf['pass'],
			$dbname, $conf['port']);
		$this->_db->set_charset('utf8');
	}

	public function __get($property) {
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
}