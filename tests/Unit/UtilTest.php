<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Source\EntryPoint;

class UtilTest extends TestCase
{
    public function testEducationYear()
    {
        $d = (new \DateTime('09/21/2018'))->format('U');
        $ey = EntryPoint::getCurrentEducationYear($d);
        $this->assertEquals('1819', $ey);

        $d = (new \DateTime('08/30/2018'))->format('U');
        $ey = EntryPoint::getCurrentEducationYear($d);
        $this->assertEquals('1718', $ey);

        $d = (new \DateTime('08/30/2019'))->format('U');
        $ey = EntryPoint::getCurrentEducationYear($d);
        $this->assertEquals('1819', $ey);

        $d = (new \DateTime('01/01/2019'))->format('U');
        $ey = EntryPoint::getCurrentEducationYear($d);
        $this->assertEquals('1819', $ey);

        $d = (new \DateTime('01/01/2020'))->format('U');
        $ey = EntryPoint::getCurrentEducationYear($d);
        $this->assertEquals('1920', $ey);
    }
}
