<?php

use \WP_CLI\Utils;

abstract class EIG_WP_CLI_Command extends WP_CLI_Command {
	/**
	 * Helper to format 'key' => 'value' arrays into ASCII tables
	 *
	 * 1. Provide $data as an array or object
	 * 2. Provide $keys as two strings -- by default 'DETAIL' and 'VALUE' are used.
	 * 3. Prints ASCII Table
	 *
	 * @param array $data
	 * @param array $keys
	 */
	protected function simple_ascii_table( $data, $keys = array( 'DETAIL', 'VALUE' ) ) {
		if ( empty( $data ) ) {
			return;
		}

		$items = array();

		foreach ( $data as $detail => $value ) {
			$items[] = array(
				$keys[0] => $detail,
				$keys[1] => $value,
			);
		}

		Utils\format_items( 'table', $items, $keys );
	}

	/**
	 * @param string $label
	 * @param string $emoji
	 */
	protected function bold_heading( $label, $emoji = '' ) {
		$this->colorize(  $label, '4', 'W', $emoji );
	}

	/**
	 * @param string $label
	 */
	protected function success( $label, $silent = false ) {
		$pre_ = $silent ? '' : 'Error: ';
		$this->colorize( $pre_ . $label, '2', 'W', '✅' );
	}

	/**
	 * @param string $label
	 */
	protected function info( $label ) {
		$this->colorize( $label, '4', 'W',  'ℹ️' );
	}

	/**
	 * @param string $label
	 */
	protected function warning( $label ) {
		$this->colorize( $label, '3', 'W', '⚠️' );
	}

	/**
	 * @param string $label
	 */
	protected function error( $label, $silent = false ) {
		$pre_ = $silent ? '' : 'Error: ';
		$this->colorize( $pre_ . $label, '1', 'W', '🛑️' );
	}

	/**
	 * @param string $label
	 * @param string $background
	 * @param string $text_color
	 * @param string $emoji_prefix
	 */
	protected function colorize( $label = '', $background = '', $text_color = '%_', $emoji_prefix = '' ) {
		if ( ! empty( $background ) ) {
			$background = '%' . $background;
		}

		if( ! empty( $text_color ) && false === strpos( $text_color, '%' ) ) {
			$text_color = '%' . $text_color;
		}

		if ( ! empty( $emoji_prefix ) ) {
			$label = $emoji_prefix . '  ' . $label;
		}

		WP_CLI::log( WP_CLI::colorize( $background . $text_color . $label . '%n' ) );
	}

	/**
	 *
	 */
	protected function new_line() {
		WP_CLI::log( __return_empty_string() );
	}

	/**
	 * @param $question
	 * @param string $type
	 */
	protected function confirm( $question, $type = 'normal' ) {
		switch( $type ) {
			case 'omg':
				WP_CLI::confirm( $this->warning( '☢ 🙊 🙈 🙊 ☢️  ' . $question ) );
				break;
			case 'red':
				WP_CLI::confirm( $this->error( $question, true ) );
				break;
			case 'yellow':
				WP_CLI::confirm( $this->warning( $question ) );
				break;
			case 'green':
				WP_CLI::confirm( $this->success( $question ) );
				break;
			case 'normal':
			default:
				WP_CLI::confirm( $question );
				break;
		}
	}
}