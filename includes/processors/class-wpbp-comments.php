<?php
/**
 * WPBP_Comments Class.
 *
 * @class       WPBP_Comments
 * @version		1.0
 * @author lafif <hello@lafif.me>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Bulk_Process Comments class.
 */
class WPBP_Comments extends Bulk_Process {
	/**
	 * The individual batch's parameter for specifying the amount of results to return.
	 *
	 * @var string
	 */
	public $per_batch_param = 'number';

	/**
	 * Default args for the query.
	 *
	 * @var array
	 */
	public $default_args = array(
		'number' => 10,
		'offset' => 0,
		'no_found_rows' => false,
	);

	/**
	 * Get results function for the registered batch process.
	 *
	 * @return array \WP_Term_Query->get_results() result.
	 */
	public function batch_get_results() {
		$query = new WP_Comment_Query( $this->args );
		$total_comments = $query->found_comments;
		$this->set_total_num_results( $total_comments );
		return $query->get_comments();
	}

	/**
	 * Clear the result status for a batch.
	 *
	 * @return bool
	 */
	public function batch_clear_result_status() {
		return delete_metadata( 'comment', null, $this->process_id . '_status', '', true );
	}

	/**
	 * Get the status of a result.
	 *
	 * @param \WP_Term $result The result we want to get status of.
	 */
	public function get_result_item_status( $result ) {
		return get_comment_meta( $result->comment_ID, $this->process_id . '_status', true );
	}

	/**
	 * Update the meta info on a result.
	 *
	 * @param \WP_Term $result  The result we want to track meta data on.
	 * @param string   $status  Status of this result in the batch.
	 */
	public function update_result_item_status( $result, $status ) {
		return update_comment_meta( $result->comment_ID, $this->process_id . '_status', $status );
	}
}
