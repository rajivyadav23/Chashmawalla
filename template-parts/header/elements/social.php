<?php

$footer = false;

if ( isset( $atts ) && isset( $title ) && $title ) {
	echo molla_strip_script_tags( $before_title . sanitize_text_field( $title ) . $after_title );
}

if ( isset( $atts ) ) {
	$footer = true;
}

if ( $footer ) {
	$icon_type = $social_icon_type;
	$icon_size = $social_icon_size;
} else {
	$icon_type = molla_option( 'header_social_type' );
	$icon_size = molla_option( 'header_social_size' );
}

?>
<div class="social-icons<?php echo ( 'colored-simple' == $icon_type || ( 'colored-circle' ) == $icon_type ? ' social-icons-colored' : '' ) . ( 'circle' == $icon_type || ( 'colored-circle' ) == $icon_type ? ' circle-type' : '' ) . ( $icon_size ? ' social-icons-sm' : '' ); ?>">
	<?php

	if ( $footer ) {
		if ( isset( $follow_before ) && $follow_before ) {
			echo do_shortcode( $follow_before );
		}
	} else {
		$atts = molla_option( 'header_social_links' );
	}

	foreach ( $atts as $key => $val ) {
		if ( ! $footer ) {
			$key = $val;
			$val = molla_option( $key );
			$val = $val ? $val : '#';
		}
		if ( $val ) {
			$suffix = '';

			if ( $key == 'facebook' ) {
				$suffix = '-f';
			} elseif ( $key == 'linkedin' ) {
				$suffix = '-in';
			}
			echo '<a href="' . esc_url( $val ) . '"' . ( isset( $nofollow ) && $nofollow ? ' rel="nofollow"' : '' ) . ' class="social-icon social-' . $key . '" title="' . $key . '" target="_blank"><i class="icon-' . $key . $suffix . '"></i></a>';
		}
	}

	if ( $footer ) {
		if ( isset( $follow_after ) && $follow_after ) {
			echo do_shortcode( $follow_after );
		}
	}
	?>
</div>
