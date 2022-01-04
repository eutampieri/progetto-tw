<?php
require_once(dirname(dirname(__FILE__))."/utils.php");
?>
<script>
async function update_cart(item) {
	let diff=parseInt(item.value)-parseInt(item.dataset["oldvalue"]);
	item.disabled=true;
	let cart = await update_item_quantity(parseInt(item.id), diff);
	let delivery_price = cart.delivery_price;
	let total_price=delivery_price;
	for(const cart_item of cart.items) {
		total_price+=parseInt(cart_item.price)*parseInt(cart_item.quantity);
		if(item.id == cart_item.product_id) {
			item.dataset["oldvalue"]=cart_item.quantity;
		}
	}
	// update_cart_items(cart);
	document.getElementById("total_price").innerHTML=((total_price/100.0).toFixed(2)+" &euro;").replace('.',',');
	document.getElementById("delivery_price").innerHTML=((delivery_price/100.0).toFixed(2)+" &euro;").replace('.',',');
	item.disabled=false;
}
function add(item_id, delta) {
	let item = document.getElementById(item_id);
	item.value = parseInt(item.value)+delta;
	update_cart(item);
}
</script>
<h1>Checkout</h1>
<form id="cart" method="POST" action="pay.php">
	<?php if(isset($_SESSION["payment_failed"])): ?>
		<div class="alert alert-danger" role="alert">
			Il pagamento è fallito, riprova.
		</div>
		<?php unset($_SESSION["payment_failed"]);
	endif; ?>
	<input type="hidden" name="create_checkout">
	<?php foreach($cart as $cart_item): ?>
	<div class="row">
		<div class="col-3 col-lg-2">
			<img src="/image.php?id=<?= $cart_item["product_id"] ?>" alt="" class="w-100"></a>
		</div>
		<div class="col-9 col-lg-10">
			<h2><?= $cart_item['title'] ?></h2>
			<p>
				<label for="<?= $cart_item['product_id']?>">quantity</label>
				<button type="button" class="btn btn-outline-primary btn-sm ms-1 font-monospace d-md-none" onclick=add(<?= $cart_item['product_id']?>,-1)>-</button>
				<button type="button" class="btn btn-outline-primary btn-sm me-1 font-monospace d-md-none" onclick=add(<?= $cart_item['product_id']?>,1)>+</button>

				<input id="<?= $cart_item['product_id']?>" type="number" style="width:3em;" min=0 step=1 value="<?= $cart_item['quantity'] ?>" data-oldvalue="<?= $cart_item['quantity'] ?>" onchange="update_cart(this);" /> x <?= price_to_string($cart_item['price']) ?>
			</p>
		</div>
	</div>
	<?php endforeach; ?>
	<div class="row">
		<div class="col">Spese di spedizione</div>
		<div class="col" id="delivery_price"><?= price_to_string($delivery_price) ?></div>
	</div>
	<div class="row">
		<div class="col">Totale ordine</div>
		<div class="col" id="total_price"><?= price_to_string($delivery_price + array_reduce(array_map(fn($product):int => $product['price']*$product['quantity'],$cart), fn($a, $b):int => $a+$b)) ?></div>
	</div>
	<div class="row">
		<div class="col-sm-12 col-md-3 m-auto"><button type="submit" class="w-100 mt-3 m-auto btn btn-primary m-2">Paga ora</button></div>
	</div>
</form>
	
