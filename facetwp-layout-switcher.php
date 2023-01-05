<?php
/*
Plugin Name: FacetWP - Layout Switcher
Description: Add one or more layout switchers with a shortcode.
Version: 0.1
Author: FacetWP, LLC
Author URI: https://facetwp.com/
GitHub URI: facetwp/facetwp-layout-switcher
*/


defined( 'ABSPATH' ) or exit;


class FacetWP_LayoutSwitcher_Addon {

  function __construct() {

    define( 'FACETWP_LAYOUT_SWITCHER_VERSION', '0.1' );
    define( 'FACETWP_LAYOUT_SWITCHER_URL', plugins_url( '', __FILE__ ) );

    add_filter( 'facetwp_assets', array( $this, 'assets' ), 11 );
    add_filter( 'facetwp_shortcode_html', array( $this, 'shortcode' ), 10, 2 );

  }


  function assets( $assets ) {
    $assets['facetwp-layout-switcher.js'] = [
      FACETWP_LAYOUT_SWITCHER_URL . '/assets/js/front.js',
      FACETWP_LAYOUT_SWITCHER_VERSION
    ];

    // Optionally remove layout switcher CSS
    if ( apply_filters( 'facetwp_layout_switcher_load_css', true ) ) {
      $assets['facetwp-layout-switcher.css'] = [
        FACETWP_LAYOUT_SWITCHER_URL . '/assets/css/front.css',
        FACETWP_LAYOUT_SWITCHER_VERSION
      ];
    }

    return $assets;
  }


  function shortcode( $output, $atts ) {

    // Output nothing if no layoutmodes attribute or if it is empty
    if ( isset( $atts['layoutmodes'] ) ) {

      if ( empty( $atts['layoutmodes'] ) ) {
        return $output;
      }

      // Optionally set target template class
      $target = ( isset( $atts['target'] ) && ! empty( $atts['target'] ) ) ? " target-" . esc_attr( $atts['target'] ) : ' target-facetwp-template';


      // Optional, customizable and translatable main label
      // Default labeltext is "Show as:" if is set to true
      // Label attribute can be left out if no label required

      $haslabel = isset( $atts['label'] ) && ! empty( $atts['label'] );

      if ( $haslabel ) {
        if ( $atts['label'] === "true" ) {
          $label = facetwp_i18n( __( 'Show as:', 'fwp-layout-switcher' ) );
        } else {
          $label = facetwp_i18n( esc_attr( $atts['label'] ) );
        }
        $label_output = '<span class="label">' . $label . '</span>';
      }

      // Sanitize the layout mode attribute and remove white spaces
      $no_whitespace = preg_replace( '/\s*,\s*/', ',', esc_attr($atts['layoutmodes']) );
      $modes_array   = explode( ',', $no_whitespace );

      // Set switcher type: text (default), icons, dropdown or fSelect
      if ( isset( $atts['type'] ) && $atts['type'] == "dropdown" ) {

        $type = "type-dropdown";

      } elseif ( isset( $atts['type'] ) && $atts['type'] == "fselect" ) {

        $type = "type-fselect";

        // Load fSelect assets conditionally
        add_filter( 'facetwp_assets', function( $assets ) {
          $assets['fSelect.js']  = FACETWP_URL . '/assets/vendor/fSelect/fSelect.js';
          $assets['fSelect.css'] = FACETWP_URL . '/assets/vendor/fSelect/fSelect.css';

          return $assets;
        } );

      } elseif ( isset( $atts['type'] ) && $atts['type'] == "icons" ) {

        $type = "type-icons";

      } else {

        $type = "type-text";

      }

      // Render output
      $output .= '<div class="facetwp-layout-switcher ' . $type . $target . '">';

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

          $mode   = facetwp_i18n( __( $mode, 'fwp-layout-switcher' ) );
          $class  = 'layoutmode-' . strtolower( str_replace( ' ', '-', $mode ) );
          $output .= '<option value="' . $class . '">' . $mode . '</option>';

        }

        $output .= '</select>';

        // Default default ul/li
      } else {

        if ( $haslabel ) {
          $output .= $label_output;
        }
        $output .= '<ul>';

        foreach ( $modes_array as $key => $mode ) {

          $mode = facetwp_i18n( __( $mode, 'fwp-layout-switcher' ) );
          $val   = 'layoutmode-' . strtolower( str_replace( ' ', '-', $mode ) );

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