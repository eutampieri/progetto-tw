"use strict";

async function display_order(order_id, previous_heading_level) {
	let hStart = 0;
	if (previous_heading_level !== undefined) {
		hStart = parseInt(previous_heading_level);
	}
	let res = document.createElement("div");
	let title = document.createElement("h" + (1 + hStart).toString());
	title.appendChild(document.createTextNode("Ordine #" + order_id.toString()));
	res.appendChild(title);
	fetch("/status_api.php?order_id=" + order_id).then(response => response.json()).then(data => {
		let productList = document.createElement("section");
		let productListHeading = document.createElement("h" + (2 + hStart).toString());
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
		if (data.order.tracking_number !== null) {
			let alert = document.createElement("div");
			alert.className = "alert alert-info";
			alert.innerHTML = "Il tuo ordine è stato spedito con " + data.order.courier_name +
				". Il codice di tracciamento è <pre>" + data.order.tracking_number + "</pre>.";
			res.appendChild("alert");
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
