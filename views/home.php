<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CSSchedule</title>
</head>
<body>

    <div id="linkGenerator">http://uurrooster/calendar/?</div>
    <a href="http://uurrooster/calendar?" download id="downloadICS">Download ICS File</a><br>
    <?php
        $i = 0;
        foreach($courseArray as $course){
    ?>
        <input type="checkbox" id="<?php echo($i) ?>" name="courses[]" onclick="toggleGetAttribute('courses[]','<?php echo($course["Id"]) ?>')" value="<?php echo($course["Id"]) ?>">
        <label for="<?php echo($i++) ?>"><?php echo($course["Name"]) ?></label><br>
    <?php
        }
    ?>



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
    </script>
</body>
</html>



