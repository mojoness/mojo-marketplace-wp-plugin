<?php

/**
 * Class Bluehost_CLI_Digest
 *
 * This class is instantiated in /inc/cli-init.php
 */
class Bluehost_CLI_Digest extends EIG_WP_CLI_Command {
	public function logo( $a, $ar ) {
		$this->bluehost_logo();
	}
	/**
	 * Display digest of information about site to quickly understand config, versions and other vital info.
	 *
	 * @param  null $args Unused.
	 * @param  array $assoc_args Unused.
	 */
	public function digest( $args, $assoc_args ) {
		$type = in_array( 'full', $assoc_args ) ? 'full' : 'standard';
		if( in_array( '--full-json', $assoc_args ) ) {
			$data = array(
				$this->eig_digest_basic(),
				$this->eig_digest_environment(),
				$this->eig_digest_plugins_and_themes(),
				$this->eig_digest_core_content($type),
				$this->eig_digest_users(),
			);
			$json = json_encode( $data );
			WP_CLI::log($json);
			exit;
		}
		$type = in_array( 'full', $assoc_args ) ? 'full' : 'standard';
		if ( 'full' === $type && ! isset( $assoc_args['noprompt'] ) ) {
			$this->confirm( 'This can strain large databases. Proceed?', 'yellow' );
			WP_CLI::log( 'Creating digest...' );
		} elseif ( ! isset( $assoc_args['noprompt'] ) ) {
			WP_CLI::log( 'Creating digest...' );
			$this->clrline( 'For complete digest, add --full flag to this command.', '', 'B' );
		}

		$this->new_line();

		$this->clrline( 'DIGEST FOR ' . strtoupper( get_option( 'blogname' ) ), '', 'W', '📋️' );
		$this->clrline( 'BASIC INFO', '4', 'W', 'ℹ️' );
		$this->simple_ascii_table( $this->eig_digest_basic() );

		$this->clrline( 'ENVIRONMENT', '4', 'W', '🖥' );
		$this->simple_ascii_table( $this->eig_digest_environment() );

		$this->clrline( '🔌  PLUGINS & 🎨  THEMES:', '4', 'W' );
		$this->simple_ascii_table( $this->eig_digest_plugins_and_themes() );

		$this->clrline( 'CONTENT:', '4', 'W', '📚' );
		$this->simple_ascii_table( $this->eig_digest_core_content( $type), array( 'POST TYPE', 'COUNT' ) );

		$this->clrline( '🎚  OPTIONS, ⏳  TRANSIENTS & ⏰  WP_CRON:', '4', 'W' );
		$this->simple_ascii_table( $this->eig_digest_options_transients_cron(), array( 'TYPE', 'COUNT' ) );
		if ( 'full' === $type ) {
			$this->clrline( 'USERS:', '4', 'W', '👥' );
			$this->simple_ascii_table( $this->eig_digest_users(), array( 'ROLE', 'COUNT' ) );
		}
	}

	/**
	 * Prepares BASIC INFO table for $this->digest()
	 *
	 * @return array
	 */
	protected function eig_digest_basic() {
		$schemes          = array( 'http://', 'https://' );
		$opts             = array();
		$opts['SITE URL'] = str_replace( $schemes, '', get_option( 'siteurl' ) );
		$opts['HOME URL'] = str_replace( $schemes, '', get_option( 'home' ) );

		if ( $opts['SITE URL'] === $opts['HOME URL'] ) {
			unset( $opts['HOME URL'] );
		}

		$front = get_option( 'show_on_front' );
		if ( 'page' === $front ) {
			$opts['HOMEPAGE SHOWS'] = 'A Static Page (PAGE: ' . get_option( 'page_on_front' ) . ')';
			$posts_page             = get_option( 'page_for_posts' );
			if ( ! empty( $posts_page ) && absint( $posts_page ) ) {
				$opts['BLOG PATH'] = str_replace( get_option( 'home' ), '', get_the_permalink( $posts_page ) ) . ' (PAGE: ' . $posts_page . ')';
			}
		} else {
			$opts['HOMEPAGE SHOWS'] = 'Latest Posts';
		}
		$privacy = get_option( 'wp_page_for_privacy_policy' );
		if ( ! empty( $privacy ) && absint( $privacy ) ) {
			$opts['PRIVACY PAGE'] = str_replace( get_option( 'home' ), '', get_the_permalink( $privacy ) ) . ' (PAGE: ' . $privacy . ')';
		}

		return $opts;
	}

	/**
	 * Prepares ENVIRONMENT table for $this->digest()
	 *
	 * @return array
	 */
	protected function eig_digest_environment() {
		global $wpdb;
		global $wp_version;

		$env                  = array();
		$env['DATABASE NAME'] = DB_NAME;
		$env['WP VERSION']    = $wp_version;
		$env['PHP VERSION']   = phpversion();
		$env['MYSQL VERSION'] = $wpdb->get_var( 'SHOW VARIABLES LIKE "version";', 1 );

		return $env;
	}

	/**
	 * Prepares PLUGINS & THEMES table for $this->digest()
	 *
	 * @return array
	 */
	protected function eig_digest_plugins_and_themes() {
		$info       = array();
		$template   = get_option( 'template' );
		$stylesheet = get_option( 'stylesheet' );
		if ( $template !== $stylesheet ) {
			$info['ACTIVE THEME'] = $stylesheet . ' (child theme)';
			$info['PARENT THEME'] = $template;
		} else {
			$info['ACTIVE THEME'] = $template;
		}

		$info['TOTAL THEMES']   = count( wp_get_themes() );
		$info['ACTIVE PLUGINS'] = count( get_option( 'active_plugins' ) ) . ' of ' . count( get_plugins() );

		return $info;
	}

