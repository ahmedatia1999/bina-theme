<?php
/**
 * Payment gateway abstraction + mock gateway for local end-to-end testing.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bina_payments_table_name() {
	global $wpdb;
	return $wpdb->prefix . 'bina_payment_transactions';
}

function bina_payments_maybe_install() {
	$ver = get_option( 'bina_payments_db_version', '0' );
	if ( $ver === '1' ) {
		return;
	}

	global $wpdb;
	$table           = bina_payments_table_name();
	$charset_collate = $wpdb->get_charset_collate();
	$sql             = "CREATE TABLE {$table} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		flow_type varchar(16) NOT NULL DEFAULT 'payin',
		object_type varchar(24) NOT NULL DEFAULT 'milestone',
		object_id bigint(20) unsigned NOT NULL DEFAULT 0,
		user_id bigint(20) unsigned NOT NULL DEFAULT 0,
		gateway_key varchar(24) NOT NULL DEFAULT 'mock',
		status varchar(16) NOT NULL DEFAULT 'pending',
		amount decimal(12,2) NOT NULL DEFAULT 0.00,
		currency varchar(8) NOT NULL DEFAULT 'SAR',
		gateway_ref varchar(190) NOT NULL DEFAULT '',
		redirect_token varchar(64) NOT NULL DEFAULT '',
		meta longtext NULL,
		created_at datetime NOT NULL,
		updated_at datetime NOT NULL,
		PRIMARY KEY (id),
		UNIQUE KEY redirect_token (redirect_token),
		KEY object_flow (object_type, object_id, flow_type),
		KEY user_flow (user_id, flow_type, created_at),
		KEY status_created (status, created_at)
	) {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	update_option( 'bina_payments_db_version', '1', false );
}
add_action( 'init', 'bina_payments_maybe_install', 3 );

function bina_payments_gateway_key() {
	$key = apply_filters( 'bina_payments_gateway_key', 'mock' );
	$key = sanitize_key( (string) $key );
	return $key !== '' ? $key : 'mock';
}

function bina_payments_is_mock_gateway() {
	return bina_payments_gateway_key() === 'mock';
}

function bina_payment_tx_create( $args ) {
	global $wpdb;

	$table          = bina_payments_table_name();
	$flow_type      = sanitize_key( (string) ( $args['flow_type'] ?? 'payin' ) );
	$object_type    = sanitize_key( (string) ( $args['object_type'] ?? 'milestone' ) );
	$object_id      = (int) ( $args['object_id'] ?? 0 );
	$user_id        = (int) ( $args['user_id'] ?? 0 );
	$gateway_key    = sanitize_key( (string) ( $args['gateway_key'] ?? bina_payments_gateway_key() ) );
	$status         = sanitize_key( (string) ( $args['status'] ?? 'pending' ) );
	$amount         = round( (float) ( $args['amount'] ?? 0 ), 2 );
	$currency       = strtoupper( sanitize_text_field( (string) ( $args['currency'] ?? 'SAR' ) ) );
	$gateway_ref    = sanitize_text_field( (string) ( $args['gateway_ref'] ?? '' ) );
	$redirect_token = sanitize_text_field( (string) ( $args['redirect_token'] ?? wp_generate_password( 32, false, false ) ) );
	$meta           = isset( $args['meta'] ) ? wp_json_encode( $args['meta'] ) : null;
	$now            = current_time( 'mysql' );

	if ( $object_id < 1 || $user_id < 1 || $amount <= 0 ) {
		return new WP_Error( 'bina_payment_bad', __( 'Payment transaction data is invalid.', 'bina' ) );
	}

	$ok = $wpdb->insert(
		$table,
		array(
			'flow_type'      => $flow_type,
			'object_type'    => $object_type,
			'object_id'      => $object_id,
			'user_id'        => $user_id,
			'gateway_key'    => $gateway_key,
			'status'         => $status,
			'amount'         => $amount,
			'currency'       => $currency,
			'gateway_ref'    => $gateway_ref,
			'redirect_token' => $redirect_token,
			'meta'           => $meta,
			'created_at'     => $now,
			'updated_at'     => $now,
		),
		array( '%s', '%s', '%d', '%d', '%s', '%s', '%f', '%s', '%s', '%s', '%s', '%s', '%s' )
	);

	if ( ! $ok ) {
		return new WP_Error( 'bina_payment_db', __( 'Unable to create payment transaction.', 'bina' ) );
	}

	return (int) $wpdb->insert_id;
}

function bina_payment_tx_get_by_token( $token ) {
	global $wpdb;

	$table = bina_payments_table_name();
	$token = sanitize_text_field( (string) $token );
	if ( $token === '' ) {
		return null;
	}

	$row = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$table} WHERE redirect_token = %s LIMIT 1",
			$token
		),
		ARRAY_A
	);

	return is_array( $row ) ? $row : null;
}

function bina_payment_tx_get_by_object( $flow_type, $object_type, $object_id, $status = 'pending' ) {
	global $wpdb;

	$table       = bina_payments_table_name();
	$flow_type   = sanitize_key( (string) $flow_type );
	$object_type = sanitize_key( (string) $object_type );
	$object_id   = (int) $object_id;
	$status      = sanitize_key( (string) $status );
	if ( $object_id < 1 ) {
		return null;
	}

	$row = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$table}
			 WHERE flow_type = %s AND object_type = %s AND object_id = %d AND status = %s
			 ORDER BY id DESC
			 LIMIT 1",
			$flow_type,
			$object_type,
			$object_id,
			$status
		),
		ARRAY_A
	);

	return is_array( $row ) ? $row : null;
}

/**
 * Fetch payment transactions for admin table.
 *
 * @param array<string,mixed> $args
 * @return array<int,array<string,mixed>>
 */
