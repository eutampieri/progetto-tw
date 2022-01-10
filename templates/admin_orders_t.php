<h2>Lista ordini</h2>
<table class="table">
	<thead>
		<tr>
			<th scope="col">Data</th>
			<th scope="col">Numero d'ordine</th>
			<th scope="col">Ultimo aggiornamento</th>
			<th scope="col"></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($orders as $order) :?>
		<tr>
			<td><?= date("d/m/Y H:i", intval($order["date"])) ?></td>
			<td><?= $order["id"] ?></td>
			<td><?= $order["last_status"] ?></td>
			<td>
				<a class="btn btn-info" role="button" href="/modify_order.php?order_id=<?= $order["id"] ?>">
					<i class="fa fa-pencil" aria-hidden="true"></i>
					Modifica l'ordine
				</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
