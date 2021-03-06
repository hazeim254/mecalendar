<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use MeCalendar\Converter;

class ConverterTest extends \PHPUnit_Framework_TestCase {

    protected function setUp() {
        parent::setUp();
    }

    /**
     * @group Other
     */
    public function testConstructor() {
        $converter = new Converter(2013, Converter::GREGORIAN, 11, 20);
        $jd = $converter->getJulianDay();
        $this->assertTrue($jd > 0, 'Constructor is not working :(');
        $this->assertEquals($jd, gregoriantojd(11, 20, 2013), 'Wrong julain day :\'(');
    }

    /**
     * @group Hijri
     */
    public function testGregToHijriConvert() {
        $converter = new Converter(2013, Converter::GREGORIAN, 11, 20);
        $hijri = $converter->getHijriDate();
        $this->assertEquals($hijri['year'], 1435, 'Invalid Hijri Year :(');
        $this->assertEquals($hijri['month'], 1, 'Invalid Hijri Month :(');
    }

    /**
     * @group Hijri
     */
    public function testHijriToGreg() {
        $converter = new Converter(1435, Converter::HIJRI, 1, 16);
        $gregorian = $converter->getGregorianDate();
        $this->assertEquals(2013, $gregorian['year'], 'Wrong gregorian year :(');
        $this->assertEquals(11, $gregorian['month'], 'Wrong gregorian month :\'(');
    }

    /**
     * @group Jewish
     */
    public function testJewishToGreg() {
        $gregYear = 2013;
        $gregMonth = 11;
        $gregDay = 20;

        $jewConverter = new Converter($gregYear, Converter::GREGORIAN, $gregMonth, $gregDay);
        $jewDate = $jewConverter->getJewishDate();
        $gregConverter = new Converter($jewDate['year'], Converter::JEWISH, $jewDate['month'], $jewDate['day']);
        $gregDate = $gregConverter->getGregorianDate();

        $this->assertEquals($gregYear, $gregDate['year'], 'Invalid year');
        $this->assertEquals($gregMonth, $gregDate['month'], 'Invalid month');
        $this->assertEquals($gregDay, $gregDate['day'], 'Invalid day');
    }

    /**
     * @group Persian
     */
    public function testPersianToGreg() {
        $gregYear = 2013;
        $gregMonth = 11;
        $gregDay = 20;
        $gregConverter = new Converter(1392, Converter::PERSIAN, 8, 29);

        $persConverter = new Converter($gregYear, Converter::GREGORIAN, $gregMonth, $gregDay);
        $persDate = $persConverter->getPersianDate();

        $gregConverter = new Converter($persDate['year'], Converter::PERSIAN, $persDate['month'], $persDate['day']);
        $gregDate = $gregConverter->getGregorianDate();

        $this->assertEquals($gregYear, $gregDate['year'], 'Invalid year');
        $this->assertEquals($gregMonth, $gregDate['month'], 'Invalid month');
        $this->assertEquals($gregDay, $gregDate['day'], 'Invalid day');
    }

    /**
     * @group Hijri
     */
    public function testGregToHijriYear() {
        $year = 2013;
        $conv = new Converter($year, Converter::GREGORIAN);
        $hijri = $conv->getHijriDate();

        $this->assertEquals('1434-1435', $hijri['year']);
    }

    /**
     * @group Hijri
     */
    public function testGregToHijriYearMonth() {
        $year = 2013;
        $month = 12;
        $conv = new Converter($year, Converter::GREGORIAN, $month);
        $hijri = $conv->getHijriDate();

        $this->assertEquals('1435', $hijri['year']);
        $this->assertEquals('1-2', $hijri['month']);
    }

    /**
     * @group Hijri
     */
    public function testHijriToGregYear() {
        $year = 1435;

        $conv = new Converter($year, Converter::HIJRI);
        $greg = $conv->getGregorianDate();

        $this->assertEquals('2013-2014', $greg['year']);
    }

    /**
     * @group Hijri
     */
    public function testHijriToGregYearMonth() {
        $year = 1435;
        $month = 1;

        $conv = new Converter($year, Converter::HIJRI, $month);
        $greg = $conv->getGregorianDate();

        $this->assertEquals('2013', $greg['year']);
        $this->assertEquals('11-12', $greg['month']);
    }

    /**
     * @group Jewish
     */
    public function testJewishToGregYear() {
        $year = 5774;

        $conv = new Converter($year, Converter::JEWISH);
        $greg = $conv->getGregorianDate();

        $this->assertEquals('2013-2014', $greg['year']);
    }

