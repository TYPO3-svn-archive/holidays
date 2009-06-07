<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Thomas Ernst <typo3@thernst.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

/**
 * Functions for backend display and frontend editing
 *
 * @author	Thomas Ernst <typo3@thernst.de>
 * @package	TYPO3
 * @subpackage	tx_holidays
 *
 * Modifications
 * ThEr310509	0.1.0	Initial development of class
 */

 
class tx_holidays_user1 {
	private $langUid;
	private $country;
	
	/* Constructor of class: Fill some needed values
	 * (It's not much, so we do this here instead in the user-function, where it is called every time the user function is used)
	 */	
	public function tx_holidays_user1() {
		//Language
		$language = $GLOBALS['TSFE']->tmpl->setup['config.']['language'];
		$select = 'static_languages.uid';
		$table = 'static_languages';
		$where = 'static_languages.lg_typo3=\''.$GLOBALS['TSFE']->tmpl->setup['config.']['language'].'\'';
		$res_language = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $table,$where);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_language) != 0) {
			$row_language = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_language);
			$this->langUid = $row_language['uid'];
			$GLOBALS['TYPO3_DB']->sql_free_result($res_language);
		} else {
			//Nothing found. Default: English 
			$this->langUid = 30;
		}
		
		//Country
		$this->country = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_holidays.']['country'];
	}
	
	/* User-function returning the holiday name based on a date
	 *
	 * @param	string		$content: Date for which the holiday name(s) should be determined
	 * @param	array		$conf: Additional configuration for user-function
	 * @return	string		Name of holiday(s) as configured
	 */	
	public function holidayName_userFunc($content, $conf) {		
		$tstamp = $this->convertToTimestamp($content);

		if ($tstamp > 0) {
			//get holidays
			$value = '';
			$holidays=Array();
			$this->addFixedHolidays($tstamp, &$holidays);
			$this->addEasterHolidays($tstamp, &$holidays);	
			if (count($holidays)!=0) {
				$value = implode(', ',$holidays);
				//add stdwrap if defined
				if (isset($conf['stdWrap.'])) {
					$local_cObj = t3lib_div::makeInstance('tslib_cObj');
					$value =  $local_cObj->stdWrap($value, $conf['stdWrap.']);
				}
			}
			
			//prefix original content if required
			if ($conf['prefixWithOriginalContent'] == 1) {
				if (isset($value)) $value = ' '.$value;
				$value = $content.$value;
			}
			
			//return data
			return $value;
		} else return $content;
	}
	
	/* Adds the fixed-date holidays to the holidays-array
	 *
	 * @param	timestamp	$tstamp: timestamp of the date whose holidays should be added
	 * @return	arrays		$holidays: Array containing all holidays
	 */	
	private function addFixedHolidays($tstamp, &$holidays) {
		$dayOfYear = $this->getDayOfYear($tstamp);
		$this->addHolidays(0,$dayOfYear,&$holidays);
	}
	
	/* Adds the easter-related holidays to the holidays-array
	 *
	 * @param	timestamp	$tstamp: timestamp of the date whose holidays should be added
	 * @return	arrays		$holidays: Array containing all holidays
	 */	
	private function addEasterHolidays($tstamp, &$holidays) {
		//get date and differences
		$year = date('Y',$tstamp);
		$easter = $this->getEasterDate($year);
		$deltaEaster = floor(($tstamp - $easter)/86400);	
		
		//add holidays
		$this->addHolidays(1,$deltaEaster,&$holidays);
	}

	/* Adds the holidays of a certain type and day-value to the holidays-Array
	 *
	 * @param	integer		$type: Type of holidays to add
	 * @param	integer		$day: Day value of holidays to add
	 * @return	arrays		$holidays: Array containing all holidays
	 */
	private function addHolidays($type, $day, &$holidays) {
		$select = 'tx_holidays_holidays.uid, tx_holidays_holidays.name, tx_holidays_holidays.country_exclude, tx_holidays_holidays.country_only';
		$table = 'tx_holidays_holidays';
		$where = 'tx_holidays_holidays.type='.intval($type).
			 ' AND tx_holidays_holidays.day='.intval($day).
			 $this->cObj->enableFields('tx_holidays_holidays');
		$res_holidays = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $table,$where);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_holidays) != 0) {
			//t3lib_div::debug($row_holidays['name']);
			while ($row_holidays = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_holidays)) {
				if ($this->country != 0) {
					if ($row_holidays['country_exclude'] != '') {
						$excl = explode(',',$row_holidays['country_exclude']);
						$use = in_array($this->country,$excl) ? false : true;
					} else if ($row_holidays['country_only'] != '') {
						$only = explode(',',$row_holidays['country_only']);
						$use = in_array($this->country,$only) ? true : false;
					} else $use = true;
				} else $use = true; 
				if ($use) $holidays[] = $this->getHolidayLocalName($row_holidays['uid']);
			}
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res_holidays);
	}

	/* Converts a string into a timestamp.
	 * Numeric strings <  30000000 are expected to be "YYYYMMDD"-Values.
	 * Numeric strings >= 30000000 are expected to be timestamps already
	 * Non-Numeric strings are expected to be date strings. If "config.language" is set, they may be localized date strings. (currently only de is supported)
	 *
	 * @param	string		$content: Value to convert to a timestamp
	 * @return	timestamp	Timestamp, matching the value or 0 if value is not convertible.
	 */
	private function convertToTimestamp($content) {
		//Convert content into a timestamp
		if(!is_numeric($content)) {
			//String. Replace propably localized texts with english texts and then convert into a timestamp
			$monthlong_en = Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
			$monthshort_en = Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
			
			//Language = German
			if($this->langUid == 43) {
				$monthlong_de = Array('Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');
				$monthshort_de = Array('Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez');
				$content = str_replace($monthlong_de, $monthlong_en, $content);
				$content = str_replace($monthshort_de, $monthshort_en, $content);
			}
			
			$tstamp = strtotime($content);
		} else if (intval($content) < 30000000) {
			//yyymmdd, convert into a timestamp
			$tstamp = strtotime($content);
		} else {
			//Propably already a timestamp
			$tstamp = $content;
		}
		
		//t3lib_div::debug($content.' --> '.date('d-m-y',$tstamp));
		
		return $tstamp;
	}

	/* Determines the localized name of a holiday.
	 * Base for the language is the TypoScript setting "config.language". 
	 * If there is no such setting, or the setting is unknown. the function returns the english name.
	 *
	 * @param	integer		$uid: uid of the holiday
	 * @return	string		localized name of the holiday
	 */
	private function getHolidayLocalName($uid) {
		$select = 'tx_holidays_holidaynames.local_name';
		$table = 'tx_holidays_holidaynames';
		$where = 'tx_holidays_holidaynames.holiday_uid='.$uid.
			 ' AND tx_holidays_holidaynames.language_uid='.$this->langUid;
		$res_names = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $table,$where);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_names) != 0) {
			$row_names = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_names);
			$name = $row_names['local_name'];
			$GLOBALS['TYPO3_DB']->sql_free_result($res_names);
			return $name;
		}
	}

	
	/* Converts a date into the number of the day in a (leap)-year
	 *
	 * @param	timestamp	$date: Date for which the number of the day in the year should be determined
	 * @return	integer		Number of the day in the year
	 */	
	private function getDayOfYear($date) {
		$year = date('Y',$date);
		$newyear = mktime(0,0,0,1,1,$year);
		$newyearDiff = floor(($date - $newyear)/86400)+1;
		
		//In order to eliminate the problem with leap years having an additional days, we
		//add one (fictional) day after 28. February, in case of non-leap years.
		//Thus, all fixed date holidays need to be stored as that day in the year, the
		//holiday has in a leap year!
		if(!$this->isLeapYear($year) && $newyearDiff >= 59) $newyearDiff = $newyearDiff +1;
		return $newyearDiff;
	}
	
	private function isLeapYear($year) {
		if($year % 400 == 0) return true;
		elseif ($year % 4 == 0 && $year % 100 != 0)  return true;
		else return false;
	}
	
	/* Calculates the date of easter sunday using Gauss's Easter formula
	 *
	 * @param	integer		$year: The year for which the easter date should be calculated (1582 to 2199)
	 * @return	timestamp	Timestamp of easter sunday
	 */
	private function getEasterDate($year) {
		if ($year < 1582 || $year > 2199)
			die('Die Berechnung des Ostersonntags ist nur für die Jahre zwischen 1582 und 2199 möglich!');
		if ($year >= 1582 && $year <= 1699) {
			$M = 22;
			$O = 2;
		} else if ($year >= 1700 && $year <= 1799) {
			$M = 23;
			$O = 3;
		} else if ($year >= 1800 && $year <= 1899) {
			$M = 23;
			$O = 4;
		} else if ($year >= 1900 && $year <= 2099) {
			$M = 24;
			$O = 5;
		} else if ($year >= 2100 && $year <= 2199) {
			$M = 24;
			$O = 6;
		}

		$A = $year % 19;
		$B = $year % 4;
		$C = $year % 7;
		$D = (19 * $A + $M) % 30;
		$E = (2 * $B + 4 * $C + 6 * $D + $O) % 7;
		
		if ($D + $E <= 9) {
			$easterstamp = mktime(0,0,0,3,22 + $D + $E,$year);
		} else {
			$easterstamp = mktime(0,0,0,4,$D + $E - 9,$year);
		}
		
		if (date('n',$easterstamp) == 4) {
			if ($D = 28 && $A > 10) {
				if (date('j',$easterstamp) == 25) {
					$easterstamp = mktime(0,0,0,4,18,$year);
				}
				if (date('j',$easterstamp) == 26) {
					$easterstamp = mktime(0,0,0,4,19,$year);
				}
			}
		}
		return $easterstamp;
	}

	/* Calculates the date of the Day of Repetance and Prayer in Germany ("Buss- und Bettag")
	 * (Wednesday between 16th and 22nd November of the year)
	 *
	 * @param	integer		$year: The year for which the date should be calculated
	 * @return	timestamp	Timestamp of the german day of repetance and prayer
	 */
	function getGermanDayOfRepentanceAndPrayer($year) {
	    for($tag=16;$tag <= 22;$tag++) {
		if (date('w',mktime(0,0,0,11,$tag,$year)) == 3) {
			return mktime(0,0,0,11,$tag,$year);
		}
	    }
	}	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/holidays/user/class.tx_holidays_user1.php'])      {
        include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/holidays/user/class.tx_holidays_user1.php']);
}
        
?>