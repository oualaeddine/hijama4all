<?php
/**
 * File for Hustle_Dashboard_Admin class.
 *
 * @package Hustle
 * @since unknwon
 */

/**
 * Hustle_Dashboard_Admin.
 */
class Hustle_Dashboard_Admin extends Hustle_Admin_Page_Abstract {

	const WELCOME_MODAL_NAME   = 'welcome_modal';
	const MIGRATE_MODAL_NAME   = 'migrate_modal';
	const HIGHLIGHT_MODAL_NAME = 'release_highlight_modal_446';
	const MIGRATE_NOTICE_NAME  = 'migrate_notice';

	/**
	 * Whether we have any module.
	 *
	 * @since 4.3.5
	 *
	 * @var boolean
	 */
	private $has_modules = false;

	/**
	 * Initiates the page's properties
	 * Should be overridden by each page.
	 *
	 * @since 4.0.1
	 */
	protected function init() {

		$this->page = 'hustle';

		$this->page_title = __( 'Dashboard', 'hustle' );

		$this->page_menu_title = $this->page_title;

		$this->page_capability = 'hustle_menu';

		$this->page_template_path = 'admin/dashboard';
	}

	/**
	 * Actions to be performed on Dashboard page.
	 *
	 * @since 4.0.4
	 */
	public function current_page_loaded() {
		parent::current_page_loaded();
		$this->export_module();
	}

