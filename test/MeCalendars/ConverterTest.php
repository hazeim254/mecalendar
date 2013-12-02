<?php

namespace MeCalendars;

require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'MeCalendars' . DIRECTORY_SEPARATOR . 'Converter.php';

class ConverterTest extends \PHPUnit_Framework_TestCase {

    protected function setUp() {
        parent::setUp();
    }

    public function testConstructor() {
        $converter = new Converter(2013, Converter::GREGORIAN, 11, 20);
        $jd = $converter->getJulianDay();
        $this->assertTrue($jd > 0, 'Constructor is not working :(');
        $this->assertEquals($jd, gregoriantojd(11, 20, 2013), 'Wrong julain day :\'(');
    }

    public function testGregToHijriConvert() {
        $converter = new Converter(2013, Converter::GREGORIAN, 11, 20);
        $hijri = $converter->getHijriDate();
        $this->assertEquals($hijri['year'], 1435, 'Invalid Hijri Year :(');
        $this->assertEquals($hijri['month'], 1, 'Invalid Hijri Month :(');
    }

    function testHijriToGreg() {
        $converter = new Converter(1435, Converter::HIJRI, 1, 16);
        $gregorian = $converter->getGregorianDate();
        $this->assertEquals(2013, $gregorian['year'], 'Wrong gregorian year :(');
        $this->assertEquals(11, $gregorian['month'], 'Wrong gregorian month :\'(');
    }

    function testJewishToGreg() {
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

    function testPersianToGreg() {
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
    
    function testGregToHijriYear() {
        $year = 2013;
        $conv = new Converter($year, Converter::GREGORIAN);
        $hijri = $conv->getHijriDate();
        
        $this->assertEquals('1434-1435', $hijri['year']);
    }

    function testHijriToGregYearMonth() {
        $year = 2013;
        $month = 12;
        
        $conv = new Converter($year, Converter::GREGORIAN, $month);
        $hijri = $conv->getHijriDate();
        
        $this->assertEquals('1435', $hijri['year']);
        $this->assertEquals('1-2', $hijri['month']);
    }
    
    function testHijriToGregYear() {
        $year = 1435;
        
        $conv = new Converter($year, Converter::HIJRI);
        $greg = $conv->getGregorianDate();
        
        $this->assertEquals('2013-2014', $greg['year']);
    }
}

