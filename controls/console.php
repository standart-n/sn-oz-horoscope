<?php class console extends sn {
	
public static $status;
public static $text;
public static $path;

function __construct() {
	self::$text="";
	self::$path=project."/log/console.log";
	self::write(date("Ymd")." ".date("H:i:s")." ".time());
}

function write($line="") {
	self::$text.=$line."\r\n";
	self::save();
}

function clear() {
	if (file_exists(self::$path)) {
		unlink(self::$path);
	}
}

function save() {
	file_put_contents(self::$path,self::$text);
}

} ?>
