<?php

namespace Source;

use Source\Parsedata\Course;
use Source\Parsedata\Parser;
use Source\Utility\Str;
use Source\DB\DBHandler;

class EntryPoint{
    public static function start(){
        $requestUri = $_SERVER['REQUEST_URI'];
        if ($requestUri == "/"){
            self::home();
        } else if (preg_match('#^/calendar#', $requestUri)){
            self::calendar();
        } else {
            echo("404 page not found");
        }
        
    }

    public static function home(){
        $courseArray = [];
        $res = DBHandler::getCourses();
        foreach($res as $row){
            $courseArray[$row["Name"]] = $row;
        }

        ksort($courseArray);

        ob_start();
        include "views/home.php";
        $output = ob_get_clean();

        echo($output);
    }

    public static function calendar(){
        if (isset($_GET["courses"])){
            if (is_array($_GET["courses"])){
                $courses = $_GET["courses"]; //For backwards compatibility.
            } else {
                $courses = explode(',', $_GET["courses"]);
            }
            

            header("content-type:text/calendar");
            $str = new Str();
            $str->addLine("BEGIN:VCALENDAR");
            $str->addLine("VERSION:2.0");
            $str->addLine("PRODID:-//midaslambrichts/cscalendar//cscalendar v1.0//EN");
            $str->addLine("X-WR-TIMEZONE:Europe/Brussels");
            $str->addLine("X-WR-CALNAME:CSSchedule");
            foreach($courses as $courseId){
                $res = DBHandler::getCourseMoments($courseId);
                foreach($res as $row){
                    $str->addContent(Course::createICSFromDBRow($row));
                }
            }
            $str->addLine("END:VCALENDAR");

            echo($str->getString());
        }
    }

    public static function updateDB(){

        $currentYear = intval(date('y'));
        $september = strtotime('first day of september this year');
        $now = time();
        if ($september < $now){
            // We're currently in first semester;
            $nextYear = $currentYear + 1;
            $educationYear = "$currentYear$nextYear";
        } else {
            // We're currently in the second semester
            $prevYear = $currentYear - 1;
            $educationYear = "$prevYear$currentYear";
        }

        $urls = [
            "http://people.cs.kuleuven.be/~btw/roosters$educationYear/cws_semester_1.html",
            "http://people.cs.kuleuven.be/~btw/roosters$educationYear/cws_semester_2.html"
        ];

        $groupedByCourse = Parser::parseURLsForSchedule($urls);

        $courseIdNameMap = Parser::parseIDsForNames(array_keys($groupedByCourse), $urls);

        DBHandler::updateDatabaseCourses($courseIdNameMap);
        DBHandler::updateDatabaseCourseMoments($groupedByCourse);

        return;
    }

    public function otherParse(){
        $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        $response = $guzzleClient->request('GET', "https://webwsp.aps.kuleuven.be/sap(bD1ubCZjPTIwMA==)/public/bsp/sap/z_mijnuurrstrs/uurrooster_sem_lijst.htm?sap-params=dGl0ZWxsaWpzdD1VdXJyb29zdGVyJm90eXBlPVNDJm9iamlkPTUxMjMwNDExJmJlZ2lud2VlazE9MjAxNzM5JmVpbmRld2VlazE9MjAxNzUxJmJlZ2lud2VlazI9Mjk5OTAxJmVpbmRld2VlazI9MjAwMDAxJnNjX29iamlkPTAwMDAwMDAwJnNlc3Npb25pZD0yRTFERTBGNjM2MEQxRUQ3QTlCOTlDQUM3QzIwQzAxMyZ0eXBlX2dyb2VwPQ%3d%3d");


        $fullpage = (string)$response->getBody();

        $fullpage = trim(preg_replace('/\s\s+/', ' ', $fullpage));
        $fullpage = trim(preg_replace('/<img[^>]*>/', '', $fullpage));


        $regex = '/<TABLE[^\>]*?align="center"[^\>]*?>.*?<\/table>(.*)<table/i';
        $res = preg_match_all($regex, $fullpage, $matches);

        $tablesString = $matches[1][0];

        $regex = '/<TABLE[^>].*?>.*?<\/table>/i';
        $res = preg_match_all($regex, $tablesString, $matches);

        $tables = $matches[0];

        $infoTables = [];

        foreach($tables as $table){
            if (strpos($table, 'strong') === false) {
                $infoTables[] = $table;
            }
        }

        $groupedInfoTables = [];

        for($i = 0; $i < count($infoTables); $i += 2){
            $group = [$infoTables[$i],$infoTables[$i+1]];
            $groupedInfoTables[] = $group;
        }

        foreach($groupedInfoTables as $course){
            libxml_use_internal_errors(true);
            $document = new \DomDocument();
            $document->loadHTML($course[0]);
            $tds = [];
            foreach($document->getElementsByTagName('td') as $td){
                $tds[] = $td;
            }
            //d($tds[1]->textContent); //Tijd
            //d($tds[2]->textContent); //Lokaal
            //d($tds[3]->textContent); //VakCode
            //d($tds[4]->textContent); //Vaknaam
            //d($tds[5]->textContent); //Naam Docent

            $document = new \DomDocument();
            //$string = str_replace(' ', '', $course[1]);
            $string = str_replace('<td width="35"></td>', '', $course[1]);
            
            $document->loadHTML($string);
            $data = [];
            foreach($document->getElementsByTagName('td') as $td){
                $data[] = $td->textContent;
            }

            d($data);

        }

        return;
    }
}