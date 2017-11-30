<?php
	include(dbConnect.php);
?>
/* List of Students */
var studentList = [];
var studentCounter = 0;

/* List of Available Colours, so that no two colours are on the schedule at the same time */
var colorsOccupied = [false, false, false, false, false, false, false, false];

/* Counter of schedules */
var scheduleCounter = 0;

/* Test Students */
var testStudent1 = {
	name: 'Matthew Duggan',
	blockCounter: 0,
	scheduleBlockList: [],
	b00: 'B00617196'
};

var testStudent2 = {
	name: 'Hawley Jean',
	blockCounter: 0,
	scheduleBlockList: [],
	b00: 'B00626007'
}

/* On load */
window.onload = populateSchedule;

function populateSchedule () {
	populateScheduleBlocks(testStudent1.scheduleBlockList);
	studentList.push(testStudent1);
	drawBlocks(testStudent1);

	populateScheduleBlocks2(testStudent2.scheduleBlockList);
	studentList.push(testStudent2);
	drawBlocks(testStudent2);
}

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
		else {
			var elementWithColor = document.getElementById(reducedName + counter);
			var currentColor = elementWithColor.classList.item(3);

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

			elementWithColor.classList.remove(currentColor);

			if (firstElement) {
				/* Remove From Listed Names over Schedule View*/
				var element = document.getElementById(reducedName + 'listed');
				element.parentNode.removeChild(element);
				firstElement = false;
			}

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

	for (var i = 0; i < student.scheduleBlockList.length; i++) {
		var name = student.name.replace(/\s/g, '');
		var el =  document.createElement("div");
		
		var mouseoveratt = "Student: " + student.name + "\n" +
		                   "Class Name: " + student.scheduleBlockList[i].name + "\n" +
		                   "Class ID: " + student.scheduleBlockList[i].id + "\n" +
		                   "Location: " + student.scheduleBlockList[i].location;
		el.setAttribute("title", mouseoveratt);

		el.id = name + student.b00 + student.blockCounter;

		document.getElementById("schedule-container").appendChild(el);

		var newEl = document.getElementById(name + student.b00 + student.blockCounter);
		student.blockCounter++;

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

		newEl.style.visibility = "hidden";
	}
}

/* Populate Test Student */
function populateScheduleBlocks(scheduleBlockList) {
	var block = {};

	block['starttime'] = 830;
	block['endtime'] = 1130;
	block['name'] = "UI Design";
	block['id'] = "CSCI3169";
	block['location'] = "Killam";
	block['day'] = "Monday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1600;
	block['endtime'] = 1730;
	block['name'] = "Web Centric Computing";
	block['id'] = "CSCI3172";
	block['location'] = "Mona Campbell";
	block['day'] = "Monday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1000;
	block['endtime'] = 1130;
	block['name'] = "Networks";
	block['id'] = "CSCI4171";
	block['location'] = "Goldberg";
	block['day'] = "Tuesday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1430;
	block['endtime'] = 1600;
	block['name'] = "Economic Rise of China and India";
	block['id'] = "CHIN2290";
	block['location'] = "LSC";
	block['day'] = "Tuesday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1600;
	block['endtime'] = 1730;
	block['name'] = "Mobile Computing";
	block['id'] = "CSCI4176";
	block['location'] = "LSC";
	block['day'] = "Tuesday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 830;
	block['endtime'] = 1130;
	block['name'] = "Mobile Computing";
	block['id'] = "CSCI4176";
	block['location'] = "Goldberg";
	block['day'] = "Wednesday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1600;
	block['endtime'] = 1730;
	block['name'] = "UI Design";
	block['id'] = "CSCI3160";
	block['location'] = "LSC";
	block['day'] = "Wednesday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1000;
	block['endtime'] = 1130;
	block['name'] = "Networks";
	block['id'] = "CSCI4171";
	block['location'] = "Goldberg";
	block['day'] = "Thursday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1430;
	block['endtime'] = 1600;
	block['name'] = "Economic Rise of China and India";
	block['id'] = "CHIN2290";
	block['location'] = "LSC";
	block['day'] = "Thursday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1600;
	block['endtime'] = 1730;
	block['name'] = "Mobile Computing";
	block['id'] = "CSCI4176";
	block['location'] = "LSC";
	block['day'] = "Thursday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1030;
	block['endtime'] = 1130;
	block['name'] = "Web Centric Computing";
	block['id'] = "CSCI3172";
	block['location'] = "Goldberg";
	block['day'] = "Friday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1600;
	block['endtime'] = 1730;
	block['name'] = "Web Centric Computing";
	block['id'] = "CSCI3172";
	block['location'] = "Rowe";
	block['day'] = "Friday";

	scheduleBlockList.push(block);
}

/* Populate Test Student */
function populateScheduleBlocks2(scheduleBlockList) {
	var block = {};

	block['starttime'] = 1030;
	block['endtime'] = 1130;
	block['day'] = "Monday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1130;
	block['endtime'] = 1230;
	block['day'] = "Monday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1330;
	block['endtime'] = 1430;
	block['day'] = "Monday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1730;
	block['endtime'] = 1830;
	block['day'] = "Monday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1130;
	block['endtime'] = 1230;
	block['day'] = "Tuesday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1030;
	block['endtime'] = 1130;
	block['day'] = "Wednesday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1130;
	block['endtime'] = 1230;
	block['day'] = "Wednesday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1330;
	block['endtime'] = 1430;
	block['day'] = "Wednesday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1730;
	block['endtime'] = 1830;
	block['day'] = "Wednesday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1030;
	block['endtime'] = 1130;
	block['day'] = "Thursday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1130;
	block['endtime'] = 1230;
	block['day'] = "Thursday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1730;
	block['endtime'] = 2030;
	block['day'] = "Thursday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1030;
	block['endtime'] = 1130;
	block['day'] = "Friday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1130;
	block['endtime'] = 1230;
	block['day'] = "Friday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1330;
	block['endtime'] = 1430;
	block['day'] = "Friday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1600;
	block['endtime'] = 1730;
	block['day'] = "Friday";

	scheduleBlockList.push(block);

	block = {};

	block['starttime'] = 1730;
	block['endtime'] = 1830;
	block['day'] = "Friday";

	scheduleBlockList.push(block);
}