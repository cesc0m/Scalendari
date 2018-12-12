function enableSearch() {
	searchEnabled = true;
	document.getElementById('searchbar_container').classList.add("searching");
	search();
}

function disableSearch() {
	searchEnabled = false;
	document.getElementById('searchbar_container').classList
			.remove("searching");
	search();
}

function search(){
	var input = document.getElementById('searchbar');
	onSearch(input.value);
}