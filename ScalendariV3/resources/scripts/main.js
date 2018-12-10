function onSearch(input) {
	var filter = input.toUpperCase();
	var elements_container = document.getElementById('content');
	var all_elements = elements_container.getElementsByClassName('site');

	var search = searchEnabled;
	// Loop through all list items, and hide those who don't match the search query
	for (i = 0; i < all_elements.length; i++) {
		var current_element = all_elements[i];
		if (!search || (current_element.innerHTML.toUpperCase().indexOf(filter) > -1)) {
			current_element.style.display = "";
		} else {
			current_element.style.display = "none";
		}
	}
}