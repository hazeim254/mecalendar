<?php

namespace MeCalendars;

require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'MeCalendars' . DIRECTORY_SEPARATOR . 'Converter.php';

class ConverterTest extends \PHPUnit_Framework_TestCase{
    
    protected function setUp() {
        parent::setUp();
    }
    
    public function testConstructor() {
        $converter = new Converter(2013, 11, 20);
        $jd = $converter->getJulianDay();
        $this->assertTrue($jd > 0, 'Constructor is not working :(');
        $this->assertEquals($jd, gregoriantojd(11, 20, 2013), 'Wrong julain day :\'(');
    }
    
    public function testGregToHijriConvert() {
        $converter = new Converter(2013, 11, 20);
        $hijri = $converter->getHijriDate();
        $this->assertEquals($hijri['year'], 1435, 'Invalid Hijri Year :(');
        $this->assertEquals($hijri['month'], 1, 'Invalid Hijri Month :(');
    }
    
    function testHijriToGreg() {
        $converter = new Converter(1435, 1, 16, Converter::HIJRI);
        $gregorian = $converter->getGregorianDate();
        $this->assertEquals(2013, $gregorian['year'], 'Wrong gregorian year :(');
        $this->assertEquals(11, $gregorian['month'], 'Wrong gregorian month :\'(');
    }
    
    function testJewishToGreg(){
        $gregYear = 2013;
        $gregMonth = 11;
        $gregDay = 20;
        
        $jewConverter = new Converter($gregYear, $gregMonth, $gregDay);
        $jewDate = $jewConverter->getJewishDate();
        
        $gregConverter = new Converter($jewDate['year'], $jewDate['month'], $jewDate['day'], Converter::JEWISH);
        $gregDate = $gregConverter->getGregorianDate();
        
        $this->assertEquals($gregYear, $gregDate['year'], 'Invalid year');
        $this->assertEquals($gregMonth, $gregDate['month'], 'Invalid month');
        $this->assertEquals($gregDay, $gregDate['day'], 'Invalid day');
        
    }
	
	function testPersianToGreg(){
        $gregYear = 2013;
        $gregMonth = 11;
        $gregDay = 20;
        $gregConverter = new Converter(1392, 8, 29, Converter::PERSIAN);
		var_dump($gregConverter->getGregorianDate());
		
        $persConverter = new Converter($gregYear, $gregMonth, $gregDay);
        $persDate = $persConverter->getPersianDate();
		
        $gregConverter = new Converter($persDate['year'], $persDate['month'], $persDate['day'], Converter::PERSIAN);
        $gregDate = $gregConverter->getGregorianDate();
        
        $this->assertEquals($gregYear, $gregDate['year'], 'Invalid year');
        $this->assertEquals($gregMonth, $gregDate['month'], 'Invalid month');
        $this->assertEquals($gregDay, $gregDate['day'], 'Invalid day');
        
    }
}

