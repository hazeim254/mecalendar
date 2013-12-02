<?php

namespace MeCalendars;

/**
 * Middle East Calendars Converter is used to convert between some popular 
 * calendars used in middle east
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Hazem Mohamed
 * 
 * @todo Add suuport for Copteic date
 */
class Converter {
    //<editor-fold defaultstate="collapsed" desc="Variables Declaration">
    /**
     * Represents Hijri date
     */

    const HIJRI = 1;

    /**
     * Represents Gregorian date
     */
    const GREGORIAN = 2;

    /**
     * Represents Persian date
     */
    const PERSIAN = 3;

    /**
     * Represents Hebrew date
     */
    const JEWISH = 4;

    /**
     * Represents Julian Day 
     */
    const JD = 0;

    /**
     * Julian day
     * @var integer
     */
    protected $jd = 0;

    //</editor-fold>

    /**
     * @param integer $year Year
     * @param integer $month Month 
     * @param integer $day Day
     * @param integer $type One of the constants representing calendar type
     */
    public function __construct($year, $month, $day, $type = self::GREGORIAN) {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        $this->type = $type;

        $this->jd = $this->toJD();
    }

    /**
     * Converts Hijri date to a Julian Day
     * 
     * @param type $year Hijri Year
     * @param type $month Hijri Month
     * @param type $day Hijri Day
     * 
     * @return integer Julian day corresponding to Hijri date
     */
    protected function hijriToJD($year = null, $month = null, $day = null) {
        $year = is_null($year)? $this->year : $year;
        $month = is_null($month)? $this->month : $month;
        $day = is_null($day)? $this->day : $day;
        
        $jd = ($day + ceil(29.5 * ($month - 1)) + 
                ($year - 1) * 354 + floor((3 + 11 * $year) / 30) + 1948439.5) - 1;
        
        return $jd;
    }

    /**
     * Converts Persian date to a Julian Day
     * 
     * @param type $year Persian Year
     * @param type $month Perisan Month
     * @param type $day Persian Day
     * 
     * @return integer Julian day corresponding to Persian date
     */    
    protected function persianToJD($year = null, $month = null, $day = null) {
        $year = is_null($year)? $this->year : $year;
        $month = is_null($month)? $this->month : $month;
        $day = is_null($day)? $this->day : $day;
        
		$epochBase = $year - ($year >= 0? 474 : 473);
		$epochYear = 474 + ($epochBase % 2820);
		
		if ($month <= 7) {
			$factor = ($month - 1) * 31;
		} else {
			$factor = (($month - 1) * 30) + 6;
		}
		
		$jd = $day + $factor + floor(($epochYear * 682 - 110) / 2816) + ($epochYear - 1) * 365
				+ floor($epochBase / 2820) * 1029983 + (1948321 - 1);
		
		return $jd;
    }

    /**
     * Converts Jewish date to a Julian Day
     * 
     * @param type $year Jewish Year
     * @param type $month Jewish Month
     * @param type $day Jewish Day
     * 
     * @return integer Julian day corresponding to Jewish date
     */
    protected function jewishToJD($year = null, $month = null, $day = null) {
        $year = is_null($year)? $this->year : $year;
        $month = is_null($month)? $this->month : $month;
        $day = is_null($day)? $this->day : $day;

        return jewishtojd($month, $day, $year);
    }

    /**
     * Converts Persian date to a Gregorian Day
     * 
     * @param type $year Gregorian Year
     * @param type $month Gregorian Month
     * @param type $day Gregorian Day
     * 
     * @return integer Julian day corresponding to Gregorian date
     */
    protected function gregorianToJD($year = null, $month = null, $day = null) {
        $year = is_null($year)? $this->year : $year;
        $month = is_null($month)? $this->month : $month;
        $day = is_null($day)? $this->day : $day;
        
        $jd = gregoriantojd($month, $day, $year);
        return $jd;
    }

    /**
     * Converts the date according to the primary type
     */    
    protected function toJD() {
        switch ($this->type) {
            case self::HIJRI:
                return $this->hijriToJD();
                break;
            case self::PERSIAN:
                return $this->persianToJD();
                break;
            case self::JEWISH:
                return $this->jewishToJD();
                break;
            case self::GREGORIAN:
            default:
                return $this->gregorianToJD();
                break;
        }
    }

    /**
     * Get the Hijri date corressponding to the current date
     * 
     * @return array associative array contains converted Hijri date keys are 'year', 'month', 'day'
     */
    public function getHijriDate() {
        if (self::HIJRI == $this->type) {
            return ['year' => $this->year, 'month' => $this->month, 'day' => $this->day];
        }
        
        $year = floor((30 * ($this->jd - 1948439.5) + 10646) / 10631);
        $month = min(12, ceil(($this->jd - (29 + $this->hijriToJD($year, 1, 1))) / 29) + 1);
        $day = $this->jd - $this->hijriToJD($year, $month, 1) + 1;
        
        return compact('year', 'month', 'day');
    }
    
    /**
     * Get the Persian date corressponding to the current date
     * 
     * @return array associative array contains converted Persian date keys are 'year', 'month', 'day'
     */
    public function getPersianDate() {
        if (self::PERSIAN == $this->type) {
            return ['year' => $this->year, 'month' => $this->month, 'day' => $this->day];
        }
		
		$epoch = $this->jd - $this->persianToJD(475, 1, 1);
		$cycle = floor($epoch / 1029983);
		$cannee = $epoch % 1029983;
		if ($cannee == 1029982) {
			$ycycle = 2820;
		} else {
			$aux1 = floor($cannee / 366);
			$aux2 = $cannee % 366;
			$ycycle = floor(($aux1 * 2134 + 2816 * $aux2 + 2815) / 1028522) + $aux1 + 1;
		}
		
		$year = $ycycle + (2820 * $cycle) + 474;
		if ($year <= 0) {
			--$year;
		}
		
		$yday = $this->jd - $this->persianToJD($year, 1, 1) + 1;
		$month = $yday > 186? ceil($yday / 31) : ceil(($yday - 6) / 30);
		$day = $this->jd - $this->persianToJD($year, $month, 1) + 1;
		
		return compact('year', 'month', 'day');
    }
    
    /**
     * Get the Jewish date corressponding to the current date
     * 
     * @return array associative array contains converted Jewish date keys are 'year', 'month', 'day'
     */
    public function getJewishDate() {
        if (self::JEWISH == $this->type) {
            return ['year' => $this->year, 'month' => $this->month, 'day' => $this->day];
        }

        list($month, $day, $year) = explode('/', jdtojewish($this->jd));
        return compact('year', 'month', 'day');
    }
    
    /**
     * Get the Gregorian date corressponding to the current date
     * 
     * @return array associative array contains converted Gregorian date keys are 'year', 'month', 'day'
     */
    public function getGregorianDate() {
        if (self::GREGORIAN == $this->type) {
            return ['year' => $this->year, 'month' => $this->month, 'day' => $this->day];
        }

        list($month, $day, $year) = explode('/', jdtogregorian($this->jd));
        return compact('year', 'month', 'day');
    }
    
    /**
     * Get the Julian Day corresponding to the input date
     * 
     * @return integer the Julain day corresponding to the input date
     */
    public function getJulianDay() {
        return $this->jd;
    }
}
