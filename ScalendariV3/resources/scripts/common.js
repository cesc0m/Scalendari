function toggleExpanded(element) {
	if (!element.classList.contains("expanded")) {
		element.classList.add("expanded");
	} else {
		element.classList.remove("expanded");
	}
}

function openMap(x, y) {
	var form = document.createElement("form");
	form.setAttribute('class', 'mapSelection');
	var available = [ "default", "confini" ];
	for (i = 0; i < available.length; i++) {
		var label = document.createElement("label");
		var radio = document.createElement("input");
		radio.setAttribute('type', 'radio');
		radio.setAttribute('name', 'maptype');
		radio.setAttribute('value', available[i]);
		label.appendChild(radio);
		label.appendChild(document.createTextNode(available[i]));
		form.appendChild(label);
		form.appendChild(document.createElement("br"));
	}
	form.firstChild.firstChild.setAttribute('checked', 'checked');

	var alertBox = commonAlertContainer(
			"Select map",
			[
					{
						text : "annulla",
						f : function() {
						}
					},
					{
						text : "ok",
						bgcolor : "red",
						f : function() {
							if (form.elements['maptype'].value == "default") {
								if (x !== undefined && y !== undefined) {
									window
											.open('https://www.google.com/maps/search/?api=1&query='
													+ x + "," + y + "");
								}
							} else {
								var url = window.location.href;
								url = url.substring(0, url.indexOf("public/webApp/"))+"public/webApp/mapView.php?X=" + x + "&Y=" + y+ "";
								window.open(url);
							}
						}
					}]);
	alertBox.appendChild(form);
}