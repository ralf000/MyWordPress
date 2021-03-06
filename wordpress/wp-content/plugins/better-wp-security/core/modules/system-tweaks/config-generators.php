<?php

final class ITSEC_System_Tweaks_Config_Generators {
	public static function filter_litespeed_server_config_modification( $modification ) {
		return self::filter_apache_server_config_modification( $modification, 'litespeed' );
	}
	
	public static function filter_apache_server_config_modification( $modification, $server = 'apache' ) {
		$input = ITSEC_Modules::get_settings( 'system-tweaks' );
		
		if ( $input['protect_files'] ) {
			$files = array(
				'.htaccess',
				'readme.html',
				'readme.txt',
				'install.php',
				'wp-config.php',
			);
			
			$modification .= "\n";
			$modification .= "\t# " . __( 'Protect System Files - Security > Settings > System Tweaks > System Files', 'better-wp-security' ) . "\n";
			
			foreach ( $files as $file ) {
				$modification .= "\t<files $file>\n";
				
				if ( 'apache' === $server ) {
					$modification .= "\t\t<IfModule mod_authz_core.c>\n";
					$modification .= "\t\t\tRequire all denied\n";
					$modification .= "\t\t</IfModule>\n";
					$modification .= "\t\t<IfModule !mod_authz_core.c>\n";
					$modification .= "\t\t\tOrder allow,deny\n";
					$modification .= "\t\t\tDeny from all\n";
					$modification .= "\t\t</IfModule>\n";
				} else {
					$modification .= "\t\t<IfModule mod_litespeed.c>\n";
					$modification .= "\t\t\tOrder allow,deny\n";
					$modification .= "\t\t\tDeny from all\n";
					$modification .= "\t\t</IfModule>\n";
				}
				
				$modification .= "\t</files>\n";
			}
		}
		
		if ( $input['directory_browsing'] ) {
			$modification .= "\n";
			$modification .= "\t# " . __( 'Disable Directory Browsing - Security > Settings > System Tweaks > Directory Browsing', 'better-wp-security' ) . "\n";
			$modification .= "\tOptions -Indexes\n";
		}
		
		
		$rewrites = '';
		
		if ( $input['protect_files'] ) {
			$rewrites .= "\n";
			$rewrites .= "\t\t# " . __( 'Protect System Files - Security > Settings > System Tweaks > System Files', 'better-wp-security' ) . "\n";
			$rewrites .= "\t\tRewriteRule ^wp-admin/includes/ - [F]\n";
			$rewrites .= "\t\tRewriteRule !^wp-includes/ - [S=3]\n";
			$rewrites .= "\t\tRewriteCond %{SCRIPT_FILENAME} !^(.*)wp-includes/ms-files.php\n";
			$rewrites .= "\t\tRewriteRule ^wp-includes/[^/]+\.php$ - [F]\n";
			$rewrites .= "\t\tRewriteRule ^wp-includes/js/tinymce/langs/.+\.php - [F]\n";
			$rewrites .= "\t\tRewriteRule ^wp-includes/theme-compat/ - [F]\n";
		}
		
		if ( $input['uploads_php'] ) {
			require_once( $GLOBALS['itsec_globals']['plugin_dir'] . 'core/lib/class-itsec-lib-utility.php' );
			
			$dir = ITSEC_Lib_Utility::get_relative_upload_url_path();
			
			if ( ! empty( $dir ) ) {
				$dir = preg_quote( $dir );
				
				$rewrites .= "\n";
				$rewrites .= "\t\t# " . __( 'Disable PHP in Uploads - Security > Settings > System Tweaks > Uploads', 'better-wp-security' ) . "\n";
				$rewrites .= "\t\tRewriteRule ^$dir/.*\.(?:php[1-6]?|pht|phtml?)$ - [NC,F]\n";
			}
		}
		
		if ( $input['request_methods'] ) {
			$rewrites .= "\n";
			$rewrites .= "\t\t# " . __( 'Filter Request Methods - Security > Settings > System Tweaks > Request Methods', 'better-wp-security' ) . "\n";
			$rewrites .= "\t\tRewriteCond %{REQUEST_METHOD} ^(TRACE|DELETE|TRACK) [NC]\n";
			$rewrites .= "\t\tRewriteRule ^.* - [F]\n";
		}
		
		if ( $input['suspicious_query_strings'] ) {
			$rewrites .= "\n";
			$rewrites .= "\t\t# " . __( 'Filter Suspicious Query Strings in the URL - Security > Settings > System Tweaks > Suspicious Query Strings', 'better-wp-security' ) . "\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} \.\.\/ [NC,OR]\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} ^.*\.(bash|git|hg|log|svn|swp|cvs) [NC,OR]\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} etc/passwd [NC,OR]\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} boot\.ini [NC,OR]\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} ftp\:  [NC,OR]\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} http\:  [NC,OR]\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} https\:  [NC,OR]\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC,OR]\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} mosConfig_[a-zA-Z_]{1,21}(=|%3D) [NC,OR]\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} base64_encode.*\(.*\) [NC,OR]\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} ^.*(%24&x).* [NC,OR]\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} ^.*(127\.0).* [NC,OR]\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} ^.*(globals|encode|localhost|loopback).* [NC,OR]\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} ^.*(request|concat|insert|union|declare).* [NC]\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} !^loggedout=true\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} !^action=jetpack-sso\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} !^action=rp\n";
			$rewrites .= "\t\tRewriteCond %{HTTP_COOKIE} !^.*wordpress_logged_in_.*$\n";
			$rewrites .= "\t\tRewriteCond %{HTTP_REFERER} !^http://maps\.googleapis\.com(.*)$\n";
			$rewrites .= "\t\tRewriteRule ^.* - [F]\n";
		}
		
		if ( $input['non_english_characters'] ) {
			$rewrites .= "\n";
			$rewrites .= "\t\t# " . __( 'Filter Non-English Characters - Security > Settings > System Tweaks > Non-English Characters', 'better-wp-security' ) . "\n";
			$rewrites .= "\t\tRewriteCond %{QUERY_STRING} ^.*(%0|%A|%B|%C|%D|%E|%F).* [NC]\n";
			$rewrites .= "\t\tRewriteRule ^.* - [F]\n";
		}
		
		if ( ! empty( $rewrites ) ) {
			$modification .= "\n";
			$modification .= "\t<IfModule mod_rewrite.c>\n";
			$modification .= "\t\tRewriteEngine On\n";
			$modification .= $rewrites;
			$modification .= "\t</IfModule>\n";
		}
		
		
		return $modification;
	}
	
