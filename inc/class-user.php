<?php
/**
 * User
 *
 * @package gwelser-qgiv
 */

namespace GW;

/**
 * User class
 */
class User {

	/**
	 * User ID
	 *
	 * @var int
	 */
	private $id;

	/**
	 * First name
	 *
	 * @var string
	 */
	private $first_name;

	/**
	 * Last name
	 *
	 * @var string
	 */
	private $last_name;

	/**
	 * Date of birth
	 *
	 * @var \DateTime
	 */
	private $birthdate;

	/**
	 * Email address
	 *
	 * @var string
	 */
	private $email;

	/**
	 * Whther the user data has been updated.
	 *
	 * @var bool
	 */
	private $is_dirty;

	/**
	 * User transactions
	 *
	 * @var Transaction[]
	 */
	public $transactions;

	/**
	 * User constructor
	 *
	 * @param int    $id The user id.
	 * @param string $first_name The user's first name.
	 * @param string $last_name The user's last name.
	 * @param string $email The user's email address.
	 * @param string $birthdate The user's first name.
	 */
	public function __construct( $id = null, $first_name, $last_name, $email, $birthdate ) {

		$this->id         = $id;
		$this->first_name = $first_name;
		$this->last_name  = $last_name;
		$this->email      = $email;
		$this->birthdate  = new \DateTime( $birthdate );
		$this->is_dirty   = true;

		if ( null !== $id ) {
			$this->is_dirty     = false;
			$this->transactions = Transaction::with_user_id( $id );
		}

	}

	/**
	 * Set the user's first name.
	 *
	 * @param string $first_name First name.
	 */
	public function set_first_name( $first_name ) {

		if ( $first_name !== $this->first_name ) {
			$this->first_name = $first_name;
			$this->is_dirty   = true;
		}

	}

	/**
	 * Set the user's last name.
	 *
	 * @param string $last_name Last name.
	 */
	public function set_last_name( $last_name ) {

		if ( $last_name !== $this->last_name ) {
			$this->last_name = $last_name;
			$this->is_dirty  = true;
		}

	}

	/**
	 * Set the user's email address
	 *
	 * @param string $email Email address.
	 */
	public function set_email( $email ) {

		if ( $email !== $this->email ) {
			$this->email    = $email;
			$this->is_dirty = true;
		}
	}

	/**
	 * Get user id.
	 *
	 * @return string
	 */
	public function get_id() {

		return $this->id;
	}

	/**
	 * Get first name.
	 *
	 * @return string
	 */
	public function get_first_name() {

		return $this->first_name;
	}

	/**
	 * Get last name.
	 *
	 * @return string
	 */
	public function get_last_name() {

		return $this->last_name;
	}

	/**
	 * Get email.
	 *
	 * @return string
	 */
	public function get_email() {

		return $this->email;
	}

	/**
	 * Get formatted birthdate.
	 *
	 * @return string
	 */
	public function get_birthdate() {

		return $this->birthdate->format( 'F j, Y' );
	}

	/**
	 * Get unformatted birthdate.
	 *
	 * @return \DateTime
	 */
	public function get_birthdate_raw() {

		return $this->birthdate;
	}

	/**
	 * Save user to database
	 */
	public function save() {

		if ( ! $this->is_dirty ) {
			return;
		}

		$db = Database::connect();

		$birthdate = $this->birthdate->format( 'Y-m-d' );

		$stmt = $db->prepare( 'INSERT INTO Users (first_name, last_name, email, birthdate) VALUES (?, ?, ?, ?);' );
		$stmt->bind_param( 'ssss', $this->first_name, $this->last_name, $this->email, $birthdate );
		$stmt->execute();

		$this->id       = $stmt->insert_id;
		$this->is_dirty = false;

		$stmt->close();
		$db->close();

	}

	/**
	 * Retrieve a single user by ID.
	 *
	 * @param int $id The user id.
	 *
	 * @return User
	 */
	public static function get( $id ) {

		$db   = Database::connect();
		$stmt = $db->prepare( 'SELECT id, first_name, last_name, email, birthdate FROM Users WHERE id = ?;' );
		$stmt->bind_param( 'i', $id );
		$stmt->execute();
		$result = $stmt->get_result();
		$user   = $result->fetch_assoc();

		$stmt->close();
		$db->close();

		return new User( $user['id'], $user['first_name'], $user['last_name'], $user['email'], $user['birthdate'] );
	}

}
