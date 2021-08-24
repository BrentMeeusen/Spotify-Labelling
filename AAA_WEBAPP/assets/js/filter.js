const filters = document.getElementsByClassName("filter-input");

for(const f of filters) {
	f.addEventListener("input", () => {
		Api.show.tracks(Collection.addFilter(f.dataset.search, f.value));
	});
}