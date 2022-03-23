<?php
echo sprintf( '<%1$s class="entry-title">', isset( $is_single ) ? 'h2' : 'h4' );
	echo ( ! isset( $is_single ) ? ( '<a href="' . get_the_permalink() . '">' ) : '' ) . get_the_title() . ( ! isset( $is_single ) ? '</a>' : '' );
echo sprintf( '</%1$s>', isset( $is_single ) ? 'h2' : 'h4' );
