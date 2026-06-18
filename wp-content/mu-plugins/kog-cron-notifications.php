<?php
/**
 * Plugin Name: KOG Cron Notifications
 * Description: Schedules and sends one-time game reminder notifications.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const KOG_TELEGRAM_ALERT_HOOK = 'kog_telegram_alert_event';

add_action( KOG_TELEGRAM_ALERT_HOOK, 'kog_do_send_telegram_alert' );

function kog_load_env_file() {
	$env_file = ABSPATH . 'kog/.env';

	if ( ! file_exists( $env_file ) ) {
		return;
	}

	$lines = file( $env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
	if ( ! is_array( $lines ) ) {
		return;
	}

	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( $line === '' || strpos( $line, '#' ) === 0 || strpos( $line, '=' ) === false ) {
			continue;
		}

		list( $name, $value ) = explode( '=', $line, 2 );
		$name  = trim( $name );
		$value = trim( $value );

		if ( $name !== '' && getenv( $name ) === false ) {
			putenv( "{$name}={$value}" );
			$_ENV[ $name ] = $value;
		}
	}
}

function kog_schedule_game_alert( $game_id ) {
	$game_id = absint( $game_id );
	if ( $game_id <= 0 ) {
		return false;
	}

	$args = array( $game_id );
	if ( wp_next_scheduled( KOG_TELEGRAM_ALERT_HOOK, $args ) ) {
		return true;
	}

	$trigger_time = kog_get_game_alert_timestamp( $game_id );

	return wp_schedule_single_event( $trigger_time, KOG_TELEGRAM_ALERT_HOOK, $args );
}

function kog_clear_game_alert( $game_id ) {
	$game_id = absint( $game_id );
	if ( $game_id <= 0 ) {
		return false;
	}

	return wp_clear_scheduled_hook( KOG_TELEGRAM_ALERT_HOOK, array( $game_id ) );
}

function kog_get_game_alert_timestamp( $game_id ) {
	global $wpdb;

	$start_time = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT start_time FROM {$wpdb->prefix}kog_games WHERE id = %d LIMIT 1",
			$game_id
		)
	);

	$start_time = is_numeric( $start_time ) ? (int) $start_time : 0;
	if ( $start_time > 0 ) {
		return $start_time + HOUR_IN_SECONDS;
	}

	return time() + HOUR_IN_SECONDS;
}

function kog_do_send_telegram_alert( $game_id ) {
	global $wpdb;

	$game_id = absint( $game_id );
	if ( $game_id <= 0 ) {
		return false;
	}

	$status = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT status FROM {$wpdb->prefix}kog_games WHERE id = %d LIMIT 1",
			$game_id
		)
	);

	if ( (string) $status !== '1' ) {
		return true;
	}

	$message = "牌局 GID-{$game_id}: 已进行一个小时！";

	if ( kog_send_telegram_alert_message( $message ) ) {
		error_log( "[KOG Notification] Telegram alert sent successfully for Game ID: {$game_id}" );
		return true;
	}

	error_log( "[KOG Notification] Failed to send Telegram alert for Game ID: {$game_id}" );
	return false;
}

function kog_send_telegram_alert_message( $message ) {
	kog_load_env_file();

	$bot_token = getenv( 'TELEGRAM_BOT_TOKEN' );
	$chat_id   = getenv( 'TELEGRAM_CHAT_ID' );

	if ( empty( $bot_token ) || empty( $chat_id ) ) {
		error_log( '[KOG Notification] Telegram Bot Token or Chat ID is not configured.' );
		return false;
	}

	$response = wp_remote_post(
		"https://api.telegram.org/bot{$bot_token}/sendMessage",
		array(
			'timeout' => 10,
			'body'    => array(
				'chat_id' => $chat_id,
				'text'    => $message,
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		error_log( '[KOG Notification] Telegram request failed: ' . $response->get_error_message() );
		return false;
	}

	$status_code = wp_remote_retrieve_response_code( $response );
	if ( $status_code < 200 || $status_code >= 300 ) {
		error_log( "[KOG Notification] Telegram returned HTTP {$status_code}." );
		return false;
	}

	return true;
}
