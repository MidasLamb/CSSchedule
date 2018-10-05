<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CSSchedule</title>
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/pretty.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body style="padding-top:5rem">
    <nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">
        <div class="">
            <a class="navbar-brand" href="#">
                <img src="images/logo.svg" height="30" class="d-inline-block align-top">
                CSSchedule
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-4 order-md-2">
                <h2>Export to ICS</h2>
                <p style="color:red" id="warning"></p>
                <div class="form-group">
                    <div id="textSelector">
                        <textarea id="linkGenerator" class="form-control" type="text" placeholder="" readonly></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-outline-primary" onclick="clipboard()">Copy</button>
                    <a class="btn btn-primary" href="https://csschedule.xyz/calendar?" download id="downloadICS">Download ICS File</a><br>
                </div>

                <h2>About CSSchedule</h2>
                <div>
                    CSSchedule is a program made by students, for students. It just parses the CS Department provided pages to get the place and moments for the courses. It is completely Open Source and can be found on Github (see below).
                </div>

                <h3 style="margin-top:5px;">Helping out</h3>
                <div>
                    The Source can be found on <b><a href="https://github.com/MidasLamb/CSSchedule">GitHub</a></b>. How to contribute is explained there. If there is something wrong, there is a bug, or you want a certain feature, you can just create an <b><a href="https://github.com/MidasLamb/CSSchedule/issues">issue</a></b> there.
                </div>

            </div>
            <div class="col-md-8 order-md-1">
                <h2>Select courses</h2>
                <div class="input-group">
                    <input id="searchText" class="form-control" type="text" placeholder="Search" oninput="filter()">
                    <div class="input-group-append btn-group-toggle" data-toggle="buttons">
                        <label id="showSelectedSwitch" class="btn btn-outline-dark btn-block" onclick="showSelectedToggle()">
                            <input type="checkbox"> 
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </label>
                    </div>
                </div>
                <table class="table table-sm" style="width:100%">
                    <tbody>
                        <?php
                            foreach ($courseArray as $course) {
                                ?>
                            <tr style="border:none;width:100%;">
                                <td style="border:none;width:100%;">
                                    <div class="btn-group-toggle coursename-btn" data-toggle="buttons">
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

            var courseCSV = newLink.split("?")[1].split("=")[1];
            if (typeof(localStorage) !== "undefined") {
	    		localStorage.setItem("courses", courseCSV);
	    	} else {
	    		document.getElementById("warning").innerHTML = "Because your browser doesn't support local storage, the selected subjects will not be saved."
	    	}
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
            if (showSelected){
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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

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
        	if (typeof(localStorage) !== "undefined") {
		        var courses = localStorage.getItem("courses");
		        if (courses){
		            var courses = courses.split(",");
		            courses.forEach(function(course, index){
		                toggleCourseCSV(course);
		                $("#"+course).addClass("active");
		            });
		        }
		    } else {
		    	document.getElementById("warning").innerHTML = = "Because your browser doesn't support local storage, the selected subjects will not be saved."
		    }
        });

    </script>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>
    <script>
	    window.addEventListener("load", function(){
		    window.cookieconsent.initialise({
			  "palette": {
			    "popup": {
			      "background": "#343a40"
			    },
			    "button": {
			      "background": "#007bff"
			    }
			  },
			  "showLink": false,
			  "content": {
			    "message": "This website uses cookies to personalise content."
			  }
		})});
    </script>
</body>
</html>
