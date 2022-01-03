"use strict";

async function display_order(order_id) {
	let res = document.createElement("div");
	fetch("/status_api.php?order_id=" + order_id).then(response => response.json()).then(data => {
		for (let x in data) {
			if (Array.isArray(data[x])) {
				for (let y in data[x]) {
					let p = document.createElement("p");
					let t = document.createTextNode(x + ": " + data[x][y]);
					p.appendChild(t);
					res.appendChild(p);
				}
			} else {
				let p = document.createElement("p");
				let t = document.createTextNode(x + ": " + data[x]);
				p.appendChild(t);
				res.appendChild(p);
			}
		}
	});
	return res;
}

async function update_item_quantity(product, quantity) {
	return await fetch("/edit-cart.php?product_id=" + product + "&quantity=" + quantity).then(x => x.json());
}


async function update_item_quantity_btn(button) {
	let cart = await update_item_quantity(button.dataset["product"], button.dataset["increment"]);
	/*for (const item of cart.items) {
		if(item.id == button.dataset["product"]) {
			document.getElementById("")
			break;
		}
	}*/
}
