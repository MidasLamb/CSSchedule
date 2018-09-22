<?php

namespace Source\DB;

class DBHandler{
    public static function updateDatabaseCourseMoments($groupedCourses){
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM CourseMoments");
        $stmt->execute();

        foreach($groupedCourses as $courseId => $courseGroup){
            foreach($courseGroup as $course){
                $stmt = $db->prepare("INSERT INTO CourseMoments VALUES (:CourseId, :DTStamp, :DTStart, :DTEnd, :Location, :Name)");
                $stmt->bindValue(':CourseId', $courseId);
                $stmt->bindValue(':DTStamp', $course->constructStartTimeStamp());
                $stmt->bindValue(':DTStart', $course->constructStartTimeStamp());
                $stmt->bindValue(':DTEnd', $course->constructEndTimeStamp());
                $stmt->bindValue(':Location', $course->placeString);
                $stmt->bindValue(':Name', $course->nameString);
                $stmt->execute();
            }
        }
    }

    public static function updateDatabaseCourses($courses){
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM Courses");
        $stmt->execute();

        foreach($courses as $courseId => $courseName){
            $stmt = $db->prepare("INSERT INTO Courses VALUES (:CourseId, :CourseName)");
            $stmt->bindValue(':CourseId', $courseId);
            $stmt->bindValue(':CourseName', $courseName);
            $stmt->execute();
        }
    }

    public static function getCourseMoments($courseId){
        $db = self::getDB();
        $stmt = $db->prepare('SELECT * FROM CourseMoments WHERE CourseId = :courseId');
        $stmt->execute([
            ':courseId' => $courseId
        ]);
        return $stmt->fetchAll();
    }

    public static function getCourses(){
        $db = self::getDB();
        $res = $db->query("SELECT * FROM Courses");
        return $res->fetchAll();
    }

    public static function getDB(){
        return new \PDO('sqlite:'.__DIR__.'/../../database/db.sqlite3');
    }
}