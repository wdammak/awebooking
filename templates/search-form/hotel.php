<?php
/**
 * The template for displaying hotel input in the search-form.php template
 *
 * This template can be overridden by copying it to {yourtheme}/awebooking/template-parts/search-form/hotel.php.
 *
 * @see      http://docs.awethemes.com/awebooking/developers/theme-developers/
 * @author   awethemes
 * @package  AweBooking
 * @version  3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! abrs_multiple_hotels() || ! $atts['hotel_location'] ) {
	return;
}

?>

<div tabindex="0" class="searchbox__box searchbox__box--hotel">
	<div class="searchbox__box-wrap">
		<div class="searchbox__box-icon">
			<i class="aficon aficon-search"></i>
		</div>

		<div class="searchbox__box-line">
			<label class="searchbox__box-label">
				<span><?php esc_html_e( 'Hotel', 'awebooking' ); ?></span>
			</label>

			<div class="searchbox__box-input">
				<select name="hotel" class="searchbox__input searchbox__input--hotel input-transparent">
					<?php foreach ( abrs_list_hotels( [], true ) as $hotel ) : ?>
						<option value="<?php echo esc_attr( $hotel->get_id() ); ?>"><?php echo esc_html( $hotel->get( 'name' ) ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>
</div>
