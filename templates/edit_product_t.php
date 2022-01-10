<main class="row">
	<h2><?= $page_title; ?></h2>
	<section class="col-md-4">
		<img class="w-100" src="/image.php?id=<?= $product["id"] ?>" />
	</section>
	<form action="/update_product.php" method="post">
		<input name="id" type="hidden" value="<?= $product["id"] ?>" />
		<div class="form-group">
			<label>Nome <input name="name" type="text" value="<?= $product["name"] ?>"/></label>
		</div>
		<div class="form-group">
			<label>Descrizione <input name="description" type="text" value="<?= $product["description"] ?>" /></label>
		</div>
		<div class="form-group">
			<label>Prezzo in EUR <input name="price" type="number" step=0.01 value="<?= $product["price"]/100 ?>" /></label>
		</div>
		<div class="form-group">
			<label>Quantità <input name="quantity" type="number" step=1 value="<?= $product["quantity"] ?>" /></label>
		</div>
<?php if(is_null($product["id"])): ?>
		<div class="text-center mt-3">
			<button type="submit" formaction="/insert_product.php" class="btn btn-primary">Insert product</button>
			</div>
<?php else: ?>
		<div class="text-center mt-3">
			<button type="submit" formaction="/update_product.php" class="btn btn-primary">Update product</button>
		</div>
		<div class="text-center mt-3">
			<button type="submit" formaction="/delete_product.php" class="btn btn-primary">Delete product</button>
		</div>
<?php endif ?>
	</form>
</main>