	/**
	 * Register Hustle's parent menu.
	 * Call the parent method to add the submenu page for Dashboard.
	 *
	 * @since 4.0.1
	 */
	public function register_admin_menu() {

		$parent_menu_title = Opt_In_Utils::_is_free() ? __( 'Hustle', 'hustle' ) : __( 'Hustle Pro', 'hustle' );

		// Parent menu.
		add_menu_page(
			$parent_menu_title,
			$parent_menu_title,
			$this->page_capability,
			'hustle',
			array( $this, 'render_main_page' ),
			'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgY2xpcC1wYXRoPSJ1cmwoI2NsaXAwXzFfMikiPgo8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTE3LjUgMjBDMTYuODM3IDIwIDE2LjIwMTEgMTkuNzM2NiAxNS43MzIyIDE5LjI2NzhDMTUuMjYzNCAxOC43OTg5IDE1IDE4LjE2MyAxNSAxNy41QzE1IDE1LjU2MzggMTIuMzQ0MiAxNS4xMTcgOC45OTk2OSAxNS4wMjMxVjE2Ljk5OTlDOS4wMTIxOCAxNy4zOTcyIDguOTQzMTUgMTcuNzkyOSA4Ljc5NjgxIDE4LjE2MjVDOC42NTA0NiAxOC41MzIyIDguNDI5OSAxOC44Njc5IDguMTQ4OCAxOS4xNDlDNy44Njc2OSAxOS40MzAxIDcuNTMxOTcgMTkuNjUwNiA3LjE2MjM1IDE5Ljc5N0M2Ljc5MjcyIDE5Ljk0MzMgNi4zOTcwMyAyMC4wMTI0IDUuOTk5NjkgMTkuOTk5OUM1LjYwMjM0IDIwLjAxMjQgNS4yMDY2NSAxOS45NDMzIDQuODM3MDMgMTkuNzk3QzQuNDY3NCAxOS42NTA2IDQuMTMxNjggMTkuNDMwMSAzLjg1MDU4IDE5LjE0OUMzLjU2OTQ3IDE4Ljg2NzkgMy4zNDg5MSAxOC41MzIyIDMuMjAyNTcgMTguMTYyNUMzLjA1NjIyIDE3Ljc5MjkgMi45ODcyIDE3LjM5NzIgMi45OTk2OSAxNi45OTk5VjE0Ljc4NDZDMi41OTg5NyAxNC42MjcxIDIuMjMxMjggMTQuMzg3OSAxLjkyMTY5IDE0LjA3ODNDMS4zNjcyNiAxMy41MjM5IDEuMDM4NzQgMTIuNzgzMSAwLjk5OTk5NyAxMlYxMS40MTQyQzAuNzkxMDQzIDExLjM0MDMgMC41OTkwMzQgMTEuMjIwNCAwLjQzOTMzMSAxMS4wNjA3QzAuMTU4MDI2IDEwLjc3OTQgMCAxMC4zOTc4IDAgMTBDMCA5LjYwMjE3IDAuMTU4MDI2IDkuMjIwNjQgMC40MzkzMzEgOC45MzkzM0MwLjU5OTAzNCA4Ljc3OTYzIDAuNzkxMDQzIDguNjU5NjYgMC45OTk5OTcgOC41ODU3OFY4QzEuMDM4NzQgNy4yMTY4NyAxLjM2NzI2IDYuNDc2MTIgMS45MjE2OSA1LjkyMTY5QzIuNDc2MTIgNS4zNjcyNiAzLjIxNjg3IDUuMDM4NzQgNCA1SDdDMTEuMzggNSAxNSA0LjgxIDE1IDIuNUMxNSAxLjgzNjk2IDE1LjI2MzQgMS4yMDEwOCAxNS43MzIyIDAuNzMyMjM5QzE2LjIwMTEgMC4yNjMzOTggMTYuODM3IDAgMTcuNSAwQzE4LjE2MyAwIDE4Ljc5ODkgMC4yNjMzOTggMTkuMjY3OCAwLjczMjIzOUMxOS43MzY2IDEuMjAxMDggMjAgMS44MzY5NiAyMCAyLjVWMTcuNUMyMCAxOC4xNjMgMTkuNzM2NiAxOC43OTg5IDE5LjI2NzggMTkuMjY3OEMxOC43OTg5IDE5LjczNjYgMTguMTYzIDIwIDE3LjUgMjBaTTQuOTk5NjkgMTVINi45OTk2OVYxNi45OTk5QzYuOTk5NjkgMTcuMjY1MSA2Ljg5NDMyIDE3LjUxOTQgNi43MDY3OCAxNy43MDdDNi41MTkyNCAxNy44OTQ1IDYuMjY0OSAxNy45OTk5IDUuOTk5NjkgMTcuOTk5OUM1LjczNDQ3IDE3Ljk5OTkgNS40ODAxMyAxNy44OTQ1IDUuMjkyNTkgMTcuNzA3QzUuMTA1MDYgMTcuNTE5NCA0Ljk5OTY5IDE3LjI2NTEgNC45OTk2OSAxNi45OTk5VjE1Wk00IDdDMy43NDcwMyA3LjAzNDczIDMuNTEyMzQgNy4xNTEyNiAzLjMzMTc4IDcuMzMxODJDMy4xNTEyMyA3LjUxMjM3IDMuMDM0NzMgNy43NDcwMyAzIDhWMTJDMy4wMzQ3MyAxMi4yNTMgMy4xNTEyMyAxMi40ODc2IDMuMzMxNzggMTIuNjY4MkMzLjUxMjM0IDEyLjg0ODcgMy43NDcwMyAxMi45NjUzIDQgMTNIN0MxMSAxMyAxNyAxMyAxNyAxNy41QzE3IDE3LjYzMjYgMTcuMDUyNyAxNy43NTk4IDE3LjE0NjQgMTcuODUzNUMxNy4yNDAyIDE3Ljk0NzMgMTcuMzY3NCAxOCAxNy41IDE4QzE3LjYzMjYgMTggMTcuNzU5OCAxNy45NDczIDE3Ljg1MzYgMTcuODUzNUMxNy45NDczIDE3Ljc1OTggMTggMTcuNjMyNiAxOCAxNy41VjIuNUMxOCAyLjM2NzM5IDE3Ljk0NzMgMi4yNDAyMiAxNy44NTM2IDIuMTQ2NDVDMTcuNzU5OCAyLjA1MjY5IDE3LjYzMjYgMiAxNy41IDJDMTcuMzY3NCAyIDE3LjI0MDIgMi4wNTI2OSAxNy4xNDY0IDIuMTQ2NDVDMTcuMDUyNyAyLjI0MDIyIDE3IDIuMzY3MzkgMTcgMi41QzE3IDcgMTEgNyA3IDdINFpNOC41NzAxOCAxMlYxMC4yOUwxMi4wMDAyIDhWOS43MUwxNi4wMDAyIDhMMTAuODYwMiAxMlYxMC4yOUw4LjU3MDE4IDEyWiIgZmlsbD0iI0YwRjZGQyIgZmlsbC1vcGFjaXR5PSIwLjYiLz4KPC9nPgo8ZGVmcz4KPGNsaXBQYXRoIGlkPSJjbGlwMF8xXzIiPgo8cmVjdCB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIGZpbGw9IndoaXRlIi8+CjwvY2xpcFBhdGg+CjwvZGVmcz4KPC9zdmc+Cg=='
		);

		parent::register_admin_menu();
	}

