<?php

/**
 * Clean variables using sanitize_text_field.
 *
 * Arrays are cleaned recursively.
 *
 * @param  string|array $var Data to sanitize.
 * @return string|array
 */
function abrs_clean( $var ) {
	return abrs_recursive_sanitizer( $var, 'sanitize_text_field' );
}

/**
 * Recursive sanitize a given values.
 *
 * @param  mixed  $values    The values.
 * @param  string $sanitizer The sanitizer callback.
 * @return mixed
 */
function abrs_recursive_sanitizer( $values, $sanitizer ) {
	if ( ! is_array( $values ) ) {
		return is_scalar( $values ) ? $sanitizer( $values ) : $values;
	}

	foreach ( $values as $key => &$value ) {
		if ( is_array( $value ) ) {
			$value = abrs_recursive_sanitizer( $value, $sanitizer );
		} else {
			$value = is_scalar( $value ) ? $sanitizer( $value ) : $value;
		}
	}

	return $values;
}

/**
 * Sanitizes a simple text string.
 *
 * @param  mixed $value The string to sanitize.
 * @return string
 */
function abrs_sanitize_text( $value ) {
	return sanitize_text_field( wp_unslash( $value ) );
}

/**
 * Sanitizes content that could contain HTML.
 *
 * @param  mixed $value The HTML string to sanitize.
 * @return string
 */
function abrs_sanitize_html( $value ) {
	return balanceTags( wp_kses_post( $value ), true );
}

/**
 * Sanitizes for checkbox or toggle.
 *
 * @param  mixed $value The checkbox value.
 * @return string
 */
function abrs_sanitize_checkbox( $value ) {
	return in_array( $value, [ 'on', 'yes', '1', 1, true ], true ) ? 'on' : 'off';
}

/**
 * Sanitize decimal number.
 *
 * @param  mixed $number The raw number value.
 * @return string
 */
function abrs_sanitize_decimal( $number ) {
	$locale = localeconv();
	$decimals = [ abrs_get_option( 'price_decimal_separator', '.' ), $locale['decimal_point'], $locale['mon_decimal_point'] ];

	// If not float number, clean input number and remove locale decimals.
	// Then keep only numeric, '-', comma and dot character.
	if ( ! is_float( $number ) ) {
		$number = str_replace( $decimals, '.', sanitize_text_field( $number ) );
		$number = preg_replace( '/[^0-9\.,-]/', '', $number );
	}

	// Conver the number to Decimal object then convert back to string.
	// This will ensure we have a given correct number.
	$number = abrs_decimal( $number )->as_string();

	// Trim the zeros.
	return rtrim( rtrim( $number, '0' ), '.' );
}

/**
 * Sanitizes days of week.
 *
 * @param  mixed $days The input days.
 * @return array|null
 */
function abrs_sanitize_days_of_week( $days ) {
	if ( ! is_array( $days ) ) {
		return null;
	}

	$days = array_unique( $days );
	$days_of_week = [ 0, 1, 2, 3, 4, 5, 6 ];

	return array_values( array_intersect( $days_of_week, $days ) );
}

/**
 * Sanitizes a color value with support bold hex & rgba.
 *
 * @param  string $color The value to sanitize.
 * @return string
 */
function abrs_sanitize_color( $color ) {
	if ( empty( $color ) ) {
		return '';
	}

	if ( false !== strpos( $color, '#' ) ) {
		return sanitize_hex_color( $color );
	}

	if ( false !== strpos( $color, 'rgba(' ) ) {
		return abrs_sanitize_rgba_color( $color );
	}

	return '';
}

/**
 * Sanitizes an RGBA color value.
 *
 * @param  string $color The RGBA color value to sanitize.
 * @return string
 */
function abrs_sanitize_rgba_color( $color ) {
	// Trim unneeded whitespace.
	$color = trim( str_replace( ' ', '', $color ) );

	sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );

	if ( ( $red >= 0 && $red <= 255 )
		&& ( $green >= 0 && $green <= 255 )
		&& ( $blue >= 0 && $blue <= 255 )
		&& ( $alpha >= 0 && $alpha <= 1 ) ) {
		return "rgba({$red},{$green},{$blue},{$alpha})";
	}

	return '';
}

/**
 * Sanitizes an email.
 *
 * @param  string $email The email to sanitize.
 * @return string
 */
function abrs_sanitize_email( $email ) {
	if ( is_array( $email ) ) {
		$email = implode( ',', $email );
	}

	$email = array_map( 'trim', explode( ',', $email ) );
	$email = array_filter( $email, 'is_email' );

	return implode( ', ', $email );
}

/**
 * Sanitizes comma-separated list of IDs.
 *
 * @param  string $list The value to sanitize.
 * @return string
 */
function abrs_sanitize_ids( $list ) {
	return implode( ', ', wp_parse_id_list( $list ) );
}

/**
 * Sanitize a image size.
 *
 * @param  array $size The image size to sanitize.
 * @return array
 */
function abrs_sanitize_image_size( $size ) {
	$atts = shortcode_atts([
		'width'  => 150,
		'height' => 150,
		'crop'   => 'on',
	], $size );

	$atts['width']  = absint( $atts['width'] );
	$atts['height'] = absint( $atts['height'] );
	$atts['crop']   = isset( $size['crop'] ) ? 'on' : 'off';

	return $atts;
}

/**
 * Sanitises various option values based on the nature of the option.
 *
 * @param  string $key   The name of the option.
 * @param  string $value The unsanitised value.
 * @return string
 */
function abrs_sanitize_option( $key, $value ) {
	// Pre-sanitize option by key name.
	switch ( $key ) {
		case 'enable_location':
		case 'children_bookable':
		case 'infants_bookable':
		case 'calc_taxes':
			$value = abrs_sanitize_checkbox( $value );
			break;

		case 'star_rating':
		case 'price_number_decimals':
		case 'page_checkout':
		case 'page_check_availability':
		case 'scheduler_display_duration':
			$value = absint( $value );
			break;
	}

	/**
	 * Allow custom sanitize option values.
	 *
	 * @param mixed  $value The option value.
	 * @param string $key   The option key name.
	 * @var   mixed
	 */
	return apply_filters( 'abrs_sanitize_option', $value, $key );
}

/**
 * Escaping option value before return.
 *
 * @param  string $option The name of the option.
 * @param  string $value  The unsanitised value.
 *
 * @return mixed
 */
function abrs_esc_option( $option, $value ) {
	switch ( $option ) {
		case 'enable_location':
		case 'children_bookable':
		case 'infants_bookable':
		case 'calc_taxes':
		case 'prices_include_tax':
			$value = 'on' === abrs_sanitize_checkbox( $value );
			break;

		case 'price_decimal_separator':
		case 'price_thousand_separator':
			$value = untrailingslashit( $value );
			break;

		case 'display_datepicker_disabledays':
			$value = is_array( $value ) ? abrs_sanitize_days_of_week( $value ) : [];
			break;

		case 'display_datepicker_disabledates':
			$value = wp_parse_slug_list( $value );
			$value = array_filter( $value, 'abrs_is_standard_date' );
			break;
	}

	/**
	 * Allow custom escaping option values.
	 *
	 * @param mixed  $value  The option value.
	 * @param string $option The option key name.
	 * @var   mixed
	 */
	return apply_filters( 'abrs_esc_option', $value, $option );
}