	public static function filter_nginx_server_config_modification( $modification ) {
		$input = ITSEC_Modules::get_settings( 'system-tweaks' );
		
		if ( $input['protect_files'] ) {
			$modification .= "\n";
			$modification .= "\t# " . __( 'Protect System Files - Security > Settings > System Tweaks > System Files', 'better-wp-security' ) . "\n";
			$modification .= "\tlocation ~ /\.ht { deny all; }\n";
			$modification .= "\tlocation ~ wp-config.php { deny all; }\n";
			$modification .= "\tlocation ~ readme.html { deny all; }\n";
			$modification .= "\tlocation ~ readme.txt { deny all; }\n";
			$modification .= "\tlocation ~ /install.php { deny all; }\n";
			$modification .= "\tlocation ^wp-includes/(.*).php { deny all; }\n";
			$modification .= "\tlocation ^/wp-admin/includes(.*)$ { deny all; }\n";
		}
		
		// Rewrite Rules for Disable PHP in Uploads
		if ( $input['uploads_php'] ) {
			require_once( $GLOBALS['itsec_globals']['plugin_dir'] . 'core/lib/class-itsec-lib-utility.php' );
			
			$dir = ITSEC_Lib_Utility::get_relative_upload_url_path();
			
			if ( ! empty( $dir ) ) {
				$dir = preg_quote( $dir );
				
				$modification .= "\n";
				$modification .= "\t# " . __( 'Disable PHP in Uploads - Security > Settings > System Tweaks > Uploads', 'better-wp-security' ) . "\n";
				$modification .= "\tlocation ^$dir/(.*).php(.?) { deny all; }\n";
			}
		}
		
		// Apache rewrite rules for disable http methods
		if ( $input['request_methods'] ) {
			$modification .= "\n";
			$modification .= "\t# " . __( 'Filter Request Methods - Security > Settings > System Tweaks > Request Methods', 'better-wp-security' ) . "\n";
			$modification .= "\tif (\$request_method ~* \"^(TRACE|DELETE|TRACK)\") { return 403; }\n";
		}
		
		// Process suspicious query rules
		if ( $input['suspicious_query_strings'] ) {
			$modification .= "\n";
			$modification .= "\t# " . __( 'Filter Suspicious Query Strings in the URL - Security > Settings > System Tweaks > Suspicious Query Strings', 'better-wp-security' ) . "\n";
			$modification .= "\tset \$susquery 0;\n";
			$modification .= "\tif (\$args ~* \"\\.\\./\") { set \$susquery 1; }\n";
			$modification .= "\tif (\$args ~* \"\.(bash|git|hg|log|svn|swp|cvs)\") { set \$susquery 1; }\n";
			$modification .= "\tif (\$args ~* \"etc/passwd\") { set \$susquery 1; }\n";
			$modification .= "\tif (\$args ~* \"boot.ini\") { set \$susquery 1; }\n";
			$modification .= "\tif (\$args ~* \"ftp:\") { set \$susquery 1; }\n";
			$modification .= "\tif (\$args ~* \"http:\") { set \$susquery 1; }\n";
			$modification .= "\tif (\$args ~* \"https:\") { set \$susquery 1; }\n";
			$modification .= "\tif (\$args ~* \"(<|%3C).*script.*(>|%3E)\") { set \$susquery 1; }\n";
			$modification .= "\tif (\$args ~* \"mosConfig_[a-zA-Z_]{1,21}(=|%3D)\") { set \$susquery 1; }\n";
			$modification .= "\tif (\$args ~* \"base64_encode\") { set \$susquery 1; }\n";
			$modification .= "\tif (\$args ~* \"(%24&x)\") { set \$susquery 1; }\n";
			$modification .= "\tif (\$args ~* \"(127.0)\") { set \$susquery 1; }\n";
			$modification .= "\tif (\$args ~* \"(globals|encode|localhost|loopback)\") { set \$susquery 1; }\n";
			$modification .= "\tif (\$args ~* \"(request|insert|concat|union|declare)\") { set \$susquery 1; }\n";
			$modification .= "\tif (\$args !~ \"^loggedout=true\") { set \$susquery 0; }\n";
			$modification .= "\tif (\$args !~ \"^action=jetpack-sso\") { set \$susquery 0; }\n";
			$modification .= "\tif (\$args !~ \"^action=rp\") { set \$susquery 0; }\n";
			$modification .= "\tif (\$http_cookie !~ \"^.*wordpress_logged_in_.*\$\") { set \$susquery 0; }\n";
			$modification .= "\tif (\$http_referer !~ \"^http://maps.googleapis.com(.*)\$\") { set \$susquery 0; }\n";
			$modification .= "\tif (\$susquery = 1) { return 403; } \n";
		}
		
		// Process filtering of foreign characters
		if ( $input['non_english_characters'] ) {
			$modification .= "\n";
			$modification .= "\t# " . __( 'Filter Non-English Characters - Security > Settings > System Tweaks > Non-English Characters', 'better-wp-security' ) . "\n";
			$modification .= "\tif (\$args ~* \"(%0|%A|%B|%C|%D|%E|%F)\") { return 403; }\n";
		}
		
		return $modification;
	}
	
	protected static function get_valid_referers( $server_type ) {
		$valid_referers = array();
		
		if ( 'apache' === $server_type ) {
			$domain = ITSEC_Lib::get_domain( get_site_url() );
			
			if ( '*' == $domain ) {
				$valid_referers[] = $domain;
			} else {
				$valid_referers[] = "*.$domain";
			}
		} else if ( 'nginx' === $server_type ) {
			$valid_referers[] = 'server_names';
		} else {
			return array();
		}
		
		$valid_referers[] = 'jetpack.wordpress.com/jetpack-comment/';
		$valid_referers = apply_filters( 'itsec_filter_valid_comment_referers', $valid_referers, $server_type );
		
		if ( is_string( $valid_referers ) ) {
			$valid_referers = array( $valid_referers );
		} else if ( ! is_array( $valid_referers ) ) {
			$valid_referers = array();
		}
		
		foreach ( $valid_referers as $index => $referer ) {
			$valid_referers[$index] = preg_replace( '|^https?://|', '', $referer );
		}
		
		return $valid_referers;
	}
}
