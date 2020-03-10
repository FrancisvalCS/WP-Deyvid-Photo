<div class="cbp-panel">

	<?php if( 'yes' == $wp_query->show_filters ) { ?>
	<div id="js-filters-full-width" class="cbp-filter-container text-center">
		<div data-filter="*" class="cbp-filter-item-active cbp-filter-item"> <?php esc_html_e( 'All', 'lydia' ); ?> </div>
		<?php
			$cats = get_categories( 'taxonomy=portfolio_category' );
			
			if(is_array($cats)){
				foreach($cats as $cat){
					echo '<div data-filter=".'. esc_attr( $cat->slug ) .'" class="cbp-filter-item"> '. $cat->name .' </div> ';
				}
			}
		?>
	</div>
	<?php } ?>
	
	<div id="js-grid-full-width" class="cbp">
		<?php 
			if ( have_posts() ) : while ( have_posts() ) : the_post();
				
				/**
				 * Get blog posts by blog layout.
				 */
				get_template_part( 'loop/content-portfolio', 'full' );
			
			endwhile;	
			else : 
				
				/**
				 * Display no posts message if none are found.
				 */
				get_template_part( 'loop/content', 'none' );
				
			endif;
		?>
	</div>

</div>