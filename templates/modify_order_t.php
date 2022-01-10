<main>
	<section class="row order" id="order_display">
	</section>
	<section>
		<h3>Dettagli cliente</h3>
		<main>
			Nome: <?= $user["name"]?>
		</main>
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
	<section class="row">
		<h3>Imposta numero di spedizione</h3>
		<form method="post" action="/set_tracking_number.php">
		<input required type="hidden" name="order_id" value="<?= $order_id ?>" />
			<div class="form-group">
				<label for="tracking_number">Numero di tracciamento</label>
				<input required type="text" class="form-control" id="tracking_number" name="tracking_number" placeholder="Numero di tracciamento" />
			</div>
			<label for="courier">Corriere</label>
			<div class="form-group">
				<select name="courier" id="courier" class="form-control">
<?php foreach($couriers as $courier): ?>
					<option value="<?= $courier["id"] ?>"><?= $courier["name"] ?></option>
<?php endforeach; ?>
				</select>
			</div>
			<button type="submit" class="btn btn-primary">Modifica</button>
		</form>	
	</section>
</main>
<script>
	async function load(){
		document.getElementById("order_display").appendChild(await displayOrder(<?= $order_id ?>, 3));
	}
	load();
</script>