	/**
	 * Get $active variable for get_all method
	 *
	 * @param array  $settings General settings.
	 * @param string $type Module type.
	 * @return boolean|null
	 */
	private function is_getting_active( $settings, $type ) {
		$return = null;

		if ( $settings[ 'published_' . $type . '_on_dashboard' ] && ! $settings[ 'draft_' . $type . '_on_dashboard' ] ) {
			$return = true;
		} elseif ( ! $settings[ 'published_' . $type . '_on_dashboard' ] && $settings[ 'draft_' . $type . '_on_dashboard' ] ) {
			$return = false;
		}

		return $return;
	}

	/**
	 * Get limit from general settings
	 *
	 * @param array  $settings General settings.
	 * @param string $type Module type.
	 * @return string
	 */
	private static function get_limit( $settings, $type ) {
		$limit = $settings[ $type . '_on_dashboard' ];
		if ( $limit < 1 ) {
			$limit = 1;
		}
		return $limit;
	}


	/**
	 * Get the arguments used when rendering the main page.
	 *
	 * @since 4.0.1
	 * @return array
	 */
	public function get_page_template_args() {

		$collection_instance = Hustle_Module_Collection::instance();

		$general_settings = Hustle_Settings_Admin::get_general_settings();
		$modules          = array(
			'popup'          => array(),
			'slidein'        => array(),
			'embedded'       => array(),
			'social_sharing' => array(),
		);
		foreach ( $modules as $type => $instances ) {
			$active = self::is_getting_active( $general_settings, $type );
			$limit  = self::get_limit( $general_settings, $type );

			$modules[ $type ] = $collection_instance->get_all( $active, array( 'module_type' => $type ), $limit );

			if ( ! empty( $modules[ $type ] ) ) {
				$this->has_modules = true;
			}
		}

		$active_modules = $collection_instance->get_all(
			true,
			array(
				'count_only' => true,
			)
		);

		$last_conversion = Hustle_Tracking_Model::get_instance()->get_latest_conversion_date( 'all' );

		return array(
			'metrics'         => $this->get_3_top_metrics(),
			'active_modules'  => $active_modules,
			'popups'          => $modules['popup'],
			'slideins'        => $modules['slidein'],
			'embeds'          => $modules['embedded'],
			'social_sharings' => $modules['social_sharing'],
			'last_conversion' => $last_conversion ? date_i18n( 'j M Y @ H:i A', strtotime( $last_conversion ) ) : __( 'Never', 'hustle' ),
			'sui'             => $this->get_sui_summary_config(),
		);
	}

	/**
	 * Add data to the current json array.
	 *
	 * @since 4.3.1
	 *
	 * @return array
	 */
	protected function get_vars_to_localize() {
		$current_array = parent::get_vars_to_localize();

		// Register translated strings for datepicker preview.
		$current_array['messages']['days_and_months'] = array(
			'days_full'    => Hustle_Time_Helper::get_week_days(),
			'days_short'   => Hustle_Time_Helper::get_week_days( 'short' ),
			'days_min'     => Hustle_Time_Helper::get_week_days( 'min' ),
			'months_full'  => Hustle_Time_Helper::get_months(),
			'months_short' => Hustle_Time_Helper::get_months( 'short' ),
		);

		// Also defined in listing.
		$current_array['single_module_action_nonce'] = wp_create_nonce( 'hustle_single_action' );

		return $current_array;
	}

