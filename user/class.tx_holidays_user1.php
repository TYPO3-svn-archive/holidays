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
	
	public function holidayName_userFunc($content, $conf) {	
		//Convert content into a timestamp
		if(!is_numeric($content) || intval($content) < 30000000) {
			//Convert into a timestamp
			$tstamp = strtotime($content);
		} else {
			//Propably already a timestamp
			$tstamp = $content;
		}
		
		if ($tstamp > 0) {
			//get date and differences
			$year = date('Y',$tstamp);
			$easter = $this->getEasterDate($year);
			$easterDiff = floor(($content - $easter)/86400);
			$newyearDiff = $this->getDayOfYear($tstamp);
			$this->setLangUid();

			//get holidays
			$holidays=Array();
			$this->addFixedHolidays($newyearDiff, &$holidays);
			$this->addEasterHolidays($easterDiff, &$holidays);	
			if (count($holidays)!=0) {
				$value = implode(', ',$holidays);
				//add stdwrap if defined
				if (isset($conf['stdWrap.'])) {
					$local_cObj = t3lib_div::makeInstance('tslib_cObj');
					$value =  $local_cObj->stdWrap($value, $conf['stdWrap.']);
				}
			} else {
				$value = '';
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
	
	private function addFixedHolidays($dayOfYear, &$holidays) {
		$this->addHolidays(0,$dayOfYear,&$holidays);
	}
	
	private function addEasterHolidays($deltaEaster, &$holidays) {
		$this->addHolidays(1,$deltaEaster,&$holidays);
	}

	private function addHolidays($type, $day, &$holidays) {
		$select = 'tx_holidays_holidays.uid, tx_holidays_holidays.name';
		$table = 'tx_holidays_holidays';
		$where = 'tx_holidays_holidays.type='.intval($type).
			 ' AND tx_holidays_holidays.day='.intval($day).
			 $this->cObj->enableFields('tx_holidays_holidays');
		$res_holidays = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $table,$where);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_holidays) != 0) {
			while ($row_holidays = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_holidays)) {
				$holidays[] = $this->getLanguageName($row_holidays['uid']);
			}
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res_holidays);
	}

	private function getLanguageName($uid) {
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
	
	private function setLangUid() {
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
	}
	
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
	
/*
         * Change a date in any format to an unix timestamp
	 * Coding found at http://www.typo3.net/index.php?id=13&action=list_post&tid=87564, Thanks to Alex
         *
	 * @param	string		$string: Date to change
	 * @param	string		$default: Default value used in case of errors
	 * @param	int		$timestamp: 1: Return timestamp, 0: return "Y-m-d"
	 *
         * @return 	converted date
         */	 
	private function getTimestamp($string, $default = 'now', $timestamp = 1) {
		$error = 0; // no error at the beginning
		$string = str_replace(array('-', '_', ':', '+', ',', ' '), '.', $string); // change 23-12-2009 -> 23.12.2009 AND "05:00 23.01.2009" -> 05.00.23.01.2009
		if (method_exists('t3lib_div', 'trimExplode')) $dateParts = t3lib_div::trimExplode('.', $string, 1); else $dateParts = explode('.', $string); // split at .
		t3lib_div::debug($dateParts);
		if (count($dateParts) === 3) { // only if there are three parts like "23.12.2009"
			if (strlen($dateParts[0]) <= 2 && strlen($dateParts[1]) <= 2 && strlen($dateParts[2]) <= 2) { // xx.xx.xx
				$string = strtotime($dateParts[2].'-'.$dateParts[1].'-'.$dateParts[0]); // change to timestamp
			}
			elseif (strlen($dateParts[0]) == 4 && strlen($dateParts[1]) <= 2 && strlen($dateParts[2]) <= 2) { // xxxx.xx.xx
				$string = strtotime($dateParts[0].'-'.$dateParts[1].'-'.$dateParts[2]); // change to timestamp
			}
			elseif (strlen($dateParts[0]) <= 2 && strlen($dateParts[1]) <= 2 && strlen($dateParts[2]) == 4) { // xx.xx.xxxx
				$string = strtotime($dateParts[2].'-'.$dateParts[1].'-'.$dateParts[0]); // change to timestamp
			}
			else { // error
				$error = 1; // error
			}
		} elseif (count($dateParts) === 5) { // only if there are five parts like "05.00.23.01.2009"
			$string = strtotime($dateParts[4].'-'.$dateParts[3].'-'.$dateParts[2].' '.$dateParts[0].':'.$dateParts[1].':00'); // change to timestamp
		} else { // more than 3 parts - so error
			$error = 1; // error
		}
		$string = date('Y-m-d', $string); // For default: change 1234567 -> 1.1.1979
		if ($timestamp) $string = strtotime($string); // Change back 1.1.1979 -> 1234567
		if ($error) $string = ($default == 'now' ? time() : $default); // show default value
       
		return $string;
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