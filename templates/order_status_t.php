<main class="row" id="main_content">
	<?php if(isset($show_payment_successful_message) && $show_payment_successful_message): ?>
	<div class="alert alert-success" role="alert">
		Il tuo pagamento è riuscito. Trovi qui sotto i dettagli dell'ordine.
	</div>
	<?php endif; ?>
</main>
<script>
	async function load(){
		document.getElementById("main_content").appendChild(await display_order(<?= $order_id ?>));
	}
	load();
</script>

