/* List of Students */
var studentList = [];

/*  Student Tracking Variables */
var studentCounter = 0;
var studentsSession = true;

/* List of Available Colours, so that no two colours are on the schedule at the same time */
var colorsOccupied = [false, false, false, false, false, false, false, false];

/* Remove button listeners for submission */
document.getElementById("addstudentmodalbtn").onclick = function() {
	return false;
}

document.getElementById("timeblockbtn").onclick = function() {
	return false;
}

document.getElementById("removestudentmodalbtn").onclick = function() {
	return false;
}

/* Counter of schedules */
var scheduleCounter = 0;

/* Ajax call to server for student objects */
$.ajax({                                      
  type: "POST",
  url: 'https://web.cs.dal.ca/~jean/proj/creatingLiveSession.php',  
  dataType: 'JSON', 
  success: function(response)
  {
    populateData(response);
  }
});

/* Populates our student list */
function populateData(response) {
	var currentStudentIndex = -1;

	/* Populate students */
	for(var i = 0; i < response.length; i++) {
		/* If student exists and isn't already in the list, add them */
		if (studentList.length == 0) {
				var newStudent = {};
				newStudent['name'] = response[i].name;
				newStudent['b00'] = response[i].B00;
				newStudent['scheduleBlockList'] = [];
				newStudent['blockCounter'] = 0;
				studentList.push(newStudent);
				currentStudentIndex = 0;
		}
		else {
			var studentExists = false;
			for (var k = 0; k < studentList.length; k++) {
				if (response[i].name === studentList[k].name) {
					studentExists = true;
					currentStudentIndex = k;
				}
			}
			if (!studentExists) {
				var newStudent = {};
				newStudent['name'] = response[i].name;
				newStudent['b00'] = response[i].B00;
				newStudent['scheduleBlockList'] = [];
				newStudent['blockCounter'] = 0;
				studentList.push(newStudent);
				currentStudentIndex = studentList.length - 1;
			}
		}

		/* Parse all the schedule blocks and populate each students' schedule block list */
		var days = response[i].days;

		if (days.charAt(0) == '1') {
			var block = {};

			block['starttime'] = parseInt(response[i].startTime);
			block['endtime'] = parseInt(response[i].endTime);
			block['name'] = response[i].course_name;
			block['id'] = response[i].course_code;
			block['location'] = response[i].location;
			block['day'] = "Monday";

			studentList[currentStudentIndex].scheduleBlockList.push(block);
		}

		if (days.charAt(1) === '1') {
			var block = {};

			block['starttime'] = parseInt(response[i].startTime);
			block['endtime'] = parseInt(response[i].endTime);
			block['name'] = response[i].course_name;
			block['id'] = response[i].course_code;
			block['location'] = response[i].location;
			block['day'] = "Tuesday";

			studentList[currentStudentIndex].scheduleBlockList.push(block);
		}

		if (days.charAt(2) === '1') {
			var block = {};

			block['starttime'] = parseInt(response[i].startTime);
			block['endtime'] = parseInt(response[i].endTime);
			block['name'] = response[i].course_name;
			block['id'] = response[i].course_code;
			block['location'] = response[i].location;
			block['day'] = "Wednesday";

			studentList[currentStudentIndex].scheduleBlockList.push(block);
		}

		if (days.charAt(3) === '1') {
			var block = {};

			block['starttime'] = parseInt(response[i].startTime);
			block['endtime'] = parseInt(response[i].endTime);
			block['name'] = response[i].course_name;
			block['id'] = response[i].course_code;
			block['location'] = response[i].location;
			block['day'] = "Thursday";

			studentList[currentStudentIndex].scheduleBlockList.push(block);
		}

		if (days.charAt(4) === '1') {
			var block = {};

			block['starttime'] = parseInt(response[i].startTime);
			block['endtime'] = parseInt(response[i].endTime);
			block['name'] = response[i].course_name;
			block['id'] = response[i].course_code;
			block['location'] = response[i].location;
			block['day'] = "Friday";

			studentList[currentStudentIndex].scheduleBlockList.push(block);
		}		
	}

	/* Add them to remove students modal so that they can be removed from list if desired */
	for (var i = 0; i < studentList.length; i++) {
		if (!studentsSession) {
			var el =  document.createElement("option");
			el.innerHTML = studentList[i].name;
			el.setAttribute("value", studentList[i].b00);
			document.getElementById("remove-students-options").appendChild(el);
		}
		studentsSession = false;
	}

	/* if no students, put in a dummy option */

	if (studentList.length < 2) {
		var el =  document.createElement("option");
		el.innerHTML = "No Student to Remove";
		el.setAttribute("value", "NONE");
		document.getElementById("remove-students-options").appendChild(el);
	}

	populateSchedule();
}

