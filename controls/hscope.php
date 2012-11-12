<?php class hscope extends sn {
	
public static $response;
public static $htype;


function __construct() {
	self::$htype="astroscope";
	self::$response=array();
	self::$response['hscope']=array();
}

function getResponse() {
	self::getHscope();
	return json_encode(self::$response);
	
}

function getHscope() { $data=""; $ms=array();
	if ((self::$htype=="ra-project") || (self::$htype=="astroscope")) {
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
		if (self::$htype=="ra-project") {
			return $data;
		}	
		if (self::$htype=="astroscope") {
			$parser = xml_parser_create();
			xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
			xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
			xml_parse_into_struct($parser,$data,$values,$tags);
			xml_parser_free($parser);
			foreach ($tags as $key=>$val) {
				if ($key=="item") {
					for ($i=0;$i<count($val);$i+=2) {
						$offset=$val[$i]+1;
						$len=$val[$i+1]-$offset;
						$ar=array_slice($values,$offset,$len);
						for ($j=0;$j<count($ar);$j++) {
							$ms[$ar[$j]["tag"]]=$ar[$j]["value"];
						}
						return $ms;
					}
				}
			}
			return false;
		}
	}
}

function getZodiacId($id) {
	if ((self::$htype=="ra-project") || (self::$htype=="astroscope")) {
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
	}
	return false;
}

function getDataFromSite($url) {
	if ($url) {
		return file_get_contents($url);
	}
	return false;
}

function getUrl($id=1) {
	if (self::$htype=="ra-project") {
		return "http://horoscope.ra-project.net/api/".$id;
	}
	if (self::$htype=="astroscope") {
		switch ($id) {
			case 1: return "http://astroscope.ru/rss_feed/aries.rss"; break;
			case 2: return "http://astroscope.ru/rss_feed/taurus.rss"; break;
			case 3: return "http://astroscope.ru/rss_feed/gemini.rss"; break;
			case 4: return "http://astroscope.ru/rss_feed/cancer.rss"; break;
			case 5: return "http://astroscope.ru/rss_feed/leo.rss"; break;
			case 6: return "http://astroscope.ru/rss_feed/virgo.rss"; break;
			case 7: return "http://astroscope.ru/rss_feed/libra.rss"; break;
			case 8: return "http://astroscope.ru/rss_feed/scorpio.rss"; break;
			case 9: return "http://astroscope.ru/rss_feed/sagittarius.rss"; break;
			case 10: return "http://astroscope.ru/rss_feed/capricorn.rss"; break;
			case 11: return "http://astroscope.ru/rss_feed/aquarius.rss"; break;
			case 12: return "http://astroscope.ru/rss_feed/pisces.rss"; break;			
		}
	}
}

} ?>
