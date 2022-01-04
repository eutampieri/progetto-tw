<main class="row" id="main_content">
	<?php if(isset($show_payment_successful_message) && $show_payment_successful_message): ?>
	<div class="alert alert-success" role="alert">
		Il tuo pagamento è riuscito. Trovi qui sotto i dettagli dell'ordine.
	</div>
	<?php endif; ?>
<div class="family d-flex flex-wrap justify-content-around">
  <?php foreach($cart as $order_element): ?>
		<div class="product">
			<img src="/image.php?id=<?= $order_element["product_id"] ?>" class="card-img-top" alt="">
			<p>Quantity: <?= $order_element["quantity"] ?></p>
		</div>
  <?php endforeach; ?>
</div>
</main>
<script>
	async function load(){
		document.getElementById("main_content").appendChild(await display_order(<?= $order_id ?>));
	}
	load();
</script>