    /**
     * @group Jewish
     */
    public function testJewishToGregYearMonth() {
        $year = 5774;
        $month = 3;

        $conv = new Converter($year, Converter::JEWISH, $month);
        $jewish = $conv->getGregorianDate();

        $this->assertEquals('2013', $jewish['year']);
        $this->assertEquals('11-12', $jewish['month']);
    }

    /**
     * @group Jewish
     */
    public function testGregToJewishYear() {
        $year = 2013;

        $conv = new Converter($year, Converter::GREGORIAN);
        $greg = $conv->getJewishDate();

        $this->assertEquals('5773-5774', $greg['year']);
    }

    /**
     * @group Jewish
     */
    public function testGregToJewishYearMonth() {
        $year = 2013;
        $month = 12;

        $conv = new Converter($year, Converter::GREGORIAN, $month);
        $jewish = $conv->getJewishDate();

        $this->assertEquals('5774', $jewish['year']);
        $this->assertEquals('3-4', $jewish['month']);
    }

    /**
     * @group Persian
     */
    public function testPersianToGregYear() {
        $year = 1392;

        $conv = new Converter($year, Converter::PERSIAN);
        $greg = $conv->getGregorianDate();

        $this->assertEquals('2013-2014', $greg['year']);
    }

    /**
     * @group Persian
     */
    public function testPersianToGregYearMonth() {
        $year = 1392;
        $month = 8;

        $conv = new Converter($year, Converter::PERSIAN, $month);
        $jewish = $conv->getGregorianDate();

        $this->assertEquals('2013', $jewish['year']);
        $this->assertEquals('10-11', $jewish['month']);
    }

    /**
     * @group Persian
     */
    public function testGregToPerianYear() {
        $year = 2013;

        $conv = new Converter($year, Converter::GREGORIAN);
        $greg = $conv->getPersianDate();

        $this->assertEquals('1391-1392', $greg['year']);
    }

    /**
     * @group Persian
     */
    public function testGregToPersianYearMonth() {
        $year = 2013;
        $month = 12;

        $conv = new Converter($year, Converter::GREGORIAN, $month);
        $jewish = $conv->getPersianDate();

        $this->assertEquals('1392', $jewish['year']);
        $this->assertEquals('9-10', $jewish['month']);
    }

    /**
     * @group Coptic
     */
    public function testCopticToGreg() {
        $year = 1730;
        $month = 3;
        $day = 25;

        $conv = new Converter($year, Converter::COPTIC, $month, $day);
        $greg = $conv->getGregorianDate();

        $this->assertEquals(2013, $greg['year']);
        $this->assertEquals(12, $greg['month']);
        $this->assertEquals(4, $greg['day']);
    }

    /**
     * @group Coptic
     */
    public function testCopticToGregYear() {
        $year = 1730;

        $conv = new Converter($year, Converter::COPTIC);
        $greg = $conv->getGregorianDate();

        $this->assertEquals('2013-2014', $greg['year']);
    }

    /**
     * @group Coptic
     */
    public function testCopticToGregYearMonth() {
        $year = 1730;
        $month = 3;

        $conv = new Converter($year, Converter::COPTIC, $month);
        $greg = $conv->getGregorianDate();

        $this->assertEquals(2013, $greg['year']);
        $this->assertEquals('11-12', $greg['month']);
    }

    /**
     * @group Coptic
     */
    public function testGregToCoptic() {
        $year = 2013;
        $month = 12;
        $day = 31;
        $conv = new Converter($year, Converter::GREGORIAN, $month, $day);
        $copt = $conv->getCopticDate();

        $this->assertEquals('1730', $copt['year']);
        $this->assertEquals('4', $copt['month']);
        $this->assertEquals('22', $copt['day']);
    }

    /**
     * @group Coptic
     */
    public function testGregToCopticYear() {
        $year = 2013;
        $conv = new Converter($year, Converter::GREGORIAN);
        $copt = $conv->getCopticDate();

        $this->assertEquals('1729-1730', $copt['year']);
    }

    /**
     * @group Coptic
     */
    public function testGregToCopticYearMonth() {
        $year = 2013;
        $month = 12;
        $conv = new Converter($year, Converter::GREGORIAN, $month);
        $copt = $conv->getCopticDate();

        $this->assertEquals('1730', $copt['year']);
        $this->assertEquals('3-4', $copt['month']);
    }

