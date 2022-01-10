<?php
require_once(dirname(dirname(__FILE__))."/utils.php");
?>
<div class="d-flex flex-wrap justify-content-around">
  <?php foreach($products as $product): ?>
  <div class="card m-2 product" >
    <!--a href="/product.php?id=<?= $product["id"] ?>"-->
    <img src="/image.php?id=<?= $product["id"] ?>" class="card-img-top"
      alt=""><!--/a-->
    <div class="card-body">
      <a href="/product.php?id=<?= $product["id"] ?>">
      <h2><?= $product["name"] ?></h2>
      </a>
      <button <?= intval($product["quantity"]) > 0 ? "": "disabled"?> onclick="update_item_quantity_btn(this)" data-product="<?= $product["id"] ?>" data-increment="1" class="btn btn-<?= intval($product["quantity"]) > 0 ? "primary": "danger"?> showlink"><i class="fa fa-cart-plus" aria-hidden="true"></i> <?= price_to_string($product["price"])?></button>
    </div>
  </div>
  <?php endforeach; ?>
</div>
