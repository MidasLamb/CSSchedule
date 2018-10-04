<?php

namespace Source\Parsedata;

use Source\Utility\Str;

class CourseMoment
{
    public $datumString;
    public $timeString;
    public $placeString;
    public $nameString;
    public $url;

    public function __construct($url, $nameString, $timeString, $datumString, $placeString){
        $this->url = $url;
        $this->nameString = $nameString;
        $this->timeString = $timeString;
        $this->datumString = $datumString;
        $this->placeString = $placeString;
    }

    public function getICSString()
    {
        $str = new Str();
        $str->addLine("BEGIN:VEVENT");
        $str->addLine("UID:".$this->constructUID());
        $str->addLine("DTSTAMP:".$this->constructStartTimeStamp());
        $str->addLine("DTSTART:".$this->constructStartTimeStamp());
        $str->addLine("DTEND:".$this->constructEndTimeStamp());
        $str->addLine("LOCATION:".$this->placeString);
        $str->addLine("SUMMARY:".$this->nameString);
        $str->addLine("END:VEVENT");
        return $str->getString();
    }

    public function getCourseID()
    {
        $regex = '#/([^/]*?).htm#';
        preg_match_all($regex, $this->url, $matches);
        return $matches[1][0];
    }

    public function constructUID()
    {
        return hash("md5", $this->datumString.$this->timeString.$this->placeString.$this->nameString.$this->url)."@midcalendar.com";
    }

    public function constructStartTimeStamp()
    {
        $dateregex = '# (.*?):#';
        preg_match_all($dateregex, $this->datumString, $dateMatches);
        $date = $dateMatches[1][0];

        $startTime = explode('—', $this->timeString)[0];

        $format ='H:i d.m.Y';
        $dateTimeString = $startTime.' '.$date;
        $dateobj = \DateTime::createFromFormat($format, $dateTimeString);
        return $dateobj->format('Ymd\THis');
    }

    public function constructEndTimeStamp()
    {
        $dateregex = '# (.*?):#';
        preg_match_all($dateregex, $this->datumString, $dateMatches);
        $date = $dateMatches[1][0];

        $startTime = explode('—', $this->timeString)[1];

        $format ='H:i d.m.Y';
        $dateTimeString = $startTime.' '.$date;
        $dateobj = \DateTime::createFromFormat($format, $dateTimeString);
        return $dateobj->format('Ymd\THis');
    }

    public static function createICSFromDBRow($row)
    {
        $str = new Str();
        $str->addLine("BEGIN:VEVENT");
        $str->addLine("UID:".self::createUIDFromDBRow($row));
        $str->addLine("DTSTAMP:".$row['DTStamp']);
        $str->addLine("DTSTART:".$row['DTStart']);
        $str->addLine("DTEND:".$row['DTEnd']);
        $str->addLine("LOCATION:".$row['Location']);
        $str->addLine("SUMMARY:".$row['Name']);
        $str->addLine("END:VEVENT");
        return $str->getString();
    }

    public static function createUIDFromDBRow($row)
    {
        return hash("md5", $row['CourseId'].$row['DTStart'].$row['Location'])."@midcalendar.com";
    }
}
