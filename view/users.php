<?php
/**
 * View all users
 *
 * @package gwelser-qgiv
 */

use GW\Pagination;

$limit      = 25;
$user_query = new GW\User_Query( $query_args['pg'], $limit, $query_args['ob'], $query_args['o'] );

/**
 * Order and sorting
 *
 * @param array $query_args The array of current url query parameters.
 * @param array $column The current column.
 */
function build_query( $query_args, $column ) {

	$args = array(
		'pg' => 1,
		'ob' => $column,
		'o'  => $column === $query_args['ob'] && 'a' === $query_args['o'] ? 'd' : 'a',
	);

	return http_build_query( $args );
}

?>
<div class="data-table-container">
	<table class="data-table">
		<thead>
			<tr>
				<th class="ui-secondary-color"><a href="?<?php echo build_query( $query_args, 'id' ); ?>">ID</a></th>
				<th class="ui-secondary-color"><a href="?<?php echo build_query( $query_args, 'fn' ); ?>">First Name</a></th>
				<th class="ui-secondary-color"><a href="?<?php echo build_query( $query_args, 'ln' ); ?>">Last Name</a></th>
				<th class="ui-secondary-color"><a href="?<?php echo build_query( $query_args, 'email' ); ?>">Email</a></th>
				<th class="ui-secondary-color"><a href="?<?php echo build_query( $query_args, 'dob' ); ?>">DOB</a></th>
				<th class="ui-secondary-color"></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $user_query->users() as $user ) {
				?>
			<tr class="data-row">
				<td><a href="?uid=<?php echo htmlspecialchars( $user->get_id() ); ?>"><?php echo htmlspecialchars( $user->get_id() ); ?></a></td>
				<td><?php echo htmlspecialchars( $user->get_first_name() ); ?></td>
				<td><?php echo htmlspecialchars( $user->get_last_name() ); ?></td>
				<td><?php echo htmlspecialchars( $user->get_email() ); ?></td>
				<td><?php echo htmlspecialchars( $user->get_birthdate() ); ?></td>
				<td><a href="?uid=<?php echo htmlspecialchars( $user->get_id() ); ?>">View Details</a></td>
			</tr> 
				<?php
			}
			?>
		</tbody>
	</table>

	<?php
	$pagination = new Pagination( $query_args['pg'], $limit, $user_query->get_total_users(), $query_args );
	echo $pagination->render();
	?>
</div>
