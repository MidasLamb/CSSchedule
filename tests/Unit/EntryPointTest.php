<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Source\EntryPoint;

class EntryPointTest extends TestCase
{
    public function testNoExceptionsCalendar(){
        $this->setOutputCallback(function() {});
        $_GET['courses'] = 'H04G1BN';
        try {
            EntryPoint::calendar(false);
            $this->assertTrue(true);
        } catch (\Throwable $e){
            $this->fail('Exception/Error has been thrown.');
        }
    }

    public function testNoExceptionsHome(){
        $this->setOutputCallback(function() {});
        try {
            EntryPoint::home();
            $this->assertTrue(true);
        } catch (\Throwable $e){
            $this->fail('Exception/Error has been thrown.');
        }
    }
}