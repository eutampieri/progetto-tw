"use strict";

async function display_order(order_id) {
	let res = document.createElement("div");
	fetch("/status_api.php?order_id=" + order_id).then(response => response.json()).then(data => {
		let productList = document.createElement("section");
		let productListHeading = document.createElement("h2");
		productListHeading.appendChild(document.createTextNode("Articoli nell'ordine"));
		let productListContainer = document.createElement("div");
		productListContainer.className = "d-flex flex-wrap justify-content-around";
		productList.appendChild(productListHeading);
		productList.appendChild(productListContainer);
		data.cart.map((x) => {
			let container = document.createElement("div");
			container.classList.add("product");
			let img = document.createElement("img");
			img.src = "/image.php?id=" + x.product_id;
			img.alt = x.name;
			container.classList.add("position-relative");
			let quantity = document.createElement("span");
			quantity.className = "position-absolute top-100 start-100 translate-middle badge rounded-pill bg-success";
			quantity.appendChild(document.createTextNode(x.quantity));
			container.appendChild(quantity);
			container.appendChild(img);
			return container;
		}).forEach((x) => productListContainer.appendChild(x));
		res.appendChild(productList);
		for (let y in data["order"]) {
			let p = document.createElement("p");
			let t = document.createTextNode(y + ": " + data["order"][y]);
			p.appendChild(t);
			res.appendChild(p);
		}
		for (let y in data["updates"]) {
			let p = document.createElement("p");
			let t1 = document.createTextNode("timestamp: " + data["updates"][y]["timestamp"]);
			let t2 = document.createTextNode("update: " + data["updates"][y]["status"]);
			p.appendChild(t1);
			p.appendChild(t2);
			res.appendChild(p);
		}
	});
	return res;
}

async function update_item_quantity(product, quantity) {
	return await fetch("/edit-cart.php?product_id=" + product + "&quantity=" + quantity).then(x => x.json());
}

function create_go_to_cart_modal() {
	for (const modal of document.getElementsByClassName("modal")) {
		modal.remove();
	}
	const modalContent = `
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<h5 class="modal-title">Vui andare al carrello?</h5>
		</div>
		<div class="modal-body">
		</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
		<a class="btn btn-primary" role="button" href="/cart.php">Vai al carrello</a>
		</div>
	</div>
	</div>`;
	let root = document.createElement("div");
	root.className = " modal fade";
	root.tabIndex = -1;
	root.ariaLabel = "Vai al carrello";
	root.ariaHidden = true;
	root.innerHTML = modalContent;
	document.getElementsByTagName("body")[0].appendChild(root);
	return root;
}

function update_cart_items(cart) {
	let count = cart.items.map(x => parseInt(x.quantity)).reduce((p, x) => { return x + p; });
	document.getElementById("cart-count").innerHTML = count.toString();
}

async function update_item_quantity_btn(button) {
	let cart = await update_item_quantity(button.dataset["product"], button.dataset["increment"]);
	new bootstrap.Modal(create_go_to_cart_modal()).show();

	update_cart_items(cart);
}
