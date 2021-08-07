<?php
/**
 * View user deatil
 */

$user = GW\User::get( $uid );

?>
<p><a href="index.php">Back to All Users</a></p>
<div class="data-table-container">
	<table class="data-table">
		<thead>
			<tr>
				<th class="ui-secondary-color">ID</th>
				<th class="ui-secondary-color">First Name</th>
				<th class="ui-secondary-color">Last Name</th>
				<th class="ui-secondary-color">Email</th>
				<th class="ui-secondary-color">DOB</th>
			</tr>
		</thead>
		<tbody>
			<tr class="data-row">
				<td><?php echo $user->get_id(); ?></td>
				<td><?php echo htmlspecialchars( $user->get_first_name() ); ?></td>
				<td><?php echo htmlspecialchars( $user->get_last_name() ); ?></td>
				<td><?php echo htmlspecialchars( $user->get_email() ); ?></td>
				<td><?php echo htmlspecialchars( $user->get_birthdate() ); ?></td>
			</tr> 
		</tbody>
	</table>

	<h2>Transactions</h2>
	<table class="data-table">
		<thead>
			<tr>
				<th class="ui-secondary-color">ID</th>
				<th class="ui-secondary-color">Amount</th>
				<th class="ui-secondary-color">Status</th>
				<th class="ui-secondary-color">Method</th>
				<th class="ui-secondary-color">Transaction</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $user->transactions as $transaction ) {
				?>
			<tr class="data-row">
				<td><?php echo $transaction->get_id(); ?></td>
				<td><?php echo htmlspecialchars( $transaction->get_amount() ); ?></td>
				<td><?php echo htmlspecialchars( $transaction->get_method() ); ?></td>
				<td><?php echo htmlspecialchars( $transaction->get_status() ); ?></td>
				<td><?php echo htmlspecialchars( $transaction->get_timestamp() ); ?></td>
			</tr> 
				<?php
			}
			?>
		</tbody>
	</table>
</div>
