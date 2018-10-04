<?php

namespace Source\Parsedata;

use GuzzleHttp\Client;

class Parser
{
    public static function parseURLsForSchedule($arrayOfURLs)
    {
        $guzzleClient = new Client();
        $courses = [];

        foreach ($arrayOfURLs as $url) {
            $response = $guzzleClient->request('GET', $url);
            
            $fullpage = (string) $response->getBody();

            $foundCourses = self::parseHTMLForCourses($fullpage);
            foreach ($foundCourses as $id => $foundCourse) {
                if (array_key_exists($id, $courses)){
                    $courses[$id] = array_merge($foundCourse, $courses[$id]);
                } else {
                    $courses[$id] = $foundCourse;
                }
            }
        }
        return $courses;
    }

    public static function parseHTMLForCourses($html)
    {
        $courses = [];
        libxml_use_internal_errors(true);
        $document = new \DomDocument();
        $succesfullRead = $document->loadHTML($html);

        // Fetch the days
        $regex = '#<i><b>(.*?)</i>#';
        $res = preg_match_all($regex, $html, $matches);
        $parsedDays = $matches[1];

        foreach ($document->getElementsByTagName('table') as $index => $htmltable) {
            foreach ($htmltable->getElementsByTagName('tr') as $htmlrow) {
                $url = $htmlrow->childNodes[5]->firstChild->attributes->getNamedItem("href")->textContent;
                $datumString = $parsedDays[$index];
                $timeString = $htmlrow->childNodes[0]->textContent;
                $placeString = $htmlrow->childNodes[2]->textContent;
                $nameString = $htmlrow->childNodes[5]->textContent;
                $course = new CourseMoment($url, $nameString, $timeString, $datumString, $placeString);
                $courses[$course->getCourseID()][] = $course;
            }
        }

        return $courses;
    }

    public static function getNameFromString($st)
    {
        // Capita selecta has a semi colon, which we don't want to split on.
        if (strpos($st, 'Capita Selecta') !== false) {
            return $st;
        } else {
            return explode(':', $st)[0];
        }
    }

    public static function parseIDsForNames($courseIds, $backupurls)
    {
        $guzzleClient = new Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        
        $courseIdNameMap = [];
        /* Uses the ECTS Page for the name, but is way slower.
        foreach($courseIds as $courseId){
            try {
                $languageLetter = strtolower(substr($courseId, -1));
                $response = $guzzleClient->request('GET', "http://onderwijsaanbod.kuleuven.be//syllabi/$languageLetter/$courseId.htm");
                $fullpage = $response->getBody();

                $regex = '#<h2>(.*?)<span class="extraheading">#';
                $res = preg_match_all($regex, $fullpage, $matches);
                $name = $matches[1][0];
                $courseIdNameMap[$courseId] = $name;
            } catch (\GuzzleHttp\Exception\ServerException $e){

            }

        }
        */
        // Search for the name in these urls based on CourseID, but this means that words like "Exercise", "Lecture" may be included.
        foreach ($backupurls as $url) {
            $foundCourseIds = array_keys($courseIdNameMap);
            $notFoundCourseIds = array_diff($courseIds, $foundCourseIds);

            $response = $guzzleClient->request('GET', $url);
            $fullpage = $response->getBody();
            foreach ($notFoundCourseIds as $courseId) {
                $regex = '#<a [^>]*?'.$courseId.'.*?><font.*?>(.*?)</font></a>#';
                $res = preg_match($regex, $fullpage, $match);
                if (count($match) > 0) {
                    $courseIdNameMap[$courseId] = self::getNameFromString($match[1]);
                }
            }
        }
        return $courseIdNameMap;
    }
}
