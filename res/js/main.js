"use strict";

async function display_order(order_id) {
	let res = document.createElement("div");
	fetch("/status_api.php?order_id=" + order_id).then(response => response.json()).then(data => {
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

function render_credit_card(card) {
	const icons = {
		"amex": "cc-amex",
		"diners": "cc-diners-club",
		"discover": "cc-discover",
		"jcb": "cc-jcb",
		"mastercard": "cc-mastercard",
		"unionpay": "credit-card",
		"visa": "cc-visa",
		"unknown": "credit-card",
	}
	const names = {
		"amex": "American Express",
		"diners": "Diners Club",
		"discover": "Discover",
		"jcb": "JCB",
		"mastercard": "MasterCard",
		"unionpay": "Union Pay",
		"visa": "VISA",
		"unknown": "",
	}
	let res = document.createElement("span");
	let cardInfo = document.createElement("span");
	cardInfo.classList.add("visually-hidden");
	cardInfo.appendChild(document.createTextNode("Carta " + names[card.brand]))
	res.appendChild(cardInfo);
	let icon = document.createElement("i");
	icon.className = "fa fa-" + icons[card.brand];
	icon.ariaHidden = true;
	res.appendChild(icon);
	let separator = document.createElement("span");
	separator.appendChild(document.createTextNode("\u25cf"));
	separator.ariaLabel = " che termina con ";
	separator.classList.add(m - 1);
	res.appendChild(separator);
	let lastFour = document.createElement("span");
	lastFour.appendChild(card.last4);
	res.appendChild(lastFour);
	return res;
}