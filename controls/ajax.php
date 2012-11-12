<?php class ajax extends sn {
	
public static $conf;
public static $options;
public static $url;	

function __construct() {
	self::engine();
}

function engine() {
	self::$url=new def;
	if (self::getControls()) {
		echo hscope::getResponse();
	}
}

function getControls() {
	foreach (array("hscope") as $key) {
		if (!file_exists(project."/controls/".$key.".php")) return false;
		require_once(project."/controls/".$key.".php");
		sn::cl($key);
	}
	return true;	
}


} ?>
