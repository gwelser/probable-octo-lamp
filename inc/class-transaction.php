<?php
/**
 * Transaction
 *
 * @package gwelser-qgic
 */

namespace GW;

/**
 * Transaction class
 */
class Transaction {

	/**
	 * Transaction ID
	 *
	 * @var int
	 */
	public $id;

	/**
	 * User ID
	 *
	 * @var int
	 */
	public $user_id;

	/**
	 * Transaction timestamp
	 *
	 * @var \DateTime
	 */
	public $timestamp;

	/**
	 * Amount of transaction
	 *
	 * @var float
	 */
	public $amount;

	/**
	 * Transaction status
	 *
	 * @var string
	 */
	public $status;

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $method;

	/**
	 * Whether the transaction has been updated.
	 *
	 * @var bool
	 */
	private $is_dirty;

	/**
	 * Transaction contructor
	 *
	 * @param int    $id The trnasaction ID.
	 * @param int    $user_id User ID.
	 * @param float  $amount Transaction amount.
	 * @param string $status Transaction status.
	 * @param string $method Transaction payment method.
	 */
	public function __construct( $id = null, $user_id, $amount, $status, $method ) {

		$this->id      = $id;
		$this->user_id = $user_id;
		$this->amount  = $amount;
		$this->status  = $status;
		$this->method  = $method;

		$this->timestamp = new \DateTime();

		$this->is_dirty = null === $id;

	}

	/**
	 * Get transaction ID
	 *
	 * @return int
	 */
	public function get_id() {

		return $this->id;
	}

	/**
	 * Get unformatted transaction amount
	 *
	 * @return float
	 */
	public function get_amount_raw() {

		return $this->amount;
	}

	/**
	 * Get transaction amount formatted as currency
	 *
	 * @return string
	 */
	public function get_amount() {

		return sprintf( '$%s', number_format( $this->amount, 2 ) );
	}

	/**
	 * Get transaction status
	 *
	 * @return string
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * Get transaction payment method
	 *
	 * @return string
	 */
	public function get_method() {

		return $this->method;
	}

	/**
	 * Get formatted transaction timestamp
	 *
	 * @return \DateTime
	 */
	public function get_timestamp_raw() {

		return $this->timestamp;
	}

	/**
	 * Get formatted transaction timestamp
	 *
	 * @return string
	 */
	public function get_timestamp() {

		return $this->timestamp->format( 'm/d/Y H:i' );
	}

	/**
	 * Save the transaction
	 */
	public function save() {

		if ( ! $this->is_dirty ) {
			return;
		}

		$db = Database::connect();

		$stmt = $db->prepare( 'INSERT INTO Transactions (user_id, amount, status, method) VALUES (?, ?, ?, ?);' );
		$stmt->bind_param( 'idss', $this->user_id, $this->amount, $this->status, $this->method );
		$stmt->execute();

		$this->id       = $stmt->insert_id;
		$this->is_dirty = false;

		$stmt->close();
		$db->close();

	}

	/**
	 * Retrives all transactions for a user
	 *
	 * @param int $user_id The user id.
	 *
	 * @return Transaction[]
	 */
	public static function with_user_id( $user_id ) {

		$db   = Database::connect();
		$stmt = $db->prepare( 'SELECT id, user_id, amount, status, method, timestamp FROM Transactions WHERE user_id = ?  ORDER BY id;' );
		$stmt->bind_param( 'i', $user_id );
		$stmt->execute();
		$result = $stmt->get_result();

		$transactions = array();
		while ( $transaction = $result->fetch_assoc() ) {
			$transactions[] = new Transaction( $transaction['id'], $transaction['user_id'], $transaction['amount'], $transaction['status'], $transaction['method'], $transaction['timestamp'] );
		}

		return $transactions;

	}

	/**
	 * Add a transaction
	 *
	 * @param int    $user_id The user id.
	 * @param float  $amount Transaction amount.
	 * @param string $status Transaction status.
	 * @param string $method Transaction payment method.
	 */
	public static function add_transaction( $user_id, $amount, $status, $method ) {

		$transaction = new Transaction( null, $user_id, $amount, $status, $method );
		$transaction->save();

	}

}
