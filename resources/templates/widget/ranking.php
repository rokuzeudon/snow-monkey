<?php
/**
 * @package inc2734/snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 */

$items = explode( ',', $instance['items'] );
if ( ! $items ) {
	return;
}

$ranking_posts_query = new WP_Query( [
	'post_type'           => 'any',
	'posts_per_page'      => count( $items ),
	'post__in'            => $items,
	'orderby'             => 'post__in',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
	'suppress_filters'    => true,
] );
?>

<?php echo wp_kses_post( $args['before_widget'] ); ?>

	<?php if ( $instance['title'] ) : ?>
		<?php echo wp_kses_post( $args['before_title'] ); ?>
			<?php echo esc_html( $instance['title'] ); ?>
		<?php echo wp_kses_post( $args['after_title'] ); ?>
	<?php endif; ?>

	<div
		class="wpaw-ranking wpaw-ranking--<?php echo esc_attr( $args['widget_id'] ); ?>"
		id="wpaw-ranking-<?php echo esc_attr( $args['widget_id'] ); ?>"
		>

		<ul class="wpaw-ranking__list">
			<?php while ( $ranking_posts_query->have_posts() ) : ?>
				<?php $ranking_posts_query->the_post(); ?>
				<li class="wpaw-ranking__item">
					<a href="<?php the_permalink(); ?>">

						<?php if ( $instance['show-thumbnail'] ) : ?>
							<div class="wpaw-ranking__figure"
								style="background-image: url(<?php echo esc_url( wp_get_attachment_image_url( get_post_thumbnail_id(), 'thumbnail' ) ); ?> )"
							></div>
						<?php endif; ?>

						<div class="wpaw-ranking__body">
							<?php
							$taxonomies = get_post_taxonomies( get_the_ID() );
							$taxonomy   = ! empty( $taxonomies[0] ) ? $taxonomies[0] : false;
							$terms      = ( $taxonomy ) ? get_the_terms( get_the_ID(), $taxonomy ) : [];
							?>
							<?php if ( $instance['show-taxonomy'] && $terms ) : ?>
								<div class="wpaw-ranking__taxonomy">
									<?php foreach ( $terms as $term ) : ?>
										<span class="wpaw-ranking__term wpaw-ranking__term--category-<?php echo esc_attr( $term->term_id ); ?>"><?php echo esc_html( $term->name ); ?></span>
										<?php break; ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<div class="wpaw-ranking__title"><?php the_title(); ?></div>
							<div class="wpaw-ranking__date"><?php the_time( get_option( 'date_format' ) ); ?></div>
						</div>

					</a>
				</li>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</ul>
	</div>

<?php echo wp_kses_post( $args['after_widget'] ); ?>
