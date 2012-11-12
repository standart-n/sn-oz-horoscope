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
		if (self::checkParams(array("callback"))) {
			echo hscope::getResponse();
		}
	}
}

function checkParams($ms) {
	foreach ($ms as $key) {
		if (!isset($_REQUEST[$key])) return false;
		self::$url->$key=trim(strval($_REQUEST[$key]));
		if (self::$url->$key=="") return false;
	}
	return true;
}

function getControls() {
	foreach (array("hscope","console") as $key) {
		if (!file_exists(project."/controls/".$key.".php")) return false;
		require_once(project."/controls/".$key.".php");
		sn::cl($key);
	}
	return true;	
}


} ?>
