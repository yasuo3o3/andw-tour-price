<?php
/**
 * Calendar Controller - カレンダーRESTコントローラー
 *
 * /wp-json/andw/v1/calendar の GET 対応
 *
 * @package Andw_Tour_Price
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Andw_Tour_Price_Calendar_Controller extends WP_REST_Controller {

	public function __construct() {
		$this->namespace = 'andw/v1';
		$this->rest_base = 'calendar';
	}

	public function register_routes() {
		register_rest_route( $this->namespace, '/' . $this->rest_base, array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( $this, 'handle_calendar_request' ),
			'permission_callback' => '__return_true',
			'args'                => $this->get_endpoint_args(),
		) );
	}

	public function get_endpoint_args() {
		return array(
			'tour' => array(
				'default' => 'A1',
				'sanitize_callback' => 'sanitize_text_field',
				'description' => 'ツアーID',
			),
			'month' => array(
				'default' => null,
				'sanitize_callback' => 'sanitize_text_field',
				'description' => '表示月 (YYYY-MM形式)',
			),
			'duration' => array(
				'default' => 4,
				'sanitize_callback' => 'absint',
				'description' => '日数',
			),
			'heatmap' => array(
				'default' => true,
				'sanitize_callback' => 'wp_validate_boolean',
				'description' => 'ヒートマップ表示',
			),
			'show_legend' => array(
				'default' => true,
				'sanitize_callback' => 'wp_validate_boolean',
				'description' => '凡例表示',
			),
			'confirmed_only' => array(
				'default' => false,
				'sanitize_callback' => 'wp_validate_boolean',
				'description' => '確定済みのみ表示',
			),
		);
	}

	public function handle_calendar_request( $request ) {
		try {
			// 既存のメインクラスのメソッドを使用
			$main_instance = new Andw_Tour_Price();
			return $main_instance->rest_calendar_callback( $request );
		} catch ( Exception $e ) {
			return new WP_REST_Response( array(
				'success' => false,
				'message' => $e->getMessage(),
			), 500 );
		}
	}
}