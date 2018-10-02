<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Source\Parsedata\Parser;

class ParserTest extends TestCase
{
    public function testOneCourseParsing()
    {
        $html = \file_get_contents(__DIR__.'/../resources/onecourse.html');
        $courses = Parser::parseHTMLForCourses($html);

        // There should only be one course.
        $this->assertEquals(1, count($courses));

        // That course should have only 1 course moment
        $courseArray = reset($courses);
        $this->assertEquals(1, count($courseArray));

        // That one course moment should
        // TODO: check for time, date and place.
        $courseMoment = reset($courseArray);
    }

    public function testNameParsing()
    {
        $name = Parser::getNameFromString('Formal Systems and their Applications: Lecture');
        $this->assertEquals('Formal Systems and their Applications', $name);

        $name = Parser::getNameFromString('Frans in de bedrijfsomgeving');
        $this->assertEquals('Frans in de bedrijfsomgeving', $name);

        $name = Parser::getNameFromString('Capita Selecta Computer Science: Artificial Intelligence');
        $this->assertEquals('Capita Selecta Computer Science: Artificial Intelligence', $name);
    }
}