function bina_payment_tx_fetch_for_admin( $args = array() ) {
	global $wpdb;

	$table      = bina_payments_table_name();
	$flow_type  = isset( $args['flow_type'] ) ? sanitize_key( (string) $args['flow_type'] ) : '';
	$object_type = isset( $args['object_type'] ) ? sanitize_key( (string) $args['object_type'] ) : '';
	$status     = isset( $args['status'] ) ? sanitize_key( (string) $args['status'] ) : '';
	$limit      = isset( $args['limit'] ) ? (int) $args['limit'] : 100;
	$limit      = max( 1, min( 500, $limit ) );

	$where   = array();
	$params  = array();
	if ( $flow_type !== '' ) {
		$where[]  = 'flow_type = %s';
		$params[] = $flow_type;
	}
	if ( $object_type !== '' ) {
		$where[]  = 'object_type = %s';
		$params[] = $object_type;
	}
	if ( $status !== '' ) {
		$where[]  = 'status = %s';
		$params[] = $status;
	}

	$sql = "SELECT id, flow_type, object_type, object_id, user_id, gateway_key, status, amount, currency, gateway_ref, created_at, updated_at
		FROM {$table}";
	if ( ! empty( $where ) ) {
		$sql .= ' WHERE ' . implode( ' AND ', $where );
	}
	$sql .= ' ORDER BY id DESC LIMIT %d';
	$params[] = $limit;

	$rows = $wpdb->get_results( $wpdb->prepare( $sql, $params ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	return is_array( $rows ) ? $rows : array();
}

function bina_payment_tx_update( $tx_id, $data ) {
	global $wpdb;

	$table = bina_payments_table_name();
	$tx_id = (int) $tx_id;
	if ( $tx_id < 1 || ! is_array( $data ) || empty( $data ) ) {
		return false;
	}

	$data['updated_at'] = current_time( 'mysql' );
	if ( isset( $data['meta'] ) && is_array( $data['meta'] ) ) {
		$data['meta'] = wp_json_encode( $data['meta'] );
	}

	return false !== $wpdb->update( $table, $data, array( 'id' => $tx_id ) );
}

function bina_withdraw_request_get_by_id( $withdraw_request_id ) {
	global $wpdb;

	$table               = function_exists( 'bina_wallet_withdrawals_table_name' ) ? bina_wallet_withdrawals_table_name() : '';
	$withdraw_request_id = (int) $withdraw_request_id;
	if ( $table === '' || $withdraw_request_id < 1 ) {
		return null;
	}

	$row = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$table} WHERE id = %d LIMIT 1",
			$withdraw_request_id
		),
		ARRAY_A
	);

	return is_array( $row ) ? $row : null;
}

