<?php
$header_search = molla_option( 'header_search_style' );
$icon_pos      = molla_option( 'header_search_icon_left' ) ? ' icon-left' : '';
?>
<div class="header-search<?php echo esc_attr( $header_search ? ( ' ' . $header_search ) : '' ) . $icon_pos; ?> header-search-no-radius">
<?php get_search_form( array( 'aria_label' => 'header' ) ); ?>
</div>
