<?php

namespace MeCalendar;

/**
 * Middle East Calendars Converter is used to convert between some popular
 * calendars used in middle east
 *
 * @license http://opensource.org/licenses/mit-license.php GNU Public License
 * @author Hazem Mohamed <hazeim254@gmail.com>
 *
 * @todo Add suuport for Coptic date
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
     * Represents Coptic date
     */
    const COPTIC = 5;

    /**
     * Represents Libyan date
     */
    const LIBYAN = 6;

    /**
     * Represents Libyan milady date
     */
    const LIBYAN_MILADY = 7;

    /**
     * Represents Julian Day
     */
    const JD = 0;

    /**
     * Julian day
     * @var integer
     */
    protected $firstJDay = 0;

    /**
     * Julian day
     * @var integer
     */
    protected $lastJDay = 0;

    /**
     * Year to convert
     * @var integer
     */
    protected $year = null;

    /**
     * Month to convert
     * @var integer
     */
    protected $month = null;

    /**
     * Day to convert
     * @var integer
     */
    protected $day = null;

    //</editor-fold>

    /**
     * @param integer $year Year to convert
     * @param integer $type One of the constants representing calendar type
     * @param integer $month Month to convert. If the value of the parameters is null it will convert year only
     * @param integer $day Day to convert. If the value of the parameters is null it will convert year and month only
     */
    public function __construct($year, $type = self::GREGORIAN, $month = null, $day = null) {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        $this->type = $type;

        $this->toJD();
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
        $year = is_null($year) ? $this->year : $year;
        $month = is_null($month) ? $this->month : $month;
        $day = is_null($day) ? $this->day : $day;

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
        $year = is_null($year) ? $this->year : $year;
        $month = is_null($month) ? $this->month : $month;
        $day = is_null($day) ? $this->day : $day;

        $epochBase = $year - ($year >= 0 ? 474 : 473);
        $epochYear = 474 + ($epochBase % 2820);

        if ($month <= 7) {
            $factor = ($month - 1) * 31;
        } else {
            $factor = (($month - 1) * 30) + 6;
        }

        $jd = $day + $factor + floor(($epochYear * 682 - 110) / 2816) + ($epochYear - 1) * 365 + floor($epochBase / 2820) * 1029983 + (1948321 - 1);

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
        $year = is_null($year) ? $this->year : $year;
        $month = is_null($month) ? $this->month : $month;
        $day = is_null($day) ? $this->day : $day;

        return jewishtojd($month, $day, $year);
    }

    /**
     * Converts Gregorian date to a Julain Day
     *
     * @param type $year Gregorian Year
     * @param type $month Gregorian Month
     * @param type $day Gregorian Day
     *
     * @return integer Julian day corresponding to Gregorian date
     */
    protected function gregorianToJD($year = null, $month = null, $day = null) {
        $year = is_null($year) ? $this->year : $year;
        $month = is_null($month) ? $this->month : $month;
        $day = is_null($day) ? $this->day : $day;

        $jd = gregoriantojd($month, $day, $year);
        return $jd;
    }

    /**
     * Converts Libyan Milday date to a Julian Day
     *
     * @param type $year Gregorian Year
     * @param type $month Gregorian Month
     * @param type $day Gregorian Day
     *
     * @return integer Julian day corresponding to Gregorian date
     */
    protected function libyanToJD($year = null, $month = null, $day = null) {
        $year = is_null($year) ? $this->year : $year;
        $month = is_null($month) ? $this->month : $month;
        $day = is_null($day) ? $this->day : $day;

        $jd = gregoriantojd($month, $day, $year + 632);
        return $jd;
    }

    /**
     * Converts Libyan Milday date to a Julian Day
     *
     * @param type $year Libyan Year
     * @param type $month Libyan Month
     * @param type $day Libyan Day
     *
     * @return integer Julian day corresponding to Gregorian date
     */
    protected function libyanMiladyToJD($year = null, $month = null, $day = null) {
        $year = is_null($year) ? $this->year : $year;
        $month = is_null($month) ? $this->month : $month;
        $day = is_null($day) ? $this->day : $day;

        $jd = gregoriantojd($month, $day, $year + 570);
        return $jd;
    }

    /**
     * Converts Coptic date to Julian day
     *
     * @param type $year Coptic year
     * @param type $month Coptic month
     * @param type $day Coptic day
     *
     * @return integer
     */
    protected function copticToJD($year = null, $month = null, $day = null) {
        $year = is_null($year) ? $this->year : $year;
        $month = is_null($month) ? $this->month : $month;
        $day = is_null($day) ? $this->day : $day;

        $dt = ($year - 1) / 4;
        if ($dt > 0.0) {
            $dt = floor($dt);
        } else {
            $dt = ceil($dt);
        }

        $j = $dt * 1461;

        $dan = $year - ($dt * 4);
        $jd = 1825028.5 + $j + ($dan - 1) * 365 + 30 * ($month - 1) + $day + 0.5;

        if ($dan / 4 == 1) {
            ++$jd;
        }

        return $jd;
    }

    /**
     * Converts the date according to the primary type
     */
    protected function toJD() {
        switch ($this->type) {
            case self::HIJRI:
                if (is_null($this->month)) {
                    $this->firstJDay = $this->hijriToJD($this->year, 1, 1);
                    $this->lastJDay = $this->hijriToJD($this->year, 12, 29);
                } elseif (is_null($this->day)) {
                    $this->firstJDay = $this->hijriToJD($this->year, $this->month, 1);
                    $this->lastJDay = $this->hijriToJD($this->year, $this->month, 29);
                } else {
                    $this->firstJDay = $this->hijriToJD();
                }
                break;
            case self::PERSIAN:
                $monthDays = [0, 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                if (is_null($this->month)) {
                    $this->firstJDay = $this->persianToJD($this->year, 1, 1);
                    $this->lastJDay = $this->persianToJD($this->year, 12, 29);
                } elseif (is_null($this->day)) {
                    $this->firstJDay = $this->persianToJD($this->year, $this->month, 1);
                    $this->lastJDay = $this->persianToJD($this->year, $this->month, $monthDays[$this->month]);
                } else {
                    $this->firstJDay = $this->persianToJD();
                }
                break;
            case self::JEWISH:
                $monthDays = [0, 30, 29, 29, 29, 30, 30, 30, 29, 30, 29, 30, 29];
                if (is_null($this->month)) {
                    $this->firstJDay = $this->jewishToJD($this->year, 1, 1);
                    $this->lastJDay = $this->jewishToJD($this->year, 12, 29);
                } elseif (is_null($this->day)) {
                    $this->firstJDay = $this->jewishToJD($this->year, $this->month, 1);
                    $this->lastJDay = $this->jewishToJD($this->year, $this->month, $monthDays[$this->month]);
                } else {
                    $this->firstJDay = $this->jewishToJD();
                }
                break;
            case self::COPTIC:
                $monthDays = [0, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 5];
                if (is_null($this->month)) {
                    $this->firstJDay = $this->copticToJD($this->year, 1, 1);
                    $this->lastJDay = $this->copticToJD($this->year, 13, 5);
                } elseif (is_null($this->day)) {
                    $this->firstJDay = $this->copticToJD($this->year, $this->month, 1);
                    $this->lastJDay = $this->copticToJD($this->year, $this->month, $monthDays[$this->month]);
                } else {
                    $this->firstJDay = $this->copticToJD();
                }
                break;
            case self::LIBYAN:
                $monthDays = [0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                if (is_null($this->month)) {
                    $this->firstJDay = $this->libyanToJD($this->year, 1, 1);
                    $this->lastJDay = $this->libyanToJD($this->year, 12, $monthDays[12]);
                } elseif (is_null($this->day)) {
                    $this->firstJDay = $this->libyanToJD($this->year, $this->month, 1);
                    $this->lastJDay = $this->libyanToJD($this->year, $this->month, $monthDays[$this->month]);
                } else {
                    $this->firstJDay = $this->libyanToJD();
                }
                break;
            case self::LIBYAN_MILADY:
                $monthDays = [0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                if (is_null($this->month)) {
                    $this->firstJDay = $this->libyanMiladyToJD($this->year, 1, 1);
                    $this->lastJDay = $this->libyanMiladyToJD($this->year, 12, $monthDays[12]);
                } elseif (is_null($this->day)) {
                    $this->firstJDay = $this->libyanMiladyToJD($this->year, $this->month, 1);
                    $this->lastJDay = $this->libyanMiladyToJD($this->year, $this->month, $monthDays[$this->month]);
                } else {
                    $this->firstJDay = $this->libyanMiladyToJD();
                }
                break;
            case self::GREGORIAN:
            default:
                $monthDays = [0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                if (is_null($this->month)) {
                    $this->firstJDay = $this->gregorianToJD($this->year, 1, 1);
                    $this->lastJDay = $this->gregorianToJD($this->year, 12, 31);
                } elseif (is_null($this->day)) {
                    $this->firstJDay = $this->gregorianToJD($this->year, $this->month, 1);
                    $this->lastJDay = $this->gregorianToJD($this->year, $this->month, $monthDays[$this->month]);
                } else {
                    $this->firstJDay = $this->gregorianToJD();
                }
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

        $y = floor((30 * ($this->firstJDay - 1948439.5) + 10646) / 10631);
        $year = [$y];
        $month = [];
        $day = null;
        if (!is_null($this->month)) {
            $m = min(12, ceil(($this->firstJDay - (29 + $this->hijriToJD($y, 1, 1))) / 29) + 1);
            $month[] = $m;
        }

        if (!is_null($this->day)) {
            $day = $this->firstJDay - $this->hijriToJD($y, $m, 1) + 1;
        }

        if ($this->lastJDay) {
            $y = floor((30 * ($this->lastJDay - 1948439.5) + 10646) / 10631);
            if ($year[0] !== $y) {
                $year[] = $y;
            }
            if (!is_null($this->month)) {
                $m = min(12, ceil(($this->lastJDay - (29 + $this->hijriToJD($y, 1, 1))) / 29) + 1);
                $month[] = $m;
            }
        }

        return ['year' => implode('-', $year), 'month' => implode('-', $month), 'day' => $day];
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

        $epoch = $this->firstJDay - $this->persianToJD(475, 1, 1);
        $cycle = floor($epoch / 1029983);
        $cannee = $epoch % 1029983;
        if ($cannee == 1029982) {
            $ycycle = 2820;
        } else {
            $aux1 = floor($cannee / 366);
            $aux2 = $cannee % 366;
            $ycycle = floor(($aux1 * 2134 + 2816 * $aux2 + 2815) / 1028522) + $aux1 + 1;
        }

        $y = $ycycle + (2820 * $cycle) + 474;
        if ($y <= 0) {
            --$y;
        }
        $year = [$y];
        $month = [];
        $day = null;

        if (!is_null($this->month)) {
            $yday = $this->firstJDay - $this->persianToJD($y, 1, 1) + 1;
            $m = $yday > 186 ? ceil($yday / 31) : ceil(($yday - 6) / 30);
            $month = [$m];

            if (!is_null($this->day)) {
                $day = $this->firstJDay - $this->persianToJD($y, $m, 1) + 1;
            }
        }

        if ($this->lastJDay) {
            $epoch = $this->lastJDay - $this->persianToJD(475, 1, 1);
            $cycle = floor($epoch / 1029983);
            $cannee = $epoch % 1029983;
            if ($cannee == 1029982) {
                $ycycle = 2820;
            } else {
                $aux1 = floor($cannee / 366);
                $aux2 = $cannee % 366;
                $ycycle = floor(($aux1 * 2134 + 2816 * $aux2 + 2815) / 1028522) + $aux1 + 1;
            }

            $y = $ycycle + (2820 * $cycle) + 474;
            if ($y <= 0) {
                --$y;
            }

            if ($year[0] !== $y) {
                $year[] = $y;
            }

            if (!is_null($this->month)) {
                $yday = $this->lastJDay - $this->persianToJD($y, 1, 1) + 1;
                $m = $yday > 186 ? ceil($yday / 31) : ceil(($yday - 6) / 30);
                if ($month[0] !== $m) {
                    $month[] = $m;
                }
            }
        }

        return ['year' => implode('-', $year), 'month' => implode('-', $month), 'day' => $day];
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

        list($m, $d, $y) = explode('/', jdtojewish($this->firstJDay));
        $year = [$y];
        $month = [];
        $day = $d;
        if (!is_null($this->month)) {
            $month = [$m];
        }

        if (!is_null($this->day)) {
            $day = $d;
        }

        if ($this->lastJDay) {
            list($m, $d, $y) = explode('/', jdtojewish($this->lastJDay));
            if ($year[0] != $y) {
                $year[] = $y;
            }

            if (!is_null($this->month)) {
                if ($month[0] !== $m) {
                    $month[] = $m;
                }
            }
        }

        return ['year' => implode('-', $year), 'month' => implode('-', $month), 'day' => $day];
    }

    /**
     * Get the Coptic date corressponding to the current date
     *
     * @return array associative array contains converted Coptic date keys are 'year', 'month', 'day'
     */
    public function getCopticDate() {
        $borne = [0, 365, 730, 1096, 1461];
        $dt = $this->firstJDay - 1825029.5;
        $tmpY = $dt / 1461;
        $y = $tmpY > 0.0 ? floor($tmpY) : ceil($tmpY);

        $dt = $dt - (floor($tmpY) * 1461) + 1;
        $y *= 4;
        $jan = 0;
        for ($i = 0; $i < 4; ++$i) {
            if ($dt > $borne[$i] && $dt <= $borne[$i + 1]) {
                $jan = $i + 1;
                $dt -= $borne[$i];
            }
        }
        $y += $jan;
        $year = [$y];
        $month = [];
        $day = null;
        if (!is_null($this->month)) {
            $dj = ($dt - 1) / 30;
            $m = $dj > 0.0 ? floor($dj) : ceil($dj);

            if (!is_null($this->day)) {
                $d = $dt - ($m * 30);
                $day = ($d > 0.0) ? floor($d) : ceil($d);
            }

            $month[] = $m + 1;
        }

        if ($this->lastJDay) {
            $dt = $this->lastJDay - 1825029.5;
            $tmpY = $dt / 1461;
            $y = $tmpY > 0.0 ? floor($tmpY) : ceil($tmpY);

            $dt = $dt - (floor($tmpY) * 1461) + 1;
            $y *= 4;

            $jan = 0;
            for ($i = 0; $i < 4; ++$i) {
                if ($dt > $borne[$i] && $dt <= $borne[$i + 1]) {
                    $jan = $i + 1;
                    $dt -= $borne[$i];
                }
            }
            $y += $jan;
            if ($year[0] !== $y) {
                $year[] = $y;
            }
            if (!is_null($this->month)) {
                $dj = ($dt - 1) / 30;
                $m = $dj > 0.0 ? floor($dj) : ceil($dj);
                $m++;
                if ($month[0] !== $m) {
                    $month[] = $m;
                }
            }
        }

        return ['year' => implode('-', $year), 'month' => implode('-', $month), 'day' => $day];
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

        list($m, $day, $y) = explode('/', jdtogregorian($this->firstJDay));
        $year = [$y];
        $month = [];
        if (!is_null($this->month)) {
            $month[] = $m;
        }
        if (is_null($this->day)) {
            $day = null;
        }

        if ($this->lastJDay) {
            list($m, $d, $y) = explode('/', jdtogregorian($this->lastJDay));
            if ($year[0] !== $y) {
                $year[] = $y;
            }
            if (!is_null($this->month)) {
                if ($month[0] !== $m) {
                    $month[] = $m;
                }
            }
        }
        return ['year' => implode('-', $year), 'month' => implode('-', $month), 'day' => $day];
    }

     /**
     * Get the Libyan date corressponding to the current date
     *
     * @return array associative array contains converted Gregorian date keys are 'year', 'month', 'day'
     */
    public function getLibyanDate() {
        if (self::LIBYAN == $this->type) {
            return ['year' => $this->year, 'month' => $this->month, 'day' => $this->day];
        }

        list($m, $day, $y) = explode('/', jdtogregorian($this->firstJDay));
        $y -= 632;
        $year = [$y];
        $month = [];
        if (!is_null($this->month)) {
            $month[] = $m;
        }
        if (is_null($this->day)) {
            $day = null;
        }

        if ($this->lastJDay) {
            list($m, $d, $y) = explode('/', jdtogregorian($this->lastJDay));
            $y -= 632;
            if ($year[0] !== $y) {
                $year[] = $y;
            }
            if (!is_null($this->month)) {
                if ($month[0] !== $m) {
                    $month[] = $m;
                }
            }
        }
        return ['year' => implode('-', $year), 'month' => implode('-', $month), 'day' => $day];
    }

     /**
     * Get the Libyan Milady date corressponding to the current date
     *
     * @return array associative array contains converted Gregorian date keys are 'year', 'month', 'day'
     */
    public function getLibyanMiladyDate() {
        if (self::LIBYAN == $this->type) {
            return ['year' => $this->year, 'month' => $this->month, 'day' => $this->day];
        }

        list($m, $day, $y) = explode('/', jdtogregorian($this->firstJDay));
        $y -= 570;
        $year = [$y];
        $month = [];
        if (!is_null($this->month)) {
            $month[] = $m;
        }
        if (is_null($this->day)) {
            $day = null;
        }

        if ($this->lastJDay) {
            list($m, $d, $y) = explode('/', jdtogregorian($this->lastJDay));
            $y -= 570;
            if ($year[0] !== $y) {
                $year[] = $y;
            }
            if (!is_null($this->month)) {
                if ($month[0] !== $m) {
                    $month[] = $m;
                }
            }
        }
        return ['year' => implode('-', $year), 'month' => implode('-', $month), 'day' => $day];
    }

    /**
     * Get the Julian Day corresponding to the input date
     *
     * @return integer the Julain day corresponding to the input date
     */
    public function getJulianDay() {
        $jd = $this->firstJDay;
        if ($this->lastJDay) {
            $jd .= '-' . $this->lastJDay;
        }

        return $jd;
    }

}
