<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CSSchedule</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
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
                    <input id="linkGenerator" class="form-control" type="text" placeholder="https://csschedule.xyz/calendar/?" readonly>
                    <div id="linkSelector" style="display:none"></div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-outline-primary" onclick="clipboard()">Copy</button>
                    <a class="btn btn-primary" href="https://csschedule.xyz/calendar?" download id="downloadICS">Download ICS File</a><br>
                </div>
            </div>
            <div class="col-md-8 order-md-1">
                <h2>Select courses</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Coursename</th>
                            <th>Add?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($courseArray as $course){
                        ?>
                            <tr>
                                <td>
                                    <?php echo($course["Name"]) ?>
                                </td>
                                <td>
                                    <input type="checkbox" name="courses[]" onclick="toggleGetAttribute('courses[]','<?php echo($course["Id"]) ?>')" value="<?php echo($course["Id"]) ?>">
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
        function toggleGetAttribute(attributeName, attributeValue){
            var linkElement = document.getElementById("linkGenerator");
            var link = linkElement.placeholder;
            if (link.includes(attributeName+"="+attributeValue)){
                linkElement.placeholder = link.replace("&amp;"+attributeName+"="+attributeValue, "");
            } else {
                linkElement.placeholder += "&amp;"+attributeName+"="+attributeValue;
            }


            document.getElementById("linkSelector").innerHTML = linkElement.placeholder;
            document.getElementById("downloadICS").href= document.getElementById("linkSelector").innerText;

        }

        function clipboard(){
            var linkElement = document.getElementById("linkSelector");
            window.getSelection().selectAllChildren(linkElement);
            document.execCommand('copy');
            window.getSelection().empty();
        }
    </script>
</body>
</html>
