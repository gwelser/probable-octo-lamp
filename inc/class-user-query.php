<?php
/**
 * User Query
 *
 * @package gwelser-qgiv
 */

namespace GW;

/**
 * User_Query class
 */
class User_Query {

	/**
	 * Current page
	 *
	 * @var int
	 */
	private $page;

	/**
	 * Limit results
	 *
	 * @var int
	 */
	private $limit;

	/**
	 * Total number of users
	 *
	 * @var int
	 */
	private $total_users;

	/**
	 * Users
	 *
	 * @var User[]
	 */
	private $users;

	/**
	 * Class constructor
	 *
	 * @param int    $page The page of results to get.
	 * @param int    $limit Number of results to get.
	 * @param string $orderby Column by which to order results.
	 */
	public function __construct( $page = 1, $limit = 50, $orderby = 'ln', $order = 'a' ) {

		$this->page        = (int) $page;
		$this->limit       = (int) $limit;
		$this->users       = $this->get_users( $orderby, $order );
		$this->total_users = $this->get_user_count();

	}

	/**
	 * Get the current page.
	 *
	 * @return int
	 */
	public function get_current_page() {

		return $this->page;
	}

	/**
	 * Get the limit
	 *
	 * @return $int
	 */
	public function get_limit() {

		return $this->limit;
	}

	/**
	 * Get the total number of users.
	 *
	 * @return int
	 */
	public function get_total_users() {

		return $this->total_users;
	}

	/**
	 * Users
	 *
	 * @return User[]
	 */
	public function users() {

		return $this->users;
	}

	/**
	 * Get users
	 *
	 * @param string $orderby Column by which to order results.
	 * @param string $order Sort order.
	 *
	 * @return User[]
	 */
	private function get_users( $orderby, $order ) {

		$allowed_orderby = $this->allowed_orderby( $orderby );
		$allowed_order   = $this->allowed_order( $order );
		$offset          = (int) ( $this->page - 1 ) * $this->limit;

		$sql = sprintf( 'SELECT id, first_name, last_name, email, birthdate FROM Users ORDER BY %s %s LIMIT %d OFFSET %d', $allowed_orderby, $allowed_order, $this->limit, $offset );

		$db     = Database::connect();
		$result = $db->query( $sql );

		$users = array();
		while ( $user = $result->fetch_assoc() ) {
			$users[] = new User( $user['id'], $user['first_name'], $user['last_name'], $user['email'], $user['birthdate'] );
		}

		$db->close();

		return $users;
	}

	/**
	 * Get totalnumber of users
	 *
	 * @return int
	 */
	private function get_user_count() {

		$db         = Database::connect();
		$result     = $db->query( 'SELECT COUNT(id) AS user_count FROM Users' );
		$row        = $result->fetch_assoc();
		$user_count = (int) $row['user_count'];

		$db->close();

		return $user_count;
	}

	/**
	 * Get allowed order by
	 *
	 * @param string $input The raw order by string.
	 *
	 * @return string
	 */
	private function allowed_orderby( $input ) {

		switch ( $input ) {
			case 'id':
				return 'id';

			case 'fn':
				return 'first_name';

			case 'email':
				return 'email';

			case 'dob':
				return 'birthdate';

			case 'ln':
			default:
				return 'last_name';
		}

	}

	/**
	 * Get allowed order
	 *
	 * @param string $input The raw order string.
	 *
	 * @return string
	 */
	private function allowed_order( $input ) {

		switch ( $input ) {
			case 'd':
				return 'DESC';

			default:
				return 'ASC';
		}

	}
}
