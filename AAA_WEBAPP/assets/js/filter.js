const filters = document.getElementsByClassName("filter-input");

for(const f of filters) {

	f.addEventListener("input", () => {

		Api.showTracks(Collection.filter(f.dataset.search, f.dataset.equality, f.value));

	});

}