/* Populates the schedule view with all blocks */
function populateSchedule () {
	for (var i = 0; i < studentList.length; i++) {
		drawBlocks(studentList[i]);
	}
}

/* Validates forms found in add student modal */
function validateStudentModal () {
	var b00 = document.forms["addstudentform"]["BInput"].value;

	var b00regex = new RegExp("B00[0-9][0-9][0-9][0-9][0-9][0-9]");

	var result = b00regex.test(b00);

	/* If a valid B00, add the listener to the submit button, otherwise display error text */
    if (!result) {
    	document.getElementById("student-modal-fb").innerHTML="Not a valid B00 number.";
    	document.getElementById("addstudentmodalbtn").onclick = function() {
    		return false;
    	}
    	return;
    }
    else {
    	document.getElementById("addstudentmodalbtn").onclick = function() {
    		document.getElementById("addstudentform").submit();
    	}
    }

    document.getElementById("student-modal-fb").innerHTML="";
}

/* Validate the add time block modal form input */
function validateTimeBlock () {
	var blockname = document.forms["timeblockform"]["bltitle"].value;

	var startTimeStr = document.forms["timeblockform"]["StTime"].value;
	var startTime = parseInt(startTimeStr);

	var endTimeStr = document.forms["timeblockform"]["EndTime"].value;
	var endTime = parseInt(endTimeStr);

	/* If block name is less than 20 chars long and the times are valid (end time later than start time and less than 4:30 hour difference), put in a button listener */
	/* Otherwise display error feedback */
	if (blockname.length > 20) {
		document.getElementById("timeblockfb").innerHTML = "Time block title must be less than 20 characters.";
		return;
	}

	if (endTime <= startTime) {
		document.getElementById("timeblockfb").innerHTML = "End time must be later than start time.";
		return;
	}

	if ((endTime - startTime) > 400) {
		document.getElementById("timeblockfb").innerHTML = "Maximum time for a time block is 4 hours.";
		return
	}

	if ((startTime + (endTime - startTime)) > 2400) {
		document.getElementById("timeblockfb").innerHTML = "Cannot have a time block that extends pass midnight.";
		return
	}

	document.getElementById("timeblockfb").innerHTML = "";

	document.getElementById("timeblockbtn").onclick = function() {
    		document.getElementById("timeblockform").submit();
    }
}

/* Validate removal of student modal input */
function validateRemoval () {
	var b00 = document.forms["removestudentform"]["RemovableStudents"].value;

	/* If there isnt a proper student selected, show error, otherwise apply button listener to allow user to submit */
	if (b00 == "NONE" || b00 =="") {
		document.getElementById("removestudentsfb").innerHTML="A student must be selected.";
		document.getElementById("removestudentmodalbtn").onclick = function() {
			return false;
		}
		return;
	}
	else {
		document.getElementById("removestudentsfb").innerHTML="";
		document.getElementById("removestudentmodalbtn").onclick = function() {
			document.getElementById("removestudentform").submit();
		}
	}
}

