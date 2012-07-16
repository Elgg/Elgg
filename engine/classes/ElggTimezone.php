<?php
class ElggTimezone {

	/**
	 * Configurations of particular timezones. Key of main array is the string - id of timezone.
	 * Sub arrays contain fields: 
	 * @var array of arrays 
	 */
	private static $groupById;
	
	static function init() {
		//let server operate always in UTC and use user defined/default timezone 
		//only while displaying data to user
		date_default_timezone_set('UTC');
	}
	
	/**
	 * @param ElggUser $user optional, user that setting should be checked first
	 * @return string PHP standard of string identifier for timezones
	 */
	static function getCurrentId(ElggUser $user=null) {
		if($user instanceof ElggUser) {
			$id = $user->timezone;
			if($id) {
				return $id;
			}
		}
		
		$id = elgg_get_config('timezone');
		if($id) {
			return $id;
		}
		
		return date_default_timezone_get();
	}
	
	static function getOffsetFromId($id) {
		//TODO implement
	}
	
	static function format($format, $ts, $user=null) {
		$date = new DateTime('@'.$ts);
		$date->setTimezone(new DateTimeZone(ElggTimezone::getCurrentId($user)));
		return $date->format($format);
	}
	
	static function getOptionsValues() {
		if(!is_array(self::$groupById)) {
			$list = timezone_identifiers_list();
			$abbreviations = timezone_abbreviations_list();
			$groupByLabel = array();
			self::$groupById = array();
			foreach ($abbreviations as $abbreviation=>$entries) {
				foreach ($entries as $entry) {
					//var_dump($entry['offset']/3600);
					$id = elgg_extract('timezone_id', $entry);
					if($id!==null) {
						$entry['abbr'] = $abbreviation;
						self::$groupById[$id] = $entry;
						$offset = elgg_extract('offset', $entry);
						$dst = elgg_extract('dst', $entry);
						$label = $offset.','.($dst?'1':'0');
						// 				var_dump($label);
						if(!isset($groupByLabel[$label])) {
							$groupByLabel[$label] = array();
						}
						try {
							$tz = new DateTimeZone($id);
							$groupByLabel[$label][] = array($id, timezone_offset_get($tz));
						} catch (Exception $e) {
							// 					var_dump($abbreviation);
							// 					var_dump($entry);
							// 					var_dump('Exception: '.$e->getMessage());
						}
					}
				}
			}
// 			var_dump(self::$groupById);
	// 		var_dump($groupByLabel);
			
// 			$timezone = new DateTimeZone('Europe/Warsaw');
// 			$time = time();
// 			$transitions = $timezone->getTransitions($time, $time);
// 			var_dump($time, $transitions);
// 			$transitions = $timezone->getTransitions($time+30*24*3600*6, $time+30*24*3600*6);
// 			var_dump($time, $transitions);
// 			for($i=0; $i<=12; $i++) {
// 				$date = new DateTime('@'.($time+30*24*3600*$i));
// 				//$date->setTimestamp();
// 				$date->setTimezone($timezone);
// 				var_dump($date->format("c I e"));
				
// 			}
			
			$result = array();
			$date = new DateTime();
			foreach(self::$groupById as $id=>$data) {
				$date->setTimezone(new DateTimeZone($id));
				list($label, $dst, $hours, $short) = explode(';', $date->format('P;I;Z;T'));
				self::$groupById[$id]['label'] = $label;
				self::$groupById[$id]['short'] = $short;
				self::$groupById[$id]['isdst'] = $dst;
				self::$groupById[$id]['cmp'] = $hours;
			}
			uasort(self::$groupById, array(__CLASS__, 'sortByCmpPredicate'));
		}
		foreach(self::$groupById as $id=>$data) {
			$details = '('.$data['label'].' '.$data['short'].')';
			//$result[$id] = '('.$data['label'].($data['isdst']?' DST':'').') '.$id.' ('.$data['short'].')';
			$result[$id] = '(GMT'.$data['label'].','.($data['isdst']?1:0).') '.$id.' ('.$data['short'].')';
			//$result[$id] = '('.$data['label'].') '.$id.' ('.$data['short'].')';
		}
		return $result;
	}
	
	private function sortByOffsetPredicate($a, $b) {
		return $a['offset'] - $b['offset'];
	}

	private function sortByCmpPredicate($a, $b) {
		if($a['cmp'] == $b['cmp']) {
			if($a['isdst'] == $b['isdst']) {
				return strcmp($a['timezone_id'], $b['timezone_id']);
			}
			return $a['isdst'] - $b['isdst'];
		}
		return $a['cmp'] - $b['cmp'];
	}

	static function getTimeDrift() {
		$time_servers = array(
			// 		'tempus1.gum.gov.pl',
// 			"time.nist.gov",
			// 		"nist1.datum.com",
			"time-a.timefreq.bldrdoc.gov",
// 			"utcnist.colorado.edu",
// 			'0.pl.pool.ntp.org',
		);
		foreach($time_servers as $time_server) {
			$mt = microtime(true);
			$fp = fsockopen($time_server, 37, $errno, $errstr, 1);
			if (!$fp) {
				echo "$time_server: $errstr ($errno)\n";
				echo "Trying next available server...\n\n";
			} else {
				$data = NULL;

				while (!feof($fp)) {
					$data .= fgets($fp, 4);
				}
				$time = time();
				fclose($fp);
				var_dump(microtime(true)-$mt);
				// 			var_dump($data);
				if (strlen($data) != 4) {
					echo "NTP Server {$time_server} returned an invalid response.\n";
					if ($i != ($ts_count - 1)) {
						echo "Trying next available server...\n\n";
					} else {
						echo "Time server list exhausted\n";
					}
				} else {
					$valid_response = true;
				}
				if ($valid_response) {
					// time server response is a string - convert to numeric
					// 				for($i=0 ; $i<strlen($data); $i++) {
					// 					var_dump(ord($data[$i]));
					// 				}
					$NTPtime = ord($data[0])<<24 | ord($data[1])<<16 | ord($data[2])<<8 | ord($data[3]);
					// 				var_dump($NTPtime, date('c',$NTPtime));

					// convert the seconds to the present date & time
					// 2840140800 = Thu, 1 Jan 2060 00:00:00 UTC
					// 631152000  = Mon, 1 Jan 1990 00:00:00 UTC
					$TimeFrom1990 = $NTPtime - 2840140800;
					$TimeNow = $TimeFrom1990 + 631152000;
					// 				var_dump($TimeFrom1990, date('c',$TimeFrom1990));
					var_dump((int)$time, date('c',$time));
					// 				var_dump((int)$TimeNow, date('c',$TimeNow));

					// set the system time
					$TheDate = date("c", $TimeNow + $time_adjustment);
					var_dump($TheDate);
					var_dump('Diff: '.($time-((int)$TimeNow + (int)$time_adjustment)));

				} else {
					echo "The system time could not be updated. No time servers available.\n";
				}
			}
		}
	}
}