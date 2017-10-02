<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CSSchedule</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
</head>
<body style="padding-top: 5rem">
    <nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">
        <div class="">
            <a class="navbar-brand" href="#">CSSchedule</a>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
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

            <div class="col-md-4">
                <h2>Export to ICS</h2>
                <div id="linkGenerator">https://csschedule.xyz/calendar/?</div>
                <button onclick="clipboard()">Copy!</button><br>
                <a href="https://csschedule.xyz/calendar?" download id="downloadICS">Download ICS File</a><br>
            </div>
        </div>
    </div>

    <script>
        function toggleGetAttribute(attributeName, attributeValue){
            var linkElement = document.getElementById("linkGenerator");
            var link = linkElement.innerHTML;
            if (link.includes(attributeName+"="+attributeValue)){
                linkElement.innerHTML = link.replace("&amp;"+attributeName+"="+attributeValue, "");
            } else {
                linkElement.innerHTML += "&amp;"+attributeName+"="+attributeValue;
            }

            document.getElementById("downloadICS").href= linkElement.innerHTML;
        }

        function clipboard(){
            var linkElement = document.getElementById("linkGenerator");
            window.getSelection().selectAllChildren(linkElement);
            document.execCommand('copy');
            window.getSelection().empty();
        }
    </script>
</body>
</html>



