var searchEnabled = false;

function checkAndHighlight(current_element, filter) {
	var value = current_element.getAttribute("value");
	var start_match_index = value.toUpperCase().indexOf(filter);
	var title = current_element.getElementsByClassName("title")[0];
	console.log(title.tagName);
	var filterLength = filter.length;
	if (start_match_index > -1) {
		title.innerHTML = value.substring(0, start_match_index)
				+ "<span class=\"highlight\">"
				+ value.substring(start_match_index, start_match_index + filterLength) 
				+ "</span>"
				+ value.substring(start_match_index + filterLength);
		return true;
	} else {
		title.innerHTML = (value);
		return false;
	}
	return true;
}

function onSearch(inputFilter) {

	var filter = inputFilter.toUpperCase();
	var elements_container = document.getElementById('content');

	var filterLength = filter.length;
	var search = searchEnabled && filter.length > 1;

	var all_containers = elements_container.getElementsByClassName('container');
	var all_sites = elements_container.getElementsByClassName('site');
	var all_areas = elements_container.getElementsByClassName('area');

	// Loop through all list items, and hide those who don't match the search query
	if (search) {
		// at first hide all the area+sites containers
		for (i = 0; i < all_containers.length; i++) {
			all_containers[i].classList.add("hidden");
		}

		// check if the area name matches the query and if this is true unhide
		// also the container

		for (i = 0; i < all_sites.length; i++) {
			if (checkAndHighlight(all_sites[i], filter)) {
				all_sites[i].parentNode.classList.remove("hidden");
				all_sites[i].classList.remove("hidden");
			} else {
				all_sites[i].classList.add("hidden");
			}
		}

		// check if the site name matches the query and if this is true unhide the container
		for (i = 0; i < all_areas.length; i++) {
			if (checkAndHighlight(all_areas[i], filter)) {
				all_areas[i].parentNode.classList.remove("hidden");
			}
		}

		var all_highlighted = document.getElementsByClassName("highlight");
		if (all_highlighted.length > 0) {
			elements_container.scrollTop = all_highlighted[0].parentNode.parentNode.parentNode.offsetTop;
		}
	} else {
		// default state: all the containers are visible and all the sites are
		// hidden
		for (i = 0; i < all_containers.length; i++) {
			all_containers[i].classList.remove("hidden");
		}

		for (i = 0; i < all_sites.length; i++) {
			all_sites[i].classList.add("hidden");
		}

		for (i = 0; i < all_areas.length; i++) {
			checkAndHighlight(all_areas[i], -1); // using an "impossible"
			// query because we don't
			// want highlighting
		}
	}
}

function openArea(areaID, siteID) {
	location.href = "./areaView/?areaID=" + areaID + "&siteID=" + siteID;
}

function countAreaCalendars() {
	var elements_container = document.getElementById("content");
	var all_containers = elements_container.getElementsByClassName('container');
	for (i = 0; i < all_containers.length; i++) {
		var count = 0;
		var my_sites_count = all_containers[i]
				.querySelectorAll(".site:not(.done)");
		for (k = 0; k < my_sites_count.length; k++) {
			if (my_sites_count[k].getAttribute("calendars") == '?') {
				count = "?";
				break;
			} else {
				count += my_sites_count[k].getAttribute("calendars") * 1;
			}
		}
		var areaMissing = all_containers[i].querySelector(".area .missing h2");
		areaMissing.innerHTML = count;
		if (count == 0) {
			areaMissing.parentNode.parentNode.classList.add("done");
		} else {
			areaMissing.parentNode.parentNode.classList.remove("done");
		}
	}
}

var timeStamp = 0;
function getAllDone(onDoneChanged) {
	var xhttp = new XMLHttpRequest();
	xhttp.open("POST", "../actions/getDone.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("timestamp=" + timeStamp);
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			// Typical action to be performed when the document is ready:
			if (xhttp.responseText.length > 0) {
				var jobject = JSON.parse(xhttp.responseText);
				timeStamp = jobject.timeStamp;
				onDoneChanged(jobject.sites);
			}
		}
	};
}