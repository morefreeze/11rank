<?php
require_once ('Db.class.php');
class DbTools{
	static private $db_conf = array(
		'dota' => array(
			'host' => '127.0.0.1',
			'port' => '3306',
			'user' => 'dota',
			'pass' => 'atod',
			),
		);
	static public function getDBConnect($dbname){
		if (isset(self::$db_pool[$dbname])){
			return self::$db_pool[$dbname];
		}
		$conf = self::$db_conf[$dbname];

		$db = new Db($dbname, $conf);
		
		self::$db_pool[$dbname] = $db;
		return $db;
	}

	static private $db_pool = array();
}