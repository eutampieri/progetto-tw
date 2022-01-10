<main class="row">
	<h2><?= $page_title; ?></h2>
	<?php if($product["id"] !== null): ?>
	<img alt="" class="w-100" src="/image.php?id=<?= $product["id"] ?>" />
	<?php endif; ?>
	<form enctype="multipart/form-data" action="/update_product.php" method="post">
		<input name="id" type="hidden" value="<?= $product["id"] ?>" />
		<div class="form-group">
			<label for="p_name">Nome</label>
			<input id="p_name" class="form-control" name="name" type="text" value="<?= $product["name"] ?>"/>
		</div>
		<div class="form-group">
			<label for="p_desc">Descrizione</label>
			<textarea id="p_desc" class="form-control" name="description"><?= $product["description"] ?></textarea>
		</div>
		<div class="form-group">
			<label for="p_price">Prezzo in EUR</label>
			<input id="p_price" class="form-control" name="price" type="number" step=0.01 value="<?= $product["price"]/100 ?>" />
		</div>
		<div class="form-group">
			<label for="p_qty">Quantità</label>
			<input id="p_qty" class="form-control" name="quantity" type="number" step=1 value="<?= $product["quantity"] ?>" /></label>
		</div>
		<div class="mb-3">
			<label for="p_img" class="form-label">Immagine</label>
			<input class="form-control" type="file" id="p_img" name="image">
		</div>
<?php if(is_null($product["id"])): ?>
		<div class="text-center mt-3">
			<button type="submit" formaction="/insert_product.php" class="btn btn-primary">Inserisci</button>
			</div>
<?php else: ?>
		<div class="text-center mt-3">
			<button type="submit" formaction="/update_product.php" class="btn btn-primary">Aggiorna</button>
		</div>
		<div class="text-center mt-3">
			<button type="submit" formaction="/delete_product.php" class="btn btn-danger">Elimina</button>
		</div>
<?php endif ?>
	</form>
</main>
