<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CSSchedule</title>
</head>
<body>

    <div id="linkGenerator">https://csschedule.xyz/calendar/?</div>
    <button onclick="clipboard()">Copy!</button><br>
    <a href="https://csschedule.xyz/calendar?" id="downloadICS">Download ICS File</a><br>

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



    <script>
        function toggleGetAttribute(attributeName, attributeValue){
            var linkElement = document.getElementById("linkGenerator");
            var link = linkElement.innerHTML;
            if (link.includes(attributeName+"="+attributeValue)){
                linkElement.innerHTML = link.replace("&amp;"+attributeName+"="+attributeValue, "");
            } else {
                linkElement.innerHTML += "&amp;"+attributeName+"="+attributeValue;
            }

            document.getElementById("downloadICS").href= linkElement.innerHTML.replace(/&amp;/g, '&');;
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



