<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CSSchedule</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/pretty.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body style="padding-top:5rem">
    <nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">
        <div class="">
            <a class="navbar-brand" href="#">CSSchedule</a>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-4 order-md-2">
                <h2>Export to ICS</h2>
                <div class="form-group">
                    <div id="textSelector">
                        <textarea id="linkGenerator" class="form-control" type="text" placeholder="" readonly></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-outline-primary" onclick="clipboard()">Copy</button>
                    <a class="btn btn-primary" href="https://csschedule.xyz/calendar?" download id="downloadICS">Download ICS File</a><br>
                </div>
            </div>
            <div class="col-md-8 order-md-1">
                <h2>Select courses</h2>
                <div class="input-group">
                    <input id="searchText" class="form-control" type="text" placeholder="Search" oninput="filter()">
                    <span class="input-group-btn">
                        <button class="btn btn-outline-dark"><i class="fa fa-sort-alpha-asc" aria-hidden="true"></i></button>
                        <div class="btn-group coursename-btn" data-toggle="buttons">
                            <label id="showSelectedSwitch" class="btn btn-outline-dark btn-block" onclick="showSelectedToggle()">
                                <input type="checkbox"> 
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </label>
                        </div> 
                    </span>
                </div>
                <table class="table table-sm" style="width:100%">
                    <tbody>
                        <?php
                            foreach ($courseArray as $course) {
                                ?>
                            <tr style="border:none;width:100%;">
                                <td style="border:none;width:100%;">
                                    <div class="btn-group coursename-btn" data-toggle="buttons">
                                        <label class="btn btn-outline-dark btn-block" onclick="toggleCourseCSV('<?php echo($course["Id"]) ?>')" id="<?php echo($course["Id"]) ?>">
                                            <input type="checkbox" name="courses[]"> 
                                            <div class="courseName"><?php echo($course["Name"]) ?></div>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function setHostName(){
            var protocol = location.protocol.concat("//");
            var host = window.location.hostname;
            document.getElementById('linkGenerator').placeholder = protocol + host + '/calendar/?courses=';
            document.getElementById("downloadICS").href = protocol + host + '/calendar/?courses=';
        }

        setHostName();

        function toggleCourseCSV(course){
            var linkElement = document.getElementById("linkGenerator");
            var link = linkElement.placeholder;

            var newLink;

            if (link.includes(course)){
                if (link.includes(","+course)){
                    newLink = link.replace(","+course, "");
                } else {
                    newLink = link.replace(course, "");
                }
            } else {
		newLink = link + "," + course;
            }
	    newLink = newLink.replace("=,","=");
            linkElement.placeholder = newLink;
            document.getElementById("downloadICS").href= linkElement.placeholder;

            var courseCSV = newLink.split("?")[1];
            document.cookie = courseCSV + ";expires=Thu, 1 Aug 2019 12:00:00 UTC";

            if ($("#searchText").isOnScreen()){
                window.setTimeout(function(){
                    $('#searchText').get(0).focus();
                }, 100);
            }
        }

        function toggleGetAttribute(attributeName, attributeValue){
            var linkElement = document.getElementById("linkGenerator");
            var link = linkElement.placeholder;
            if (link.includes(attributeName+"="+attributeValue)){
                linkElement.placeholder = link.replace("&"+attributeName+"="+attributeValue, "");
            } else {
                linkElement.placeholder += "&"+attributeName+"="+attributeValue;
            }            
        }

        function clipboard(){
            var linkElement = document.getElementById("textSelector");
            window.getSelection().selectAllChildren(linkElement);
            document.execCommand('copy');
        }

        var lastSearchString = "";

        function filter(){
            var searchText = $("#searchText").val().toLowerCase();
            var group;
            if (searchText.length > lastSearchString.length){
                group = $("table tbody tr");
            } else {
                group = $("table tbody tr");
            }
            group.each(function(){
                if ($(this).find(".courseName").text().toLowerCase().includes(searchText)){
                    $(this).removeClass("hiddenBySearch");
                } else {
                    $(this).addClass("hiddenBySearch");
                }
            });
            lastSearchString = searchText;
        }

        var showSelected = false;

        function showSelectedToggle(){
            showSelected = !showSelected;
            showSelectedCourses();
        }

        function showSelectedCourses(){
            if ($("#showSelectedSwitch").hasClass("active")){
                $("table tbody tr").each(function(){
                    if ($(this).html().includes("active")){
                        $(this).removeClass("hiddenByShowSelectedCourses");
                    } else {
                        $(this).addClass("hiddenByShowSelectedCourses");
                    }
                });
            } else {
                $("table tbody tr").each(function(){
                    $(this).removeClass("hiddenByShowSelectedCourses");
                });
            }
        }

    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

    <script>
        $.fn.isOnScreen = function(){
	
            var win = $(window);
            
            var viewport = {
                top : win.scrollTop(),
                left : win.scrollLeft()
            };
            viewport.right = viewport.left + win.width();
            viewport.bottom = viewport.top + win.height();
            
            var bounds = this.offset();
            bounds.right = bounds.left + this.outerWidth();
            bounds.bottom = bounds.top + this.outerHeight();
            
            return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
            
        };

        $(document).ready(function(){
            var cookies = document.cookie;
            if (cookies){
                var courses = cookies.split("=")[1].split(",");
                courses.forEach(function(course, index){
                    toggleCourseCSV(course);
                    $("#"+course).addClass("active");
                });
            }
        });

    </script>
</body>
</html>
