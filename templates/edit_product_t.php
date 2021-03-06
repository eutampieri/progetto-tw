<main class="row">
	<h2><?= $page_title; ?></h2>
	<div class="row">
	<?php if($product["id"] !== null): ?>
		<div class="col-md-4">
			<img alt="" class="w-100" src="/image.php?id=<?= $product["id"] ?>" />
		</div>
	<?php endif; ?>
	<form class="col-md-<?= $product["id"] !== null ? 8 : 12 ?>" enctype="multipart/form-data" action="/update_product.php" method="post">
		<input required name="id" type="hidden" value="<?= $product["id"] ?>" />
		<div class="form-group">
			<label for="p_name">Nome</label>
			<input required placeholder="Nome prodotto" id="p_name" class="form-control" name="name" type="text" value="<?= $product["name"] ?>"/>
		</div>
		<div class="form-group">
			<label for="p_desc">Descrizione</label>
			<textarea required placeholder="Descrizione del prodotto" id="p_desc" class="form-control" name="description"><?= $product["description"] ?></textarea>
		</div>
		<div class="form-group">
			<label for="p_price">Prezzo in EUR</label>
			<input required id="p_price" class="form-control" name="price" type="number" step=0.01 value="<?= $product["price"]/100 ?>" />
		</div>
		<div class="form-group">
			<label for="p_qty">Quantità in magazzino</label>
			<input required id="p_qty" class="form-control" name="quantity" type="number" step=1 value="<?= $product["quantity"] ?>" /></label>
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
</div>
</main>
