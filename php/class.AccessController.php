<?php

namespace mkdo\members;

/**
 * Class AccessController
 * @package mkdo\members
 */
class AccessController {

	private $login_errors;

	public function __construct( LoginErrors $login_errors ) {
		$this->login_errors = $login_errors;
	}

	public function run() {
		add_action( 'admin_init', array( $this, 'access_control' ) );

		$this->login_errors->run();
	}

	public function access_control() {
		if( is_admin() ) {
			$restricted_roles = get_option( 'mm_admin_restrict', array() );
			$current_user     = wp_get_current_user();
			$roles            = $current_user->roles;

			foreach( $roles as $key => $role ) {
				if( in_array( $role, $restricted_roles ) ) {

					$redirect_url = wp_login_url ('', false );

					wp_logout();
					wp_safe_redirect( $redirect_url . '?error="insufficient-permissions"' );
					exit;
				}
			}
		}
	}
}