    /**
     * @group Libyan
     */
    public function testLibyanToGreg() {
        $year = 1381;
        $month = 12;
        $day = 9;

        $conv = new Converter($year, Converter::LIBYAN, $month, $day);
        $greg = $conv->getGregorianDate();

        $this->assertEquals(2013, $greg['year']);
        $this->assertEquals(12, $greg['month']);
        $this->assertEquals(9, $greg['day']);
    }

    /**
     * @group Libyan
     */
    public function testLibyanToGregYear() {
        $year = 1381;

        $conv = new Converter($year, Converter::LIBYAN);
        $greg = $conv->getGregorianDate();

        $this->assertEquals('2013', $greg['year']);
    }

    /**
     * @group Libyan
     */
    public function testLibyanToGregYearMonth() {
        $year = 1381;
        $month = 12;

        $conv = new Converter($year, Converter::LIBYAN, $month);
        $greg = $conv->getGregorianDate();

        $this->assertEquals(2013, $greg['year']);
        $this->assertEquals(12, $greg['month']);
    }

    /**
     * @group Libyan
     */
    public function testGregToLibyan() {
        $year = 2013;
        $month = 12;
        $day = 31;
        $conv = new Converter($year, Converter::GREGORIAN, $month, $day);
        $liby = $conv->getLibyanDate();

        $this->assertEquals('1381', $liby['year']);
        $this->assertEquals('12', $liby['month']);
        $this->assertEquals('31', $liby['day']);
    }

    /**
     * @group Libyan
     */
    public function testGregToLibyanYear() {
        $year = 2013;
        $conv = new Converter($year, Converter::GREGORIAN);
        $liby = $conv->getLibyanDate();

        $this->assertEquals(1381, $liby['year']);
    }

    /**
     * @group Libyan
     */
    public function testGregToLibyanYearMonth() {
        $year = 2013;
        $month = 12;
        $conv = new Converter($year, Converter::GREGORIAN, $month);
        $liby = $conv->getLibyanDate();

        $this->assertEquals(1381, $liby['year']);
        $this->assertEquals(12, $liby['month']);
    }

    /**
     * @group LibyanMilady
     */
    public function testLibyanMiladyToGreg() {
        $year = 1443;
        $month = 12;
        $day = 9;

        $conv = new Converter($year, Converter::LIBYAN_MILADY, $month, $day);
        $greg = $conv->getGregorianDate();

        $this->assertEquals(2013, $greg['year']);
        $this->assertEquals(12, $greg['month']);
        $this->assertEquals(9, $greg['day']);
    }

    /**
     * @group LibyanMilady
     */
    public function testLibyanMiladyToGregYear() {
        $year = 1443;

        $conv = new Converter($year, Converter::LIBYAN_MILADY);
        $greg = $conv->getGregorianDate();

        $this->assertEquals('2013', $greg['year']);
    }

    /**
     * @group LibyanMilady
     */
    public function testLibyanMiladyToGregYearMonth() {
        $year = 1443;
        $month = 12;

        $conv = new Converter($year, Converter::LIBYAN_MILADY, $month);
        $greg = $conv->getGregorianDate();

        $this->assertEquals(2013, $greg['year']);
        $this->assertEquals(12, $greg['month']);
    }

    /**
     * @group LibyanMilady
     */
    public function testGregToLibyanMilady() {
        $year = 2013;
        $month = 12;
        $day = 31;
        $conv = new Converter($year, Converter::GREGORIAN, $month, $day);
        $liby = $conv->getLibyanMiladyDate();

        $this->assertEquals('1443', $liby['year']);
        $this->assertEquals('12', $liby['month']);
        $this->assertEquals('31', $liby['day']);
    }

    /**
     * @group LibyanMilady
     */
    public function testGregToLibyanMiladyYear() {
        $year = 2013;
        $conv = new Converter($year, Converter::GREGORIAN);
        $liby = $conv->getLibyanMiladyDate();

        $this->assertEquals(1443, $liby['year']);
    }

    /**
     * @group LibyanMilady
     */
    public function testGregToLibyanMiladyYearMonth() {
        $year = 2013;
        $month = 12;
        $conv = new Converter($year, Converter::GREGORIAN, $month);
        $liby = $conv->getLibyanMiladyDate();

        $this->assertEquals(1443, $liby['year']);
        $this->assertEquals(12, $liby['month']);
    }

}
