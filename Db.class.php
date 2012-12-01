<?php
class Db{
	static private $db_conf = array(
		'dota' => array(
			'host' => '127.0.0.1',
			'port' => '3306',
			'user' => 'dota',
			'pass' => 'atod',
			),
		);
	static private $db_pool = array();
	static public function getDBConnect($dbname){
		if (isset(self::$db_pool[$dbname])){
			return self::$db_pool[$dbname];
		}
		$conf = self::$db_conf[$dbname];
		$db = new mysqli($conf['host'], $conf['user'], $conf['pass'],
			$dbname, $conf['port']);
		$db->set_charset('utf8');
		self::$db_pool[$dbname] = $db;
		return $db;
	}
}