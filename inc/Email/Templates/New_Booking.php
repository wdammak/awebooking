<?php
namespace AweBooking\Email\Templates;

use AweBooking\Email\Mailable;

class New_Booking extends Mailable {
	/**
	 * The booking instance.
	 *
	 * @var \AweBooking\Model\Booking
	 */
	protected $booking;

	/**
	 * {@inheritdoc}
	 */
	public function setup() {
		$this->id             = 'new_booking';
		$this->title          = esc_html__( 'New booking', 'awebooking' );
		$this->description    = esc_html__( 'New booking emails are sent to chosen recipient(s) when a new booking is received.', 'awebooking' );
		$this->customer_email = false;
		$this->placeholders   = [
			'{booking_date}'   => '',
			'{booking_number}' => '',
		];
	}

	/**
	 * Prepare data for sending.
	 *
	 * @param  \AweBooking\Model\Booking $booking       The booking instance.
	 * @return void
	 */
	protected function prepare_data( $booking ) {
		$this->booking = $booking;
		$this->placeholders = ( new Booking_Placeholder( $booking, $this ) )->apply( $this->placeholders );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_subject() {
		return esc_html__( '[{site_title}] New customer booking #{booking_number} - {booking_date}', 'awebooking' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_content() {
		return "You have received a booking from {customer_first_name}. The booking is as follows:\n\n";
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_content_plain() {
		return $this->format_string( $this->get_option( 'content' ) );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_content_html() {
		return abrs_get_template_content( 'emails/new-booking.php', [
			'email'         => $this,
			'booking'       => $this->booking,
			'content'       => $this->format_string( $this->get_option( 'content' ) ),
		]);
	}
}