function bina_withdraw_request_mark_status( $withdraw_request_id, $status, $admin_note = '' ) {
	global $wpdb;

	$table               = function_exists( 'bina_wallet_withdrawals_table_name' ) ? bina_wallet_withdrawals_table_name() : '';
	$withdraw_request_id = (int) $withdraw_request_id;
	$status              = sanitize_key( (string) $status );
	$admin_note          = sanitize_text_field( (string) $admin_note );
	if ( $table === '' || $withdraw_request_id < 1 || $status === '' ) {
		return false;
	}

	return false !== $wpdb->update(
		$table,
		array(
			'status'     => $status,
			'admin_note' => $admin_note,
			'updated_at' => current_time( 'mysql' ),
		),
		array( 'id' => $withdraw_request_id ),
		array( '%s', '%s', '%s' ),
		array( '%d' )
	);
}

function bina_milestone_mark_funded_via_gateway( $milestone_id, $payment_tx_id, $gateway_ref = '' ) {
	$milestone_id = (int) $milestone_id;
	$payment_tx_id = (int) $payment_tx_id;
	$gateway_ref  = sanitize_text_field( (string) $gateway_ref );
	if ( $milestone_id < 1 || $payment_tx_id < 1 ) {
		return new WP_Error( 'bina_payment_bad', __( 'Invalid payment confirmation.', 'bina' ) );
	}

	$row = function_exists( 'bina_milestone_get_by_id' ) ? bina_milestone_get_by_id( $milestone_id ) : null;
	if ( ! is_array( $row ) ) {
		return new WP_Error( 'bina_payment_milestone', __( 'Milestone not found.', 'bina' ) );
	}

	$status = (string) ( $row['status'] ?? '' );
	if ( in_array( $status, array( 'funded', 'submitted', 'approved', 'released' ), true ) ) {
		return true;
	}
	if ( ! in_array( $status, array( 'scheduled', 'payment_requested' ), true ) ) {
		return new WP_Error( 'bina_payment_state', __( 'Milestone is not ready for funding.', 'bina' ) );
	}

	$project_id    = (int) ( $row['project_id'] ?? 0 );
	$provider_id   = (int) ( $row['provider_id'] ?? 0 );
	$proposal_id   = (int) ( $row['proposal_id'] ?? 0 );
	$milestone_no  = (int) ( $row['milestone_no'] ?? 0 );
	$financials    = function_exists( 'bina_milestone_get_financials' ) ? bina_milestone_get_financials( $row ) : array();
	$base_amount   = isset( $financials['base_amount'] ) ? (float) $financials['base_amount'] : (float) ( $row['amount'] ?? 0 );
	$provider_net  = isset( $financials['provider_net'] ) ? (float) $financials['provider_net'] : $base_amount;

	if ( $provider_id < 1 || $base_amount <= 0 || $provider_net <= 0 ) {
		return new WP_Error( 'bina_payment_amount', __( 'Milestone payment values are invalid.', 'bina' ) );
	}

	$u = bina_milestone_update_status( $milestone_id, 'funded' );
	if ( is_wp_error( $u ) ) {
		return $u;
	}

	$led = bina_wallet_ledger_add(
		array(
			'user_id'        => $provider_id,
			'project_id'     => $project_id,
			'proposal_id'    => $proposal_id,
			'milestone_no'   => $milestone_no,
			'entry_type'     => 'gateway_funded',
			'amount'         => (float) $provider_net,
			'balance_bucket' => 'pending',
			'note'           => 'gateway funded milestone',
			'meta'           => array(
				'milestone_id' => $milestone_id,
				'payment_tx_id' => $payment_tx_id,
				'gateway_ref'  => $gateway_ref,
				'base_amount'  => $base_amount,
				'provider_net' => $provider_net,
			),
		)
	);

	if ( is_wp_error( $led ) ) {
		bina_milestone_update_status( $milestone_id, 'scheduled' );
		return $led;
	}

	return true;
}