	/**
	 * Get the data for listing the ssharing modules conversions per page.
	 *
	 * @since 4.0.0
	 * @param int $limit Limit for retrieving the sshare data.
	 * @return array
	 */
	private function get_sshare_per_page_conversions( $limit ) {

		$tracking_model = Hustle_Tracking_Model::get_instance();
		$tracking_data  = $tracking_model->get_ssharing_per_page_conversion_count( $limit );

		$data_array = array();
		foreach ( $tracking_data as $data ) {

			if ( '0' !== $data->page_id ) {
				$title = get_the_title( $data->page_id );
				$url   = get_permalink( $data->page_id );
			} else {
				$title = get_bloginfo( 'name', 'display' );
				$url   = get_home_url();
			}

			if ( empty( $url ) ) {
				continue;
			}
			$data_array[] = array(
				'title' => $title,
				'url'   => $url,
				'count' => $data->tracked_count,
			);
		}

		return $data_array;
	}

	/**
	 * Get 3 Top Metrics
	 *
	 * @since 4.0.0
	 *
	 * @return array $data Array of 4 top metrics.
	 */
	private function get_3_top_metrics() {
		global $hustle;
		$names   = array(
			'average_conversion_rate' => __( 'Average Conversion Rate', 'hustle' ),
			'total_conversions'       => __( 'Total Conversions', 'hustle' ),
			'most_conversions'        => __( 'Most Conversions', 'hustle' ),
			'today_conversions'       => __( 'Today\'s Conversion', 'hustle' ),
			'last_week_conversions'   => __( 'Last 7 Day\'s Conversion', 'hustle' ),
			'last_month_conversions'  => __( 'Last 1 Month\'s Conversion', 'hustle' ),
			'inactive_modules_count'  => __( 'Inactive Modules', 'hustle' ),
			'total_modules_count'     => __( 'Total Modules', 'hustle' ),
		);
		$keys    = array_keys( $names );
		$metrics = Hustle_Settings_Admin::get_top_metrics_settings();
		$metrics = array_values( array_intersect( $keys, $metrics ) );

		$metrics_count = count( $metrics );

		while ( 3 > $metrics_count ) {
			$key = array_shift( $keys );
			if ( ! in_array( $key, $metrics, true ) ) {
				$metrics[] = $key;
			}
			$metrics_count = count( $metrics );
		}
		$data            = array();
		$tracking        = Hustle_Tracking_Model::get_instance();
		$module_instance = Hustle_Module_Collection::instance();
		foreach ( $metrics as $key ) {

			switch ( $key ) {
				case 'average_conversion_rate':
					$value = $tracking->get_average_conversion_rate();
					break;
				case 'total_conversions':
					$value = $tracking->get_total_conversions();
					break;
				case 'most_conversions':
					$module_id = $tracking->get_most_conversions_module_id();
					if ( ! $module_id ) {
						$value = __( 'None', 'hustle' );
						break;
					}
					$module = new Hustle_Module_Model( $module_id );
					if ( ! is_wp_error( $module ) ) {
						$value = $module->module_name;
						$url   = add_query_arg( 'page', $module->get_wizard_page() );
						if ( ! empty( $url ) ) {
							$url   = add_query_arg( 'id', $module->module_id, $url );
							$value = sprintf(
								'<a href="%s">%s</a>',
								esc_url( $url ),
								esc_html( $value )
							);
						}
					}
					break;
				case 'today_conversions':
					$value = $tracking->get_today_conversions();
					break;
				case 'last_week_conversions':
					$value = $tracking->get_last_week_conversions();
					break;
				case 'last_month_conversions':
					$value = $tracking->get_last_month_conversions();
					break;
				case 'inactive_modules_count':
					$value = $module_instance->get_all( false, array( 'count_only' => true ) );
					break;
				case 'total_modules_count':
					$value = $module_instance->get_all( 'any', array( 'count_only' => true ) );
					break;
				default:
					$value = __( 'Unknown', 'hustle' );
			}
			if ( 0 === $value ) {
				$value = __( 'None', 'hustle' );
			}
			$data[ $key ] = array(
				'label' => $names[ $key ],
				'value' => $value,
			);
		}
		return $data;
	}