/* Toggles the schedule of a student */
function toggleSchedule(name) {

	/* Get ID of Schedule Blocks and Initialize Objects */
	var clickedName = name.replace(/\n|<.*?>/g,'');
	var reducedName = clickedName.replace(/\s/g, '');
	var counter = 0;
	var displayingSched = false;
	var firstElement = true;

	/* If No More Colors, Too Many Schedules, Return w/ Error */
	if (scheduleCounter == 8) {
		alert("Cannot fit any more schedules!");
		return;
	}

	/* Pull a Random Number to Choose Colour */
	var randColor = Math.floor(Math.random() * 8);

	while (colorsOccupied[randColor]) {
		var randColor = Math.floor(Math.random() * 8);
	}
		
	/* Place Colour on All Schedule Blocks And Make Visible */
	while(document.getElementById(reducedName + counter) != null) {
		/* If were making it visible... */
		if (document.getElementById(reducedName + counter).style.visibility === 'hidden') {
			document.getElementById(reducedName + counter).style.visibility = "visible";

			var newEl = document.getElementById(reducedName + counter);

			displayingSched = true;

			switch(randColor) {
				case 0:
					newEl.classList.add('block-box-blue');
					if (firstElement) {
						colorsOccupied[0] = true;
						var el =  document.createElement("li");
						el.innerHTML = "<span style='color:rgb(25,118,210)'>&#9632 </span>" + clickedName;
						el.id = reducedName + 'listed';
						document.getElementById("current-student-list").appendChild(el);
						firstElement = false;
					}
					break;
				case 1:
					newEl.classList.add('block-box-pink');
					if (firstElement) {
						colorsOccupied[1] = true;
						var el =  document.createElement("li");
						el.innerHTML = "<span style='color:rgb(255,64,129)'>&#9632 </span>" + clickedName;
						el.id = reducedName + 'listed';
						document.getElementById("current-student-list").appendChild(el);
						firstElement = false;
					}
					break;
				case 2:
					newEl.classList.add('block-box-amber');
					if (firstElement) {
						colorsOccupied[2] = true;
						var el =  document.createElement("li");
						el.innerHTML = "<span style='color:rgb(255,160,0)'>&#9632 </span>" + clickedName;
						el.id = reducedName + 'listed';
						document.getElementById("current-student-list").appendChild(el);
						firstElement = false;
					}
					break;
				case 3:
					newEl.classList.add('block-box-black');
					if (firstElement) {
						colorsOccupied[3] = true;
						var el =  document.createElement("li");
						el.innerHTML = "<span style='color:rgb(33,33,33)'>&#9632 </span>" + clickedName;
						el.id = reducedName + 'listed';
						document.getElementById("current-student-list").appendChild(el);
						firstElement = false;
					}
					break;
				case 4:
					newEl.classList.add('block-box-purple');
					if (firstElement) {
						colorsOccupied[4] = true;
						var el =  document.createElement("li");
						el.innerHTML = "<span style='color:rgb(123,31,162)'>&#9632 </span>" + clickedName;
						el.id = reducedName + 'listed';
						document.getElementById("current-student-list").appendChild(el);
						firstElement = false;
					}
					break;
				case 5:
					newEl.classList.add('block-box-brown');
					if (firstElement) {
						colorsOccupied[5] = true;
						var el =  document.createElement("li");
						el.innerHTML = "<span style='color:rgb(121,85,72)'>&#9632 </span>" + clickedName;
						el.id = reducedName + 'listed';
						document.getElementById("current-student-list").appendChild(el);
						firstElement = false;
					}
					break;
				case 6:
					newEl.classList.add('block-box-red');
					if (firstElement) {
						colorsOccupied[6] = true;
						var el =  document.createElement("li");
						el.innerHTML = "<span style='color:rgb(211,47,47)'>&#9632 </span>" + clickedName;
						el.id = reducedName + 'listed';
						document.getElementById("current-student-list").appendChild(el);
						firstElement = false;
					}
					break;
				case 7:
					newEl.classList.add('block-box-green');
					if (firstElement) {
						colorsOccupied[7] = true;
						var el =  document.createElement("li");
						el.innerHTML = "<span style='color:rgb(76,175,80)'>&#9632 </span>" + clickedName;
						el.id = reducedName + 'listed';
						document.getElementById("current-student-list").appendChild(el);
						firstElement = false;
					}
					break;
				default:
					console.log("Error: Schedule cannot be displayed");
			}
		}
		/* Otherwise we're taking them down */
		else {
			var elementWithColor = document.getElementById(reducedName + counter);
			var currentColor = elementWithColor.classList.item(3);
			/* Make the color occupied */
			switch (currentColor) {
				case 'block-box-blue':
					colorsOccupied[0] = false;
					break;
				case 'block-box-pink':
					colorsOccupied[1] = false;
					break;
				case 'block-box-amber':
					colorsOccupied[2] = false;
					break;
				case 'block-box-black':
					colorsOccupied[3] = false;
					break;
				case 'block-box-purple':
					colorsOccupied[4] = false;
					break;
				case 'block-box-brown':
					colorsOccupied[5] = false;
					break;
				case 'block-box-red':
					colorsOccupied[6] = false;
					break;
				case 'block-box-green':
					colorsOccupied[7] = false;
					break;
			}

			/* Remove the color if theyre being removed */
			elementWithColor.classList.remove(currentColor);

			if (firstElement) {
				/* Remove From Listed Names over Schedule View*/
				var element = document.getElementById(reducedName + 'listed');
				element.parentNode.removeChild(element);
				firstElement = false;
			}

			/* Set visibility to hidden */
			document.getElementById(reducedName + counter).style.visibility = "hidden";
		}
		counter++;
	}

	/* Keep a Count of Schedule Counter */
	if (displayingSched) 
		scheduleCounter++;
	else
		scheduleCounter--;
}

