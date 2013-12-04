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

    public function testHijriToGreg() {
        $converter = new Converter(1435, Converter::HIJRI, 1, 16);
        $gregorian = $converter->getGregorianDate();
        $this->assertEquals(2013, $gregorian['year'], 'Wrong gregorian year :(');
        $this->assertEquals(11, $gregorian['month'], 'Wrong gregorian month :\'(');
    }

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
    
    public function testGregToHijriYear() {
        $year = 2013;
        $conv = new Converter($year, Converter::GREGORIAN);
        $hijri = $conv->getHijriDate();
        
        $this->assertEquals('1434-1435', $hijri['year']);
    }
    
    public function testGregToHijriYearMonth() {
        $year = 2013;
        $month = 12;
        $conv = new Converter($year, Converter::GREGORIAN, $month);
        $hijri = $conv->getHijriDate();
        
        $this->assertEquals('1435', $hijri['year']);
        $this->assertEquals('1-2', $hijri['month']);
    }

    public function testHijriToGregYearMonth() {
        $year = 2013;
        $month = 12;
        
        $conv = new Converter($year, Converter::GREGORIAN, $month);
        $hijri = $conv->getHijriDate();
        
        $this->assertEquals('1435', $hijri['year']);
        $this->assertEquals('1-2', $hijri['month']);
    }
    
    public function testHijriToGregYear() {
        $year = 1435;
        
        $conv = new Converter($year, Converter::HIJRI);
        $greg = $conv->getGregorianDate();
        
        $this->assertEquals('2013-2014', $greg['year']);
    }
    
    public function testJewishToGregYear() {
        $year = 5774;
        
        $conv = new Converter($year, Converter::JEWISH);
        $greg = $conv->getGregorianDate();
        
        $this->assertEquals('2013-2014', $greg['year']);
    }
    
    /**
     * @skipped
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
     * @skipped
     */
    public function testGregToJewishYear() {
        $year = 2013;
        
        $conv = new Converter($year, Converter::GREGORIAN);
        $greg = $conv->getJewishDate();
        
        $this->assertEquals('5773-5774', $greg['year']);
    }

    /**
     * @skipped
     */
    public function testGregToJewishYearMonth() {
        $year = 2013;
        $month = 12;
        
        $conv = new Converter($year, Converter::GREGORIAN, $month);
        $jewish = $conv->getJewishDate();
        
        $this->assertEquals('5774', $jewish['year']);
        $this->assertEquals('3-4', $jewish['month']);
    }
    
    public function testPersianToGregYear() {
        $year = 1392;
        
        $conv = new Converter($year, Converter::PERSIAN);
        $greg = $conv->getGregorianDate();
        
        $this->assertEquals('2013-2014', $greg['year']);
    }
    
    public function testPersianToGregYearMonth() {
        $year = 1392;
        $month = 8;
        
        $conv = new Converter($year, Converter::PERSIAN, $month);
        $jewish = $conv->getGregorianDate();
        
        $this->assertEquals('2013', $jewish['year']);
        $this->assertEquals('10-11', $jewish['month']);
    }
    
    /**
     * @skipped
     */
    public function testGregToPerianYear() {
        $year = 2013;
        
        $conv = new Converter($year, Converter::GREGORIAN);
        $greg = $conv->getPersianDate();
        
        $this->assertEquals('1391-1392', $greg['year']);
    }

    /**
     * @skipped
     */
    public function testGregToPersianYearMonth() {
        $year = 2013;
        $month = 12;
        
        $conv = new Converter($year, Converter::GREGORIAN, $month);
        $jewish = $conv->getPersianDate();
        
        $this->assertEquals('1392', $jewish['year']);
        $this->assertEquals('9-10', $jewish['month']);
    }
    
    public function testCopticToGreg(){
        $year = 1730;
        $month = 3;
        $day = 25;
        
        $conv = new Converter($year, Converter::COPTIC, $month, $day);
        $greg = $conv->getGregorianDate();

        $this->assertEquals(2013, $greg['year']);
        $this->assertEquals(12, $greg['month']);
        $this->assertEquals(4, $greg['day']);
    }
    
    public function testCopticToGregYear(){
        $year = 1730;
        
        $conv = new Converter($year, Converter::COPTIC);
        $greg = $conv->getGregorianDate();

        $this->assertEquals('2013-2014', $greg['year']);
    }
    
    public function testCopticToGregYearMonth(){
        $year = 1730;
        $month = 3;
        
        $conv = new Converter($year, Converter::COPTIC, $month);
        $greg = $conv->getGregorianDate();
        
        $this->assertEquals(2013, $greg['year']);
        $this->assertEquals('11-12', $greg['month']);
    }
    
    public function testGregToCoptic() {
//        $this->markTestIncomplete('getCopticDate is not implemented yet');
        
        $year = 2013; $month = 12; $day = 31;
        $conv = new Converter($year, Converter::GREGORIAN, $month, $day);
        $copt = $conv->getCopticDate();
        
        $this->assertEquals('1730', $copt['year']);
        $this->assertEquals('4', $copt['month']);
        $this->assertEquals('22', $copt['day']);
    }
    
    public function testGregToCopticYear() {
//        $this->markTestIncomplete('getCopticDate is not implemented yet');
        
        $year = 2013;
        $conv = new Converter($year, Converter::GREGORIAN);
        $copt = $conv->getCopticDate();

        $this->assertEquals('1729-1730', $copt['year']);
    }
    
    public function testGregToCopticYearMonth() {
//        $this->markTestIncomplete('getCopticDate is not implemented yet');
        
        $year = 2013; $month = 12;
        $conv = new Converter($year, Converter::GREGORIAN, $month);
        $copt = $conv->getCopticDate();
        
        $this->assertEquals('1730', $copt['year']);
        $this->assertEquals('3-4', $copt['month']);
        
    }
}