	/**
	 * Renders the modals for the Dashboard page.
	 *
	 * @since 4.3.5
	 *
	 * @todo Move global modals to the base abstract page.
	 *
	 * @return void
	 */
	protected function render_modals() {
		parent::render_modals();

		$needs_migration = Hustle_Migration::check_tracking_needs_migration();

		// On Boarding (Welcome).
		if (
			filter_input( INPUT_GET, 'show-welcome', FILTER_VALIDATE_BOOLEAN ) ||
			( ! $this->has_modules && ! Hustle_Notifications::was_notification_dismissed( self::WELCOME_MODAL_NAME ) )
		) {
			$this->get_renderer()->render( 'admin/dashboard/dialogs/fresh-install' );
		}

		// Migration.
		if (
			filter_input( INPUT_GET, 'show-migrate', FILTER_VALIDATE_BOOLEAN ) ||
			( $needs_migration && ! Hustle_Notifications::was_notification_dismissed( self::MIGRATE_MODAL_NAME ) )
		) {
			$this->get_renderer()->render( 'admin/dashboard/dialogs/migrate-data' );
		}

		// Release highlights.
		if ( $this->should_show_highlight_modal( $needs_migration ) ) {
			$this->get_renderer()->render( 'admin/dashboard/dialogs/release-highlight' );
		}

		// Dissmiss migrate tracking notice modal confirmation.
		if ( Hustle_Notifications::is_show_migrate_tracking_notice() ) {
			$this->get_renderer()->render( 'admin/dialogs/migrate-dismiss-confirmation' );
		}

		// Visibility behavior updated.
		if (
			filter_input( INPUT_GET, 'review-conditions', FILTER_VALIDATE_BOOLEAN ) &&
			Hustle_Migration::is_migrated( 'hustle_40_migrated' ) &&
			! Hustle_Notifications::was_notification_dismissed( '41_visibility_behavior_update' )
		) {
			$this->get_renderer()->render( 'admin/dashboard/dialogs/review-conditions' );
		}

		// Delete.
		$this->get_renderer()->render( 'admin/commons/sui-listing/dialogs/delete-module' );
	}

	/**
	 * Returns whether the highlight modal should show up, enqueues scripts, or dismiss otherwise.
	 *
	 * @todo Separate the check from the enqueueing and the dismissal.
	 * @todo Retrieve $has_modules and $need_migrate from here, instead of recieving them as params.
	 *
	 * @since 4.3.0
	 *
	 * @param boolean $need_migrate Whether the metas migration from 3.x to 4.x is pending.
	 * @return boolean
	 */
	private function should_show_highlight_modal( $need_migrate ) {

		$is_force_highlight      = filter_input( INPUT_GET, 'show-release-highlight', FILTER_VALIDATE_BOOLEAN );
		$was_highlight_dismissed = Hustle_Notifications::was_notification_dismissed( self::HIGHLIGHT_MODAL_NAME );

		if ( ! $was_highlight_dismissed || $is_force_highlight ) {

			// Only display when it's not a fresh install and no 3.x to 4.x migration is needed.
			// Check for $previous_installed_version in 4.2.1. For now, we assume that if there are modules it's not a fresh install.
			if ( $is_force_highlight || ( $this->has_modules && ! $need_migrate ) ) {
				return true;
			} else {
				// Fresh install or focus on migration. Dismiss the notification.
				Hustle_Notifications::add_dismissed_notification( self::HIGHLIGHT_MODAL_NAME );
			}
		}
		return false;
	}
}