	/**
	 * Prepares CONTENT table for $this->digest()
	 *
	 * @param string $type - 'full' queries Core post types, CPTs and revisions, 'standard' only queries Core types
	 *
	 * @return array
	 */
	protected function eig_digest_core_content( $type ) {

		$posts       = new WP_Query( array( 'post_type' => 'post', 'numberposts' => - 1 ) );
		$pages       = new WP_Query( array( 'post_type' => 'page', 'numberposts' => - 1 ) );
		$attachments = new WP_Query( array( 'post_type' => 'attachment', 'numberposts' => - 1 ) );

		$content          = array();
		$content['POSTS'] = $posts->found_posts;
		$content['PAGES'] = $pages->found_posts;
		$content['MEDIA'] = $attachments->found_posts;

		if ( 'full' === $type ) {
			$revisions = new WP_Query( array( 'post_type' => 'revision', 'numberposts' => - 1 ) );
			$menus     = count( wp_get_nav_menus() );

			if ( ! empty( $menus ) ) {
				$content['NAV MENUS'] = $menus;
			}

			if ( ! empty( $revisions->found_posts ) ) {
				$content['REVISIONS'] = $revisions->found_posts;
			}
		}

		$comments_count = wp_count_comments();
		if ( $comments_count->total_comments !== 0 ) {
			if ( $comments_count->total_comments > $comments_count->spam && $comments_count->spam > 0 ) {
				$content['TOTAL COMMENTS'] = $comments_count->total_comments;
				if ( ! empty( $comments_count->approved ) ) {
					$content['COMMENTS APPROVED'] = $comments_count->approved;
				}
				if ( ! empty( $comments_count->moderated ) ) {
					$content['COMMENTS MODERATION'] = $comments_count->moderated;
				}
				if ( ! empty( $comments_count->spam ) ) {
					$content['COMMENTS SPAM'] = $comments_count->spam;
				}
				if ( ! empty( $comments_count->trash ) ) {
					$content['COMMENTS TRASHED'] = $comments_count->trash;
				}
			} else {
				if ( ! empty( $comments_count->spam ) ) {
					$content['COMMENTS SPAM'] = $comments_count->spam;
				}
			}
		}

		if ( 'full' === $type ) {
			$post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' );

			if ( ! empty( $post_types ) ) {
				foreach ( $post_types as $post_type_obj ) {
					$query = new WP_Query( array( 'post_type' => $post_type_obj->name, 'numberposts' => - 1 ) );
					if ( ! empty( $query->found_posts ) ) {
						$content[ strtoupper( $post_type_obj->label ) ] = $query->found_posts;
					}
				}
			}
		}

		return $content;
	}

	/**
	 * Prepares USERS table for $this->digest()
	 *
	 * @return array
	 */
	protected function eig_digest_users() {
		$users      = array();
		$user_query = new WP_User_Query( array( 'number' => - 1, 'count_total' => true ) );
		$user_roles = array();
		if ( ! empty( $user_query->get_results() ) ) {
			// loop users to build counts
			foreach ( $user_query->get_results() as $user ) {
				$role         = $user->roles[0];
				$user_roles[] = $role;
			}
			// now loop counts into response
			$counts = array_count_values( $user_roles );
			foreach ( $counts as $role => $count ) {
				$users[ strtoupper( $role ) ] = $count;
			}

			$users['TOTAL'] = $user_query->get_total();

		}

		return $users;
	}

	/**
	 * Prepares OPTIONS, TRANSIENTS & CRON table for $this->digest()
	 *
	 * @return array
	 */
	protected function eig_digest_options_transients_cron() {
		$details = array();

		$options = WP_CLI::runcommand(
			'option list --fields=option_name,autoload,size_bytes --format=json',
			array(
				'return' => true,
				'parse'  => 'json',
				'exit_error' => true,
			)
		);

		$all_opts           = 0;
		$opts_size          = 0;
		$all_trans          = 0;
		$all_trans_timeouts = 0;
		$trans_size         = 0;
		$all_autoload       = 0;
		$autoload_size      = 0;
		$total              = count( $options );

		foreach ( $options as $data ) {
			/**
			 * Decide Transient vs. Option
			 */
			if ( false !== strpos( $data['option_name'], 'transient' ) ) {
				if ( false !== strpos( $data['option_name'], 'transient_timeout' ) ) {
					$all_trans_timeouts ++;
				} else {
					$all_trans ++;
				}

				$trans_size = $trans_size + (int) $data['size_bytes'];
			} else {
				$all_opts ++;
				$opts_size = $opts_size + (int) $data['size_bytes'];
			}

			if ( 'yes' === $data['autoload'] ) {
				$all_autoload ++;
				$autoload_size = $autoload_size + (int) $data['size_bytes'];
			}

		}

		$details['OPTIONS'] = $all_opts;
		if ( ! empty( $details['OPTIONS'] ) ) {
			$details['OPTIONS SIZE'] = size_format( $opts_size, 1 );
		}
		$details['TRANSIENTS'] = $all_trans . ' (TIMEOUTS: ' . $all_trans_timeouts . ')';
		if ( ! empty( $trans_size ) ) {
			$details['TRANSIENTS SIZE'] = size_format( $trans_size, 1 );
		}
		$details['AUTOLOADED']      = $all_autoload . ' (' . round( ( ( $all_autoload / $total ) * 100 ), 0 ) . '%)';
		$details['AUTOLOADED SIZE'] = size_format( $autoload_size, 1 );
		$details['WP_CRON EVENTS']  = count( get_option( 'cron' ) );

		return $details;
	}

}

