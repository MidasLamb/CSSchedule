<?php

namespace Source;

use Source\Parsedata\Course;
use Source\Parsedata\Parser;
use Source\Utility\Str;
use Source\DB\DBHandler;

class EntryPoint
{
    public static function start()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        if ($requestUri == "/") {
            self::home();
        } elseif (preg_match('#^/calendar#', $requestUri)) {
            self::calendar();
        } else {
            http_response_code(404);
            echo("404 page not found");
        }
    }

    public static function home()
    {
        $courseArray = [];
        $res = DBHandler::getCourses();
        foreach ($res as $row) {
            $courseArray[$row["Name"]] = $row;
        }

        ksort($courseArray);

        ob_start();
        include "views/home.php";
        $output = ob_get_clean();

        echo($output);
    }

    public static function calendar()
    {
        if (isset($_GET["courses"])) {
            if (is_array($_GET["courses"])) {
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
            foreach ($courses as $courseId) {
                $res = DBHandler::getCourseMoments($courseId);
                foreach ($res as $row) {
                    $str->addContent(Course::createICSFromDBRow($row));
                }
            }
            $str->addLine("END:VCALENDAR");

            echo($str->getString());
        }
    }

    public static function getCurrentEducationYear(int $time = null)
    {
        $time = $time ?? time();
        $startYear = date("y", strtotime("-8 months", $time));
        $endYear = date("y", strtotime("-8 months +1 year", $time));
        $educationYear =$startYear.$endYear;
        return $educationYear;
    }

    public static function updateDB()
    {
        $educationYear = self::getCurrentEducationYear();

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
}
