"use strict";

async function display_order(order_id) {
	let res = createElement("div");
	fetch("/status_api.php?order_id=" + order_id).then(response => response.json()).then(data => {
		for (let x in data) {
			if (Array.isArray(data[x])) {
				for (let y in data[x]) {
					let p = createElement("p");
					let t = createTextNode(x + ": " + data[x][y]);
					p.appendChild(t);
					res.appendChild(p);
				}
			} else {
				let p = createElement("p");
				let t = createTextNode(x + ": " + data[x]);
				p.appendChild(t);
				res.appendChild(p);
			}
		}
	});
	return res;
}