function bina_payment_start_mock_milestone_checkout( $milestone_id, $customer_id, $return_url = '' ) {
	$milestone_id = (int) $milestone_id;
	$customer_id  = (int) $customer_id;
	$return_url   = esc_url_raw( (string) $return_url );
	if ( $milestone_id < 1 || $customer_id < 1 ) {
		return new WP_Error( 'bina_payment_bad', __( 'Invalid payment request.', 'bina' ) );
	}

	$row = function_exists( 'bina_milestone_get_by_id' ) ? bina_milestone_get_by_id( $milestone_id ) : null;
	if ( ! is_array( $row ) ) {
		return new WP_Error( 'bina_payment_milestone', __( 'Milestone not found.', 'bina' ) );
	}

	$project_id = (int) ( $row['project_id'] ?? 0 );
	$post       = get_post( $project_id );
	if ( ! $post || (int) $post->post_author !== $customer_id ) {
		return new WP_Error( 'bina_payment_auth', __( 'You cannot pay for this milestone.', 'bina' ) );
	}

	$status = (string) ( $row['status'] ?? '' );
	if ( ! in_array( $status, array( 'scheduled', 'payment_requested' ), true ) ) {
		return new WP_Error( 'bina_payment_state', __( 'This milestone is not available for payment now.', 'bina' ) );
	}

	$existing = bina_payment_tx_get_by_object( 'payin', 'milestone', $milestone_id, 'pending' );
	if ( is_array( $existing ) && ! empty( $existing['redirect_token'] ) ) {
		return array(
			'transaction_id' => (int) $existing['id'],
			'redirect_url'   => add_query_arg(
				array(
					'bina_mock_checkout' => 1,
					'token'              => rawurlencode( (string) $existing['redirect_token'] ),
				),
				home_url( '/' )
			),
		);
	}

	$financials     = function_exists( 'bina_milestone_get_financials' ) ? bina_milestone_get_financials( $row ) : array();
	$customer_total = isset( $financials['customer_total'] ) ? (float) $financials['customer_total'] : (float) ( $row['amount'] ?? 0 );
	$token          = wp_generate_password( 32, false, false );

	if ( $status === 'scheduled' ) {
		bina_milestone_update_status( $milestone_id, 'payment_requested' );
	}

	$tx_id = bina_payment_tx_create(
		array(
			'flow_type'      => 'payin',
			'object_type'    => 'milestone',
			'object_id'      => $milestone_id,
			'user_id'        => $customer_id,
			'gateway_key'    => 'mock',
			'status'         => 'pending',
			'amount'         => $customer_total,
			'currency'       => 'SAR',
			'redirect_token' => $token,
			'meta'           => array(
				'return_url' => $return_url !== '' ? $return_url : get_permalink( $project_id ),
				'project_id' => $project_id,
				'financials' => $financials,
			),
		)
	);

	if ( is_wp_error( $tx_id ) ) {
		if ( $status === 'scheduled' ) {
			bina_milestone_update_status( $milestone_id, 'scheduled' );
		}
		return $tx_id;
	}

	return array(
		'transaction_id' => (int) $tx_id,
		'redirect_url'   => add_query_arg(
			array(
				'bina_mock_checkout' => 1,
				'token'              => rawurlencode( $token ),
			),
			home_url( '/' )
		),
	);
}

