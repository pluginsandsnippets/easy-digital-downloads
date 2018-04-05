<?php
/**
 * Note Meta DB class
 *
 * This class is for interacting with the note meta database table
 *
 * @package     EDD
 * @subpackage  Classes/Notes
 * @copyright   Copyright (c) 2018, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class EDD_DB_Note_Meta extends EDD_DB {

	/**
	 * Initialise object variables and register table.
	 *
	 * @since 3.0
	 */
	public function __construct() {
		global $wpdb;

		$this->table_name  = $wpdb->prefix . 'edd_notemeta';
		$this->primary_key = 'meta_id';
		$this->version     = '1.0';
	}

	/**
	 * Retrieve table columns and data types.
	 *
	 * @access public
	 * @since 3.0
	 *
	 * @return array Array of table columns and data types.
	 */
	public function get_columns() {
		return array(
			'meta_id'     => '%d',
			'edd_note_id' => '%d',
			'meta_key'    => '%s',
			'meta_value'  => '%s',
		);
	}

	/**
	 * Retrieve meta field for a note.
	 *
	 * For internal use only. Use EDD_Note->get_meta() for public usage.
	 *
	 * @access public
	 * @since 3.0
	 *
	 * @param int    $note_id  Note ID.
	 * @param string $meta_key Optional. Metadata key. If not specified, retrieve all metadata for the specified object.
	 * @param bool   $single   Optional, default is false.
	 *                         If true, return only the first value of the specified meta_key.
	 *                         This parameter has no effect if meta_key is not specified.
	 *
	 * @return mixed array|false Single metadata value, or array of values.
	 */
	public function get_meta( $note_id = 0, $meta_key = '', $single = false ) {
		$note_id = $this->sanitize_note_id( $note_id );

		if ( false === $note_id ) {
			return false;
		}

		return get_metadata( 'edd_note', $note_id, $meta_key, $single );
	}

	/**
	 * Add meta data field to a discount.
	 *
	 * For internal use only. Use EDD_Discount->add_meta() for public usage.
	 *
	 * @access public
	 * @since 3.0
	 *
	 * @param int    $note_id    Note ID.
	 * @param string $meta_key   Metadata key.
	 * @param mixed  $meta_value Metadata value.
	 * @param bool   $unique     Optional, default is false.
	 *                           Whether the specified metadata key should be unique for the object.
	 *                           If true, and the object already has a value for the specified metadata key,
	 *                           no change will be made.
	 *
	 * @return int|false The meta ID on success, false on failure.
	 */
	public function add_meta( $note_id = 0, $meta_key = '', $meta_value, $unique = false ) {
		$note_id = $this->sanitize_note_id( $note_id );

		if ( false === $note_id ) {
			return false;
		}

		return add_metadata( 'edd_note', $note_id, $meta_key, $meta_value, $unique );
	}

	/**
	 * Update discount meta field based on discount ID.
	 *
	 * For internal use only. Use EDD_Discount->update_meta() for public usage.
	 *
	 * Use the $prev_value parameter to differentiate between meta fields with the
	 * same key and Discount ID.
	 *
	 * If the meta field for the discount does not exist, it will be added.
	 *
	 * @access public
	 * @since 3.0
	 *
	 * @param int    $note_id    Note ID.
	 * @param string $meta_key   Metadata key.
	 * @param mixed  $meta_value Metadata value.
	 * @param mixed  $prev_value Optional. Previous value to check before removing.
	 *
	 * @return int|bool Meta ID if the key didn't exist, true on successful update, false on failure.
	 */
	public function update_meta( $note_id = 0, $meta_key = '', $meta_value, $prev_value = '' ) {
		$note_id = $this->sanitize_note_id( $note_id );

		if ( false === $note_id ) {
			return false;
		}

		return update_metadata( 'edd_note', $note_id, $meta_key, $meta_value, $prev_value );
	}

	/**
	 * Remove metadata matching criteria from a discount.
	 *
	 * For internal use only. Use EDD_Discount->delete_meta() for public usage.
	 *
	 * You can match based on the key, or key and value. Removing based on key and
	 * value, will keep from removing duplicate metadata with the same key. It also
	 * allows removing all metadata matching key, if needed.
	 *
	 * @access public
	 * @since 3.0
	 *
	 * @param int    $note_id    Note ID.
	 * @param string $meta_key   Metadata key.
	 * @param mixed  $meta_value Optional. Metadata value. Must be serializable if non-scalar. If specified, only delete
	 *                           metadata entries with this value. Otherwise, delete all entries with the specified meta_key.
	 *                           Pass `null, `false`, or an empty string to skip this check. (For backward compatibility,
	 *                           it is not possible to pass an empty string to delete those entries with an empty string
	 *                           for a value.)
	 *
	 * @return bool True on successful delete, false on failure.
	 */
	public function delete_meta( $note_id = 0, $meta_key = '', $meta_value = '' ) {
		return delete_metadata( 'edd_note', $note_id, $meta_key, $meta_value );
	}

	/**
	 * Given a note ID, make sure it's a positive number, greater than zero before inserting or adding.
	 *
	 * @access private
	 * @since 3.0
	 *
	 * @param mixed int|string $note_id Note ID.
	 *
	 * @return mixed int|bool The normalized note ID or false if it's found to not be valid.
	 */
	private function sanitize_note_id( $note_id = 0 ) {
		if ( ! is_numeric( $note_id ) ) {
			return false;
		}

		$note_id = (int) $note_id;

		// We were given a non positive number
		if ( absint( $note_id ) !== $note_id ) {
			return false;
		}

		if ( empty( $note_id ) ) {
			return false;
		}

		return absint( $note_id );
	}
}