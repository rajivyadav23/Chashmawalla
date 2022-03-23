<?php
$output_steps = $this->steps;
?>
<ul class="molla-admin-panel-menu_list">
<?php foreach ( $output_steps as $step_key => $step ) : ?>
	<?php
	$show_link        = true;
	$li_class_escaped = '';
	if ( $step_key === $this->step ) {
		$li_class_escaped = 'active';
	} elseif ( array_search( $this->step, array_keys( $this->steps ) ) > array_search( $step_key, array_keys( $this->steps ) ) ) {
		$li_class_escaped = 'done';
	}
	if ( $step_key === $this->step || 'next_steps' == $step_key ) {
		$show_link = false;
	}
	?>
	<li class="<?php echo esc_attr( $li_class_escaped ); ?>">
	<?php
	$id_html_escaped = '<span class="step-id">' . esc_html( $step['step_id'] ) . '</span>';
	if ( $show_link ) {
		?>
			<a href="<?php echo esc_url( $this->get_step_link( $step_key ) ); ?>"><?php echo molla_filter_output( $id_html_escaped ) . esc_html( $step['name'] ); ?></a>
			<?php
	} else {
		echo molla_filter_output( $id_html_escaped ) . esc_html( $step['name'] );
	}
	?>
		</li>
<?php endforeach; ?>
</ul>
</div>
<div class="molla-admin-panel-content molla-setup-content">