<?php
class DB
{
private static $dbLink;

private function __construct() {}
private function __clone() {}

public static function Get()
{
	if(!self::$dbLink)
	{
		self::$dbLink = new mysqli(DB_HOST,SQL_USER,SQL_PASS,DB_NAME);
		self::$dbLink->set_charset(DB_CHARSET);
		if(mysqli_connect_errno())
		{
			throw new Exception("Database connection failed: ".mysqli_connect_error());
		}
	}
	return self::$dbLink;
	}
}
?>