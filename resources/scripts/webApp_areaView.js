function toggleSiteDone(siteID, siteName, element) {
	var done = element.classList.contains("done");
	customAlert("Via fatta", "segnare <strong>" + siteName
			+ "</strong> come " + (done?"non ":"") + "fatta?", [
			{
				text : "annulla",
				f : function() {
				}
			},
			{

				text : "ok",
				f : function() {
					var xhttp = new XMLHttpRequest();
					xhttp.open("POST", "../../actions/setSiteDone.php", true);
					xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xhttp.send("Done="+!done+"&siteID=" + siteID);
					xhttp.onreadystatechange = function() {
					    if (this.readyState == 4 && this.status == 200) {
					    	updateFunction(JSON.parse(xhttp.responseText).sites);
					    }
					};
				}
			} ]);

}

var timeStamp = 0;
function getAreaDone(areaID, onDoneChanged){
	var xhttp = new XMLHttpRequest();
	xhttp.open("POST", "../../actions/getDone.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("timestamp="+timeStamp+"&areaID=" + areaID);
	xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	       // Typical action to be performed when the document is ready:
	       if(xhttp.responseText.length > 0){
	    	   var jobject = JSON.parse(xhttp.responseText);
	    	   timeStamp = jobject.timeStamp;
	    	   onDoneChanged(jobject.sites);
	       }
	    }
	};
}


function countAreaCalendars() {
	var elements_container = document.getElementById("content");
	var my_sites_count = elements_container.querySelectorAll(".site:not(.done) .missing h2");
	var count = 0;
	for (k = 0; k < my_sites_count.length; k++) {
		if (my_sites_count[k].innerHTML == '?') {
			count = "?";
			break;
		} else {
			count += my_sites_count[k].innerHTML * 1;
		}
	}
	var areaMissing = document.querySelector(".area .missing h2");
	areaMissing.innerHTML = count;
	if(count == 0){
		areaMissing.parentNode.parentNode.parentNode.parentNode.classList.add("done");
	} else {
		areaMissing.parentNode.parentNode.parentNode.parentNode.classList.remove("done");
	}
}
