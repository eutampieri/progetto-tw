<h2>I tuoi dati</h2>
<form>
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="password" name="name" class="form-control" id="name" value="<?= $user["name"] ?>">
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Indirizzo email</label>
      <input type="email" name="email" class="form-control" id="email" value="<?= $user["email"] ?>">
    </div>
  <button type="submit" class="btn btn-primary">Aggiorna</button>
</form>
<h2>I tuoi ordini</h2>
<table class="table">
    <thead>
        <tr>
            <th scope="col">Data</th>
            <th scope="col">Numero d'ordine</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($orders as $order) :?>
        <tr>
            <td scope="col"><?= date("%d/%m/%Y %H:%M", intval($order["date"])) ?></td>
            <td scope="col"><?= $order["id"] ?></td>
            <td scope="col">// TODO</td>
        </tr>
        <?php endforeach;
        if(count($orders) == 0): ?>
        <div class="alert alert-info" role="alert">
            Sembra che tu non abbia ancora effettuato alcun ordine.
            Potresti andare sulla <a href="/">homepage</a> per vedere se c'è qualcosa di interessante&hellip;
        </div>
        <?php endif; ?>
    </tbody>
</table>
<?php
