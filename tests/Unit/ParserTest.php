<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Source\Parsedata\Parser;

class ParserTest extends TestCase
{
    public function testOneCourseParsing() {
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
}