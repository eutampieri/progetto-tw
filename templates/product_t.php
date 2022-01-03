<?php
require_once(dirname(dirname(__FILE__))."/utils.php");
?>
<main class="row">
    <section class="col-md-4">
        <img class="w-100" src="/image.php?id=<?= $product["id"] ?>">
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
        <button onclick="update_item_quantity_btn(this)" data-product="<?= $product["id"] ?>" data-increment="1" class="btn btn-primary showlink"><i class="fa fa-cart-plus" aria-hidden="true"></i>
            Aggiungi al carrello
        </button>
    </section>
</main>