/* Draw all schedule blocks from a student object */
function drawBlocks(student) {

	/* Add their name to the student list */
	var listEl =  document.createElement("li");
	listEl.innerHTML = "<input onclick='toggleSchedule(this.parentNode.innerHTML)' type='checkbox' name='student" + studentCounter + "-schedulecheck' value='STD" + studentCounter + "Show'>" + student.name + " " + student.b00;
	document.getElementById("students-ul").appendChild(listEl);
	studentCounter++;

	/* Iterate through schedule blocks and draw each one */
	for (var i = 0; i < student.scheduleBlockList.length; i++) {
		/* Make the div element and add a mouseover attribute to display info */
		var name = student.name.replace(/\s/g, '');
		var el =  document.createElement("div");
		
		var mouseoveratt = "Student: " + student.name + "\n" +
		                   "Class Name: " + student.scheduleBlockList[i].name + "\n" +
		                   "Class ID: " + student.scheduleBlockList[i].id + "\n" +
		                   "Location: " + student.scheduleBlockList[i].location;
		el.setAttribute("title", mouseoveratt);

		el.id = name + student.b00 + student.blockCounter;

		/* Append the new div element */
		document.getElementById("schedule-container").appendChild(el);

		var newEl = document.getElementById(name + student.b00 + student.blockCounter);
		student.blockCounter++;

		/* Add the positioning CSS class based on day */
		switch(student.scheduleBlockList[i].day) {
			case "Monday":
				newEl.classList.add('monday');
				break;
			case "Tuesday":
				newEl.classList.add('tuesday');
				break;
			case "Wednesday":
				newEl.classList.add('wednesday');
				break;
			case "Thursday":
				newEl.classList.add('thursday');
				break;
			case "Friday":
				newEl.classList.add('friday');
				break;
			default:
				console.log("Error, unable to properly draw block");
		}

		/* Add the positioning CSS class based on time */
		switch(student.scheduleBlockList[i].starttime) {
			case 800:
				newEl.classList.add('eight');
				break;
			case 830:
				newEl.classList.add('eightthirty');
				break;
			case 900:
				newEl.classList.add('nine');
				break;
			case 930:
				newEl.classList.add('ninethirty');
				break;
			case 1000:
				newEl.classList.add('ten');
				break;
			case 1030:
				newEl.classList.add('tenthirty');
				break;
			case 1100:
				newEl.classList.add('eleven');
				break;
			case 1130:
				newEl.classList.add('eleventhirty');
				break;
			case 1200:
				newEl.classList.add('twelve');
				break;
			case 1230:
				newEl.classList.add('twelvethirty');
				break;
			case 1300:
				newEl.classList.add('thirteen');
				break;
			case 1330:
				newEl.classList.add('thirteenthirty');
				break;
			case 1400:
				newEl.classList.add('fourteen');
				break;
			case 1430:
				newEl.classList.add('fourteenthirty');
				break;
			case 1500:
				newEl.classList.add('fifteen');
				break;
			case 1530:
				newEl.classList.add('fifteenthirty');
				break;
			case 1600:
				newEl.classList.add('sixteen');
				break;
			case 1630:
				newEl.classList.add('sixteenthirty');
				break;
			case 1700:
				newEl.classList.add('seventeen');
				break;
			case 1730:
				newEl.classList.add('seventeenthirty');
				break;
			case 1800:
				newEl.classList.add('eightteen');
				break;
			case 1830:
				newEl.classList.add('eightteenthirty');
				break;
			case 1900:
				newEl.classList.add('nineteen');
				break;
			case 1930:
				newEl.classList.add('nineteenthirty');
				break;
			case 2000:
				newEl.classList.add('twenty');
				break;
			case 2030:
				newEl.classList.add('twentythirty');
				break;
			case 2100:
				newEl.classList.add('twentyone');
				break;
			case 2130:
				newEl.classList.add('twentyonethirty');
				break;
			case 2200:
				newEl.classList.add('twentytwo');
				break;
			case 2230:
				newEl.classList.add('twentytwothirty');
				break;
			case 2300:
				newEl.classList.add('twentythree');
				break;
			case 2330:
				newEl.classList.add('twentythreethirty');
				break;
			default:
				console.log("Error, unable to properly draw block.");

		}

		/* Size element based on time length of block, with no block being over 4 hours long */
		var starttimeconvert, endtimeconvert;

		if (student.scheduleBlockList[i].starttime%100 != 0) 
			starttimeconvert = student.scheduleBlockList[i].starttime + 20;
		else 
			starttimeconvert = student.scheduleBlockList[i].starttime

		if (student.scheduleBlockList[i].endtime%100 != 0) 
			endtimeconvert = student.scheduleBlockList[i].endtime + 20;
		else 
			endtimeconvert = student.scheduleBlockList[i].endtime;


		var timeframe = endtimeconvert - starttimeconvert;

		switch(timeframe) {
			case 50:
				newEl.classList.add('block-half-hour');
				break;
			case 100:
				newEl.classList.add('block-hour');
				break;
			case 150:
				newEl.classList.add('block-hour-half');
				break;
			case 200:
				newEl.classList.add('block-two-hours');
				break;
			case 250:
				newEl.classList.add('block-two-half');
				break;
			case 300:
				newEl.classList.add('block-three-hours');
				break;
			case 350:
				newEl.classList.add('block-three-half');
				break;
			case 400:
				newEl.classList.add('block-four-hours');
				break;
			default:
				console.log("Error, unable to properly draw block.");
		}

		/* Hide the block initially*/
		newEl.style.visibility = "hidden";
	}
}

/* Modal Diagram Funtionality, just adding listeners to the buttons to open them */

var modal=document.getElementById("addStudentModal");
var btn= document.getElementById("addStudentBtn");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function(){
	modal.style.display="block";
}
span.onclick= function(){
	modal.style.display="none";
}

window.onclick=function(event)
{
	if(event.target==modal){
		modal.style.display="none";
	}
}

var modal2=document.getElementById("addBlockModal");
var btn2= document.getElementById("addBlockBtn");
var span2 = document.getElementsByClassName("close")[1];

btn2.onclick = function(){
	modal2.style.display="block";
}
span2.onclick= function(){
	modal2.style.display="none";
}

window.onclick=function(event)
{
	if(event.target==modal){
		modal2.style.display="none";
	}
}

var modal3=document.getElementById("removeStudentModal");
var btn3= document.getElementById("removebtn");
var span3 = document.getElementsByClassName("close")[2];

btn3.onclick = function(){
	modal3.style.display="block";
}
span3.onclick= function(){
	modal3.style.display="none";
}

window.onclick=function(event)
{
	if(event.target==modal){
		modal3.style.display="none";
	}
}
