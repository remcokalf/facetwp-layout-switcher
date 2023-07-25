<?php
/*
Plugin Name: FacetWP - Layout Switcher
Description: Add one or more layout switchers with a shortcode.
Version: 0.2
Author: FacetWP, LLC
Author URI: https://facetwp.com/
GitHub URI: facetwp/facetwp-layout-switcher
*/


defined( 'ABSPATH' ) or exit;


class FacetWP_LayoutSwitcher_Addon {

	function __construct() {

		define( 'FACETWP_LAYOUT_SWITCHER_VERSION', '0.2' );
		define( 'FACETWP_LAYOUT_SWITCHER_URL', plugins_url( '', __FILE__ ) );

		add_filter( 'facetwp_assets', array( $this, 'add_assets' ), 11 );
		add_filter( 'facetwp_shortcode_html', array( $this, 'process_shortcode' ), 10, 2 );

	}


	function add_assets( $assets ) {
		$assets['facetwp-layout-switcher.js'] = [
			FACETWP_LAYOUT_SWITCHER_URL . '/assets/js/front.js',
			FACETWP_LAYOUT_SWITCHER_VERSION
		];

		// Optionally remove Layout Wwitcher CSS
		if ( apply_filters( 'facetwp_layout_switcher_load_css', true ) ) {
			$assets['facetwp-layout-switcher.css'] = [
				FACETWP_LAYOUT_SWITCHER_URL . '/assets/css/front.css',
				FACETWP_LAYOUT_SWITCHER_VERSION
			];
		}

		return $assets;
	}


	function process_shortcode( $output, $atts ) {

		// Output nothing if no layoutmodes attribute or if it is empty
		if ( isset( $atts['layoutmodes'] ) ) {

			if ( empty( $atts['layoutmodes'] ) ) {
				return $output;
			}

			// Optionally set target template class
			if ( isset( $atts['target'] ) && ! empty( $atts['target'] ) ) {
				$targets = strtolower( preg_replace( '/\s*,\s*/', ',', esc_attr( $atts['target'] ) ) );
				$target_array = explode( ',', $targets );
			} else {
				$target_array = [ 'facetwp-template' ];
			}
			$is_multitargetswitcher = count( $target_array ) > 1;

			// Optional, customizable and translatable main label
			// Default labeltext is "Show as:" (single target) or "Show:" (multi-target) if is set to true
			// Label attribute can be left out if no label required
			$haslabel = isset( $atts['label'] ) && ! empty( $atts['label'] );

			if ( $haslabel ) {
				if ( $atts['label'] === "true" ) {
					if ( $is_multitargetswitcher ) {
						$label = facetwp_i18n( __( 'Show:', 'fwp-layout-switcher' ) );
					} else {
						$label = facetwp_i18n( __( 'Show as:', 'fwp-layout-switcher' ) );
					}
				} else {
					$label = facetwp_i18n( esc_attr( $atts['label'] ) );
				}
				$label_output = '<span class="label">' . $label . '</span>';
			}

			// Sanitize the layout mode attribute and remove white spaces
			$modes = strtolower( preg_replace( '/\s*,\s*/', ',', esc_attr( $atts['layoutmodes'] ) ) );
			$modes_array = explode( ',', $modes );

			// Set switcher type: text (default), icons, dropdown or fSelect
			if ( isset( $atts['type'] ) && $atts['type'] == "dropdown" ) {

				$type = "type-dropdown";

			} elseif ( isset( $atts['type'] ) && $atts['type'] == "fselect" ) {

				$type = "type-fselect";

				// Load fSelect assets conditionally
				add_filter( 'facetwp_assets', function ( $assets ) {
					$assets['fSelect.js']  = FACETWP_URL . '/assets/vendor/fSelect/fSelect.js';
					$assets['fSelect.css'] = FACETWP_URL . '/assets/vendor/fSelect/fSelect.css';

					return $assets;
				} );

			} elseif ( isset( $atts['type'] ) && $atts['type'] == "icons" ) {

				$type = "type-icons";

			} else {

				$type = "type-text";

			}

			// Set setinitial - default is true
			// Set initial mode if setinitial is true
			if ( isset( $atts['setinitial'] ) && $atts['setinitial'] === "false" ) {

				$initialclass = ' setinitial-false';

			} else {

				// Get first layout mode class
				$initialmode  = strtolower( str_replace( ' ', '-', $modes_array[0] ) );
				$initialclass = " setinitial-true initial-" . $initialmode;

			}

			// Set custom class(es)
			if ( isset( $atts['class'] ) && ! empty( $atts['class'] ) ) {

				$customclass = esc_attr( $atts['class']);
				$customclass = ' ' . strtolower( preg_replace("/[^a-zA-Z0-9\s\-_]/", "", $customclass) );

			} else {

				$customclass = '';

			}

			// Render output
			$target_classes = ' target-' . implode(' target-', $target_array);
			$switchertype_class = $is_multitargetswitcher  ? ' multitarget' : ' singletarget';

			// Two types: single target switchers, and multi-target switchers.
			// Both types need to function independently
			$prefix = count($target_array) > 1 ? 'multitarget-mode-' : 'singletarget-mode-';

			$output .= '<div class="facetwp-layout-switcher ' . $type . $target_classes . $initialclass . $switchertype_class . $customclass . '">';

			// Output for type dropdown and fselect
			if ( $type === "type-dropdown" || $type === "type-fselect" ) {

				$labelinside = isset( $atts['labelposition'] ) && $atts['labelposition'] === 'inside';

				if ( $haslabel && ! $labelinside ) {
					$output .= $label_output;
				}

				$output .= '<select>';

				if ( $haslabel && $labelinside ) {
					$output .= '<option value="">' . $label . '</option>';
				}

				foreach ( $modes_array as $key => $mode ) {

					$mode = facetwp_i18n( __( $mode, 'fwp-layout-switcher' ) );
					$class  = $prefix . strtolower( str_replace( ' ', '-', $mode ) );
					$output .= '<option value="' . $class . '">' . $mode . '</option>';

				}

				$output .= '</select>';

			// Outpt default ul/li
			} else {

				if ( $haslabel ) {
					$output .= $label_output;
				}
				$output .= '<ul>';

				foreach ( $modes_array as $key => $mode ) {

					$mode = facetwp_i18n( __( $mode, 'fwp-layout-switcher' ) );
					$val  = $prefix . strtolower( str_replace( ' ', '-', $mode ) );

					$class = ( $key === 0 ) ? "active" : "";
					$title = isset( $label ) ? $label . ' ' . $mode : $mode;

					$output .= '<li class="' . $class . '" data-value="' . $val . '"><a href="#" title="' . $title . '"><span>' . $mode . '</span></a></li>';

				}

				$output .= '</ul>';

			}

			$output .= '</div>';

		}

		return $output;

	}

}

new FacetWP_LayoutSwitcher_Addon();