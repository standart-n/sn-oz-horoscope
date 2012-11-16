<?php class hscope extends sn {
	
public static $response;
public static $htype;
public static $actual;
public static $path;


function __construct() {
	self::$htype="otvetplanet";
	self::$actual=false;
	self::$response=array();
	self::$response['hscope']=array();
	self::$path=project."/files/hscope/".date("Ymd").".json";
}

function getResponse() {
	if (file_exists(self::$path)) {
		return self::getResponseString(self::getJsonFromFile());
	}
	self::getHscope();
	if (self::$actual) {
		self::saveJsonToFile();
	}
	return self::getResponseString(json_encode(self::$response));
		
}

function getResponseString($s=null) {
	if ($s) {
		if (start::$url->callback) {
			return start::$url->callback."(".$s.");";
		} else {
			return $s;
		}
	}	
}

function getHscope() { $data=""; $ms=array();
	if (self::$htype=="otvetplanet") {
		for ($i=0;$i<12;$i++) {
			$data=self::getDataFromSite(self::getUrl($i+1));
			$ms=self::parseData($data);
			self::$response['hscope'][self::getZodiacId($i+1)]=$ms;
		}
		return true;
	}
}

function parseData($data) { $ms=array(); $ar=array();
	if ($data) {
		if (self::$htype=="otvetplanet") {
			$data=preg_replace('/\r/','',$data);
			$data=preg_replace('/\n/','',$data);
			if (preg_match('/<div id="unpaid-horoscope-detail-text">(.*?)<script>/',$data,$description,PREG_OFFSET_CAPTURE)) {
				$ms['description']=strval($description[1][0]);
				return $ms;
			}
			return false;
		}
	}
}

function getZodiacId($id) {
		switch ($id) {
			case 1: return "oven"; break;
			case 2: return "telec"; break;
			case 3: return "bliznecu"; break;
			case 4: return "rak"; break;
			case 5: return "lev"; break;
			case 6: return "deva"; break;
			case 7: return "vesu"; break;
			case 8: return "scorpion"; break;
			case 9: return "strelec"; break;
			case 10: return "kozerog"; break;
			case 11: return "vodoley"; break;
			case 12: return "rubu"; break;
		}
	return false;
}

function getDataFromSite($url) { $data="";
	if ($url) {
		$data=file_get_contents($url);
	}
	if (self::$htype=="otvetplanet") {
		if (preg_match('/<div class="date-h" >(.*?)&nbsp;/',$data,$dt,PREG_OFFSET_CAPTURE)) {
			$date=strval($dt[1][0]);
			console::write($date);
			if (date("j.n.Y")==$date) {
				self::$actual=true;
			}
		}
	}
	return $data;
}

function getUrl($id=1) {
	if (self::$htype=="otvetplanet") {
		switch ($id) {
			case 1: return "http://otvetplanet.ru/horoscopes/free/aries/".self::getSiteId($id)."/"; break;
			case 2: return "http://otvetplanet.ru/horoscopes/free/taurus/".self::getSiteId($id)."/"; break;
			case 3: return "http://otvetplanet.ru/horoscopes/free/gemini/".self::getSiteId($id)."/"; break;
			case 4: return "http://otvetplanet.ru/horoscopes/free/cancer/".self::getSiteId($id)."/"; break;
			case 5: return "http://otvetplanet.ru/horoscopes/free/leo/".self::getSiteId($id)."/"; break;
			case 6: return "http://otvetplanet.ru/horoscopes/free/virgo/".self::getSiteId($id)."/"; break;
			case 7: return "http://otvetplanet.ru/horoscopes/free/libra/".self::getSiteId($id)."/"; break;
			case 8: return "http://otvetplanet.ru/horoscopes/free/scorpio/".self::getSiteId($id)."/"; break;
			case 9: return "http://otvetplanet.ru/horoscopes/free/sagittarius/".self::getSiteId($id)."/"; break;
			case 10: return "http://otvetplanet.ru/horoscopes/free/capricorn/".self::getSiteId($id)."/"; break;
			case 11: return "http://otvetplanet.ru/horoscopes/free/aquarius/".self::getSiteId($id)."/"; break;
			case 12: return "http://otvetplanet.ru/horoscopes/free/pisces/".self::getSiteId($id)."/"; break;
		}
	}
}

function getJsonFromFile() {
	return file_get_contents(self::$path);
}

function saveJsonToFile() {	
	file_put_contents(self::$path,json_encode(self::$response));
}

function getSiteId($id=1) {
	return strval(45186+(self::getDays()*72)+(($id-1)*6));
	
}

function getDays() {
	$time=time()-mktime(0,0,0,11,16,2012);
	$time=ceil($time/86400);
	return (intval($time)-1);
}

} ?>