function bina_payment_process_mock_withdrawal( $withdraw_request_id ) {
	$withdraw_request_id = (int) $withdraw_request_id;
	if ( $withdraw_request_id < 1 ) {
		return new WP_Error( 'bina_withdraw_bad', __( 'Invalid withdrawal request.', 'bina' ) );
	}

	$request = bina_withdraw_request_get_by_id( $withdraw_request_id );
	if ( ! is_array( $request ) ) {
		return new WP_Error( 'bina_withdraw_missing', __( 'Withdrawal request not found.', 'bina' ) );
	}
	if ( (string) ( $request['status'] ?? '' ) !== 'pending' ) {
		return true;
	}

	$tx_id = bina_payment_tx_create(
		array(
			'flow_type'      => 'payout',
			'object_type'    => 'withdrawal',
			'object_id'      => $withdraw_request_id,
			'user_id'        => (int) ( $request['user_id'] ?? 0 ),
			'gateway_key'    => 'mock',
			'status'         => 'completed',
			'amount'         => (float) ( $request['amount'] ?? 0 ),
			'currency'       => 'SAR',
			'gateway_ref'    => 'mock_payout_' . $withdraw_request_id,
			'meta'           => array(
				'payout_method' => (string) ( $request['payout_method'] ?? 'bank' ),
			),
		)
	);

	if ( is_wp_error( $tx_id ) ) {
		return $tx_id;
	}

	if ( ! bina_withdraw_request_mark_status( $withdraw_request_id, 'paid', 'mock payout completed' ) ) {
		return new WP_Error( 'bina_withdraw_status', __( 'Unable to complete withdrawal test.', 'bina' ) );
	}

	return $tx_id;
}

function bina_mock_gateway_redirect_back( $tx, $state ) {
	$meta       = isset( $tx['meta'] ) ? json_decode( (string) $tx['meta'], true ) : array();
	$return_url = is_array( $meta ) && ! empty( $meta['return_url'] ) ? esc_url_raw( (string) $meta['return_url'] ) : home_url( '/' );
	$return_url = add_query_arg(
		array(
			'bina_mock_payment' => sanitize_key( (string) $state ),
			'payment_tx'        => (int) ( $tx['id'] ?? 0 ),
		),
		$return_url
	);
	wp_safe_redirect( $return_url );
	exit;
}

