const filters = document.getElementsByClassName("filter-input");

for(const f of filters) {
	f.addEventListener("input", () => {
		Api.show.tracks(Collection.filter(f.dataset.search, f.value));
	});
}