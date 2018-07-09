<?php
/**
 * This template show the search result capacity.
 *
 * This template can be overridden by copying it to {yourtheme}/awebooking/search/result/capacity.php.
 *
 * @see      http://docs.awethemes.com/awebooking/developers/theme-developers/
 * @author   awethemes
 * @package  AweBooking
 * @version  3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* @var \AweBooking\Model\Room_Type $room_type */
/* @var \AweBooking\Availability\Room_Rate $room_rate */

?>

<div class="roommaster-occupancy roommaster-box" data-awebooking="tooltip" data-tippy-html="#occupancy-description-<?php echo absint( $room_type->get_id() ); ?>" title="A">
	<?php if ( $room_type->get( 'number_adults' ) ) : ?>
		<span class="roommaster-occupancy__item">
			<?php
				/* translators: %1$s number adults, %2$s adult button */
				printf( esc_html_x( '%1$s x %2$s', 'number adults', 'awebooking' ),
					absint( $room_type->get( 'number_adults' ) ),
					'<i class="aficon aficon-man"></i><span class="screen-reader-text">' . esc_html_x( 'Adult', 'adult button', 'awebooking' ) . '</span>'
				);
			?>
		</span>
	<?php endif; ?>

	<?php if ( $room_type->get( 'number_children' ) ) : ?>
		<span class="roommaster-occupancy__item">
			<?php
				/* translators: %1$s number children, %2$s child button */
				printf( esc_html_x( '%1$s x %2$s', 'number children', 'awebooking' ),
					absint( $room_type->get( 'number_children' ) ),
					'<i class="aficon aficon-body"></i><span class="screen-reader-text">' . esc_html_x( 'Child', 'child button', 'awebooking' ) . '</span>'
				);
			?>

		</span>
	<?php endif; ?>

	<?php if ( $room_type->get( 'number_infants' ) ) : ?>
		<span class="roommaster-occupancy__item">
			<?php
				/* translators: %1$s number infants, %2$s infant button */
				printf( esc_html_x( '%1$s x %2$s', 'number infants', 'awebooking' ),
					absint( $room_type->get( 'number_infants' ) ),
					'<i class="aficon aficon-infant"></i><span class="screen-reader-text">' . esc_html_x( 'Infant', 'infant button', 'awebooking' ) . '</span>'
				);
			?>
		</span>
	<?php endif; ?>

</div>
<div id="occupancy-description-<?php echo absint( $room_type->get_id() ); ?>" class="occupancy-description" style="display: none;">
	<h4 class="occupancy-description__title">
		<?php
			/* translators: %s maximum occupancy */
			printf( esc_html__( 'Maximum occupancy: %s', 'awebooking' ), absint( $room_type->get( 'maximum_occupancy' ) ) );
		?>
	</h4>
	<ul class="occupancy-description__list">
		<li>
			<?php
				/* translators: %s number adults */
				printf( esc_html__( 'Number adults: %s', 'awebooking' ), absint( $room_type->get( 'number_adults' ) ) );
			?>
		</li>

		<?php if ( $room_type->get( 'number_children' ) ) : ?>
			<li>
				<?php
					/* translators: %s number children */
					printf( esc_html__( 'Number children: %s', 'awebooking' ), absint( $room_type->get( 'number_children' ) ) );
				?>
			</li>
		<?php endif; ?>

		<?php if ( $room_type->get( 'number_infants' ) ) : ?>
			<li>
				<?php
					/* translators: %s number infants */
					printf( esc_html__( 'Number infants: %s', 'awebooking' ), absint( $room_type->get( 'number_infants' ) ) );
				?>
			</li>
		<?php endif; ?>
	</ul>

</div>
