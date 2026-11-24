<?php
defined( 'ABSPATH' ) || exit;

$labels = array(
    'available'   => __( 'Open for enquiries', 'csp-service-notice' ),
    'limited'     => __( 'Limited availability', 'csp-service-notice' ),
    'unavailable' => __( 'Currently unavailable', 'csp-service-notice' ),
);
$status = isset( $attributes['status'] ) && isset( $labels[ $attributes['status'] ] ) ? $attributes['status'] : 'available';
$title = isset( $attributes['title'] ) ? $attributes['title'] : '';
$message = isset( $attributes['message'] ) ? $attributes['message'] : '';
$label = isset( $attributes['linkLabel'] ) ? $attributes['linkLabel'] : '';
$url = isset( $attributes['linkUrl'] ) ? esc_url( $attributes['linkUrl'], array( 'http', 'https', 'mailto', 'tel' ) ) : '';
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'csp-notice csp-notice--' . $status ) ); ?>>
    <span class="csp-notice__status"><?php echo esc_html( $labels[ $status ] ); ?></span>
    <?php if ( '' !== trim( $title ) ) : ?>
        <h2 class="csp-notice__title"><?php echo wp_kses( $title, array( 'br' => array() ) ); ?></h2>
    <?php endif; ?>
    <?php if ( '' !== trim( $message ) ) : ?>
        <p class="csp-notice__message"><?php echo wp_kses( $message, array( 'br' => array() ) ); ?></p>
    <?php endif; ?>
    <?php if ( '' !== $url && '' !== trim( $label ) ) : ?>
        <a class="csp-notice__link" href="<?php echo $url; ?>"><?php echo esc_html( $label ); ?></a>
    <?php endif; ?>
</section>
