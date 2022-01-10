<main>
	<section class="row order" id="order_display">
	</section>
	<section class="row">
		<h3>Aggiungi stato</h3>
		<form method="post" action="/add_order_status.php">
		<input required type="hidden" name="order_id" value="<?= $order_id ?>" />
			<div class="form-group">
				<label for="status">Nuovo stato</label>
				<input required type="text" class="form-control" id="status" name="status" placeholder="Nuovo stato" />
			</div>
			<button type="submit" class="btn btn-primary">Aggiungi</button>
		</form>	
	</section>
</main>
<script>
	async function load(){
		document.getElementById("order_display").appendChild(await displayOrder(<?= $order_id ?>));
	}
	load();
</script>

