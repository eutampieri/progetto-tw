<?php
require_once(dirname(dirname(__FILE__))."/utils.php");
?>
<main class="row">
    <section aria-hidden="true" class="col-md-4">
        <img alt="" class="w-100" src="/image.php?id=<?= $product["id"] ?>">
    </section>
    <section class="col-md-8">
        <div class="d-flex justify-content-between">
            <h2>
                <?= $product["name"] ?>
            </h2>
            <span class="price">
                <?= price_to_string($product["price"])?>
            </span>
        </div>
        <p>
            <?= $product["description"] ?>
        </p>
        <?php if (intval($product["quantity"]) > 0):?>
        <div role="alert" class="alert alert-danger">Il prodotto è esaurito</div>
        <?php endif; ?>
        <button <?= intval($product["quantity"]) > 0 ? "": "disabled"?> onclick="updateItemQuantityButton(this)" data-product="<?= $product["id"] ?>" data-increment="1" class="btn btn-<?= intval($product["quantity"]) > 0 ? "primary": "danger"?> showlink"><i class="fa fa-cart-plus" aria-hidden="true"></i>
            Aggiungi al carrello
        </button>
        <?php if(isset($_SESSION["admin"]) && $_SESSION["admin"]==1):?>
            <a role="button" class="btn btn-primary" href="/edit_product.php?id=<?= $product["id"] ?>"><i class="fa fa-pencil" aria-hidden="true"></i> Modifica prodotto</a>
        <?php endif; ?>
		</section>
</main>
