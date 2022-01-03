<?= require("cart_t.php") ?>
<main class="row" id="main_content">
<div class="family d-flex flex-wrap justify-content-around">
  <?php foreach($order as $order_elements): ?>
    <img src="/image.php?id=<?= $order_element["product_id"] ?>" class="card-img-top" alt="">
		<p>Quantity: <?= $order_element["quantity"] ?></p>
  <?php endforeach; ?>
</div>
</main>
<script>
	async function load(){
		document.getElementById("main_content").appendChild(await display_order(1));
	}
	load();
</script>

