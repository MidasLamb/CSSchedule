<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CSSchedule</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/pretty.css">
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
                        <textarea id="linkGenerator" class="form-control" type="text" placeholder="https://csschedule.xyz/calendar/?courses=" readonly></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-outline-primary" onclick="clipboard()">Copy</button>
                    <a class="btn btn-primary" href="https://csschedule.xyz/calendar?" download id="downloadICS">Download ICS File</a><br>
                </div>
            </div>
            <div class="col-md-8 order-md-1">
                <h2>Select courses</h2>
                <div class="form-group">
                    <input id="searchText" class="form-control" type="text" placeholder="Search" oninput="filter(this.value)">
                </div>
                <table class="table table-sm" style="width:100%">
                    <thead>
                        <tr>
                            <th style="font-size:1.3em;">Coursename</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($courseArray as $course){
                        ?>
                            <tr style="border:none;width:100%;">
                                <td style="border:none;width:100%;">
                                    <div class="btn-group coursename-btn" data-toggle="buttons">
                                        <label class="btn btn-outline-dark btn-block" onclick="toggleCourseCSV('<?php echo($course["Id"]) ?>')" value="<?php echo($course["Id"]) ?>">
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
                if (link.slice(-1) === "="){
                    newLink = link + course;
                } else {
                    newLink = link + "," + course;
                }               
            }

            linkElement.placeholder = newLink;

            document.getElementById("downloadICS").href= linkElement.placeholder;

            window.setTimeout(function(){
                $('#searchText').get(0).focus();
            }, 100);

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

        function filter(searchText){
            searchText = searchText.toLowerCase();
            var group;
            if (searchText.length > lastSearchString.length){
                group = $("table tbody tr:visible");
            } else {
                group = $("table tbody tr");
            }
            group.each(function(){
                if ($(this).find(".courseName").text().toLowerCase().includes(searchText)){
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            lastSearchString = searchText;
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
</body>
</html>
