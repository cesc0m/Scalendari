function commonAlertContainer(titleText, buttons) {

	var alertBG = document.getElementById("alertBG");
	if (alertBG == null) {
		alertBG = document.createElement("div");
		alertBG.setAttribute('id', 'alertBG');
		document.body.appendChild(alertBG);
	}

	var container = document.createElement("div");
	container.setAttribute('class', 'alertContainer');

	var close = document.createElement("span");
	close.setAttribute('class', 'closeBTN');
	close.onclick = function() {
		closeAlert(container)
	};
	close.innerHTML = "&#10006;";
	container.appendChild(close);

	var title = document.createElement("h1");
	title.setAttribute('class', 'alertTitle');
	title.appendChild(document.createTextNode(titleText));
	container.appendChild(title);

	var content = document.createElement("div");
	content.setAttribute('class', 'alertContent');
	container.appendChild(content);

	var footer = document.createElement("div");
	footer.setAttribute('class', 'alertFooter');

	for (i = 0; i < buttons.length; i++) {
		var button = document.createElement("button");
		button.setAttribute('class', 'alertButton');
		if(buttons[i].bgcolor) {
			button.setAttribute('style', 'background-color: '+buttons[i].bgcolor+';');
		}
		button.innerHTML = buttons[i].text;
		button.addEventListener('click', buttons[i].f);
		button.addEventListener('click', function() {
			closeAlert(container)
		});
		footer.appendChild(button);
	}

	container.appendChild(footer);

	document.body.appendChild(container);
	container.focus();
	return content;
}

function closeAlert(alertContainer) {
	document.body.removeChild(alertContainer);
	if (document.getElementsByClassName("alertContainer").length == 0) {
		document.body.removeChild(document.getElementById("alertBG"));
	}
}

function customAlert(title, text, buttonsTextAndBehaviour) {
	var container = commonAlertContainer(title, [ 
		{
			text : buttonsTextAndBehaviour[0].text,
			f : buttonsTextAndBehaviour[0].f
		}, 
		{
			text : buttonsTextAndBehaviour[1].text,
			bgcolor: "red",
			f : buttonsTextAndBehaviour[1].f
		}
	]);
	var content = document.createElement("p");
	content.setAttribute('class', 'alertText');
	content.innerHTML = text;
	container.appendChild(content);
}