function bina_mock_gateway_render_page( $tx, $error_message = '' ) {
	$meta        = isset( $tx['meta'] ) ? json_decode( (string) $tx['meta'], true ) : array();
	$amount      = isset( $tx['amount'] ) ? (float) $tx['amount'] : 0.0;
	$financials  = is_array( $meta ) && ! empty( $meta['financials'] ) && is_array( $meta['financials'] ) ? $meta['financials'] : array();
	$base_amount = isset( $financials['base_amount'] ) ? (float) $financials['base_amount'] : 0.0;
	$fee_amount  = isset( $financials['customer_fee'] ) ? (float) $financials['customer_fee'] : max( 0, $amount - $base_amount );
	$title       = 'Binaa Mock Checkout';
	$pay_url     = add_query_arg(
		array(
			'bina_mock_checkout' => 1,
			'token'              => rawurlencode( (string) ( $tx['redirect_token'] ?? '' ) ),
			'decision'           => 'success',
		),
		home_url( '/' )
	);
	$fail_url = add_query_arg(
		array(
			'bina_mock_checkout' => 1,
			'token'              => rawurlencode( (string) ( $tx['redirect_token'] ?? '' ) ),
			'decision'           => 'fail',
		),
		home_url( '/' )
	);

	status_header( 200 );
	nocache_headers();
	?>
	<!doctype html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo esc_html( $title ); ?></title>
		<style>
			body{font-family:Arial,sans-serif;background:#f6f1eb;margin:0;padding:24px;color:#1f2937}
			.card{max-width:720px;margin:40px auto;background:#fff;border:1px solid #eadfd1;border-radius:18px;padding:24px;box-shadow:0 18px 45px rgba(0,0,0,.08)}
			.grid{display:grid;gap:10px}
			.row{display:flex;justify-content:space-between;gap:16px;padding:12px 0;border-bottom:1px solid #f1e7db}
			.actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:24px}
			.btn{display:inline-flex;align-items:center;justify-content:center;padding:12px 18px;border-radius:12px;text-decoration:none;font-weight:700}
			.btn-primary{background:#9a4529;color:#fff}
			.btn-muted{background:#f3f4f6;color:#111827}
			.note{margin-top:16px;padding:12px 14px;background:#fff8e8;border:1px solid #f0dfab;border-radius:12px}
			.err{margin-top:16px;padding:12px 14px;background:#fef2f2;border:1px solid #fecaca;border-radius:12px;color:#991b1b}
		</style>
	</head>
	<body>
		<div class="card">
			<h1 style="margin:0 0 12px;"><?php echo esc_html( $title ); ?></h1>
			<p style="margin:0 0 20px;">Test checkout page for milestone funding.</p>
			<div class="grid">
				<div class="row"><span>Milestone amount</span><strong><?php echo esc_html( number_format_i18n( $base_amount, 2 ) ); ?> SAR</strong></div>
				<div class="row"><span>Customer fee (1%)</span><strong><?php echo esc_html( number_format_i18n( $fee_amount, 2 ) ); ?> SAR</strong></div>
				<div class="row" style="border-bottom:0;"><span>Total charged</span><strong><?php echo esc_html( number_format_i18n( $amount, 2 ) ); ?> SAR</strong></div>
			</div>
			<div class="note">Use this page to simulate a successful or failed payment before selecting the final gateway.</div>
			<?php if ( $error_message !== '' ) : ?>
				<div class="err"><?php echo esc_html( $error_message ); ?></div>
			<?php endif; ?>
			<div class="actions">
				<a class="btn btn-primary" href="<?php echo esc_url( $pay_url ); ?>">Simulate success</a>
				<a class="btn btn-muted" href="<?php echo esc_url( $fail_url ); ?>">Simulate failure</a>
			</div>
		</div>
	</body>
	</html>
	<?php
	exit;
}

function bina_mock_gateway_router() {
	if ( empty( $_GET['bina_mock_checkout'] ) ) {
		return;
	}

	$token = isset( $_GET['token'] ) ? sanitize_text_field( wp_unslash( $_GET['token'] ) ) : '';
	$tx    = bina_payment_tx_get_by_token( $token );
	if ( ! is_array( $tx ) ) {
		status_header( 404 );
		wp_die( esc_html__( 'Mock payment transaction not found.', 'bina' ) );
	}

	$decision = isset( $_GET['decision'] ) ? sanitize_key( wp_unslash( $_GET['decision'] ) ) : '';
	if ( $decision === 'success' ) {
		if ( (string) ( $tx['status'] ?? '' ) !== 'completed' ) {
			$r = bina_milestone_mark_funded_via_gateway(
				(int) ( $tx['object_id'] ?? 0 ),
				(int) ( $tx['id'] ?? 0 ),
				'mock_success_' . (int) ( $tx['id'] ?? 0 )
			);
			if ( is_wp_error( $r ) ) {
				bina_mock_gateway_render_page( $tx, $r->get_error_message() );
			}
			bina_payment_tx_update(
				(int) $tx['id'],
				array(
					'status'      => 'completed',
					'gateway_ref' => 'mock_success_' . (int) $tx['id'],
				)
			);
			$tx['status'] = 'completed';
		}
		bina_mock_gateway_redirect_back( $tx, 'success' );
	}

	if ( $decision === 'fail' ) {
		if ( (string) ( $tx['status'] ?? '' ) === 'pending' ) {
			bina_payment_tx_update( (int) $tx['id'], array( 'status' => 'failed', 'gateway_ref' => 'mock_failed_' . (int) $tx['id'] ) );
			if ( (string) ( $tx['object_type'] ?? '' ) === 'milestone' ) {
				$milestone_id = (int) ( $tx['object_id'] ?? 0 );
				$row          = function_exists( 'bina_milestone_get_by_id' ) ? bina_milestone_get_by_id( $milestone_id ) : null;
				if ( is_array( $row ) && (string) ( $row['status'] ?? '' ) === 'payment_requested' ) {
					bina_milestone_update_status( $milestone_id, 'scheduled' );
				}
			}
			$tx['status'] = 'failed';
		}
		bina_mock_gateway_redirect_back( $tx, 'failed' );
	}

	bina_mock_gateway_render_page( $tx );
}
add_action( 'template_redirect', 'bina_mock_gateway_router', 1 );
