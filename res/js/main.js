"use strict";

function formatDate(date) {
	let formatted = date.getDate().toString().padStart(2, "0");
	formatted += "/";
	formatted += (date.getMonth() + 1).toString().padStart(2, "0");
	formatted += "/";
	formatted += date.getFullYear().toString().padStart(4, "0");
	formatted += " ";
	formatted += date.getHours().toString().padStart(2, "0");
	formatted += ":";
	formatted += date.getMinutes().toString().padStart(2, "0");
	return formatted;
}

async function displayOrder(order_id, previous_heading_level) {
	let hStart = 0;
	if (previous_heading_level !== undefined) {
		hStart = parseInt(previous_heading_level);
	}
	let res = document.createElement("div");
	res.classList.add("order");
	let title = document.createElement("h" + (1 + hStart).toString());
	title.appendChild(document.createTextNode("Ordine #" + order_id.toString()));
	res.appendChild(title);
	fetch("/status_api.php?order_id=" + order_id).then(response => response.json()).then(data => {
		let productList = document.createElement("section");
		let productListHeading = document.createElement("h" + (2 + hStart).toString());
		let cardInfo = document.createElement("p");
		cardInfo.appendChild(document.createTextNode("Pagato con "));
		cardInfo.appendChild(renderCreditCard(data.payment_infos));
		res.appendChild(cardInfo);
		productListHeading.appendChild(document.createTextNode("Articoli nell'ordine"));
		let productListContainer = document.createElement("div");
		productListContainer.className = "d-flex flex-wrap justify-content-around";
		productListContainer.style = "margin-bottom: 1em";
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
		res.appendChild((() => {
			let x = document.createElement("h" + (2 + hStart).toString());
			x.appendChild(document.createTextNode("Aggiornamenti sul tuo ordine"));
			return x;
		})())
		let table = document.createElement("table");
		table.classList.add("table");
		let thead = document.createElement("thead");
		thead.innerHTML = '<tr><th scope="col">Data</th><th scope="col">Stato</th><th scope="col">Luogo</th></tr>';
		table.appendChild(thead);
		let tbody = document.createElement("tbody");
		table.appendChild(tbody);
		res.appendChild(table);
		for (const update of data.updates) {
			let row = document.createElement("tr");
			let dateCol = document.createElement("td");
			dateCol.appendChild(document.createTextNode(formatDate(new Date(parseInt(update.timestamp) * 1000))))
			row.appendChild(dateCol);
			let actionCol = document.createElement("td");
			actionCol.appendChild(document.createTextNode(update.status))
			row.appendChild(actionCol);
			let placeCol = document.createElement("td");
			placeCol.appendChild(document.createTextNode(update.place))
			row.appendChild(placeCol);
			//row.childNodes.forEach(x => x.scope = "col");
			tbody.appendChild(row);
		}
	});
	return res;
}

async function updateItemQuantity(product, quantity) {
	return await fetch("/edit-cart.php?product_id=" + product + "&quantity=" + quantity).then(x => x.json());
}

function createGoToCartModal(item, quantity) {
	for (const modal of document.getElementsByClassName("modal")) {
		modal.remove();
	}
	const modalContent = `
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<h5 class="modal-title">Vuoi andare al carrello?</h5>
		</div>
		<div class="modal-body" id="cartModalProduct">
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
	const body = root.querySelector("#cartModalProduct");
	let product = document.createElement("p");
	product.innerHTML = "Hai aggiunto " + quantity.toString() + " " + item.name + " al carrello";
	body.appendChild(product);
	document.getElementsByTagName("body")[0].appendChild(root);
	return root;
}

function updateCartItems(cart) {
	let count = cart.items.map(x => parseInt(x.quantity)).reduce((p, x) => { return x + p; });
	document.getElementById("cart-count").innerHTML = count.toString();
}

async function updateItemQuantityButton(button) {
	let cart = await updateItemQuantity(button.dataset["product"], button.dataset["increment"]);
	const item = cart.items.find((x) => parseInt(x.product_id) === parseInt(button.dataset["product"]));
	new bootstrap.Modal(createGoToCartModal(item, parseInt(button.dataset["increment"]))).show();

	updateCartItems(cart);
}

function renderCreditCard(card) {
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
	separator.classList.add("m-1");
	res.appendChild(separator);
	let lastFour = document.createElement("span");
	lastFour.appendChild(document.createTextNode(card.last4));
	res.appendChild(lastFour);
	return res;
}

function priceToString(price) {
	return ((price / 100.0).toFixed(2) + " &euro;").replace('.', ',');
}

