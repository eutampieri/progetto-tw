<?php
require_once(dirname(dirname(__FILE__))."/utils.php");
?>
<h1>Scegli una proiezione</h1>
<div id="showlist" class="family d-flex flex-wrap justify-content-around">
  <?php foreach($products as $product): ?>
  <div class="card m-2" style="width: 15rem;">
    <img src="https://picsum.photos/200/300" class="card-img-top"
      alt="">
    <div class="card-body">
      <h2><?= $product["title"] ?></h2>
      <a href="." class="btn btn-primary showlink"><i class="fa fa-cart-plus" aria-hidden="true"></i> <?= price_to_string($product["price"])?></a>
    </div>
  </div>
  <?php endforeach; ?>
</div>
