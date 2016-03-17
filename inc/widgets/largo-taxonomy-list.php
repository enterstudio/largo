<?php
/*
 * List all of the terms in a custom taxonom
 *
 * This widget takes two primary forms: 1) a <select> element of terms, or 2) a customizable UL of terms.
 */
class largo_taxonomy_list_widget extends WP_Widget {

	/**
	 * Constructor
	 */
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'largo-taxonomy-list',
			'description' 	=> __('List all of the terms in a custom taxonomy with links', 'largo')
		);
		parent::__construct( 'largo-taxonomy-list-widget', __('Largo Taxonomy List', 'largo'), $widget_ops);
	}

	/**
	 * Output the widget
	 *
	 * @param array $args Sidebar-related args
	 * @param array $instance Instance-specific widget arguments
	 * @link https://developer.wordpress.org/reference/functions/get_terms/
	 */
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Categories', 'largo' ) : $instance['title'], $instance, $this->id_base);
		$is_dropdown = ! empty( $instance['dropdown'] ) ? '1' : '0';

		/*
		 * Before the widget
		 */
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		// Set us up the term args
		$term_args = array(
			'taxonomy' => $instance['taxonomy'],
			'number' => $instance['count'],
			'exclude' => $instance['exclude'],
			'orderby' => $instance['orderby'],
			'order' => $instance['order'],
		);
		$defaults = array(
			'taxonomy' => 'series',
			'number' => 5,
			'exclude' => null,
			'orderby' => 'id',
			'order' => 'DESC'
		);
		$term_args = wp_parse_args( $term_args, $defaults );

		/*
		 * The dropdown option
		 */
		if ( $is_dropdown ) {
			$term_args['orderby'] = 'name';
			$terms = get_categories($term_args); ?>

			<select id="taxonomy-list-widget">
				<option value=""><?php printf( __('Select %s', 'largo'), ucwords($instance['taxonomy']) ); ?></option>
			<?php foreach ($terms as $term) : ?>
				<option value="<?php echo get_term_link($term, $term->taxonomy) ?>"><?php echo $term->name ?></option>
			<?php endforeach; ?>
			</select>

			<script>
				jQuery(document).ready(function() {
				    jQuery('#taxonomy-list-widget').change(function() {
				        window.location = jQuery(this).val();
				    });
				});
			</script>

		<?php
		/*
		 * Not the dropdown option
		 */
		} else {
			echo '<ul class="' . $instance['taxonomy'] . '">';

			$tax_items = get_categories($term_args);

			switch ($instance['taxonomy']) {
				case 'series':
					$this->render_series_list($tax_items, $instance);
					break;
				case 'category':
					$this->render_cat_list($tax_items, $instance);
					break;
				case 'post_tag':
					$this->render_tag_list($tax_items, $instance);
					break;
				default:
					$this->render_term_list($tax_items, $instance);
			}

			echo '</ul>';
		}

		echo $after_widget;
	}

	/**
	 * Helper to render an li
	 *
	 * If $thumbnail is empty, then there is simply no image output.
	 * If there is no thumbnail, class .no-thumbnail is added to the <li>.
	 * If there is a thumbnail, class .has-thumbnail is added to the <li>.
	 *
	 * @param Object $item a wordpress taxonomy object
	 * @param str $thumbnail the HTML for the thumbnail image
	 * @param str $headline the HTML for the headline
	 * @private
	 * @since 0.5.3
	 */
	private function render_li($item, $thumbnail = '', $headline = '') {
		echo sprintf(
			'<li class="%s-%s %s"><a href="%s">%s <h5>%s</h5></a> %s</li>',
			$item->taxonomy,
			$item->term_id,
			( $thumbnail != '' ) ? "has-thumbnail" : "no-thumbnail" ,
			get_term_link($item),
			$thumbnail, // the image for the series
			$item->cat_name,
			$headline
		);
	}

	/**
	 * For a series, find a thumbnail in the landing pages or the posts, and create an <li>
	 *
	 * @private
	 * @uses largo_taxonomy_list_widget::render_li
	 * @uses largo_featured_thumbnail_in_post_array
	 * @uses largo_first_headline_in_post_array
	 * @since 0.5.3
	 */
	private function render_series_list($tax_items, $instance) {
		foreach ($tax_items as $item) {
			$thumbnail = '';
			$headline = '';
			$posts = array();

			if ($instance['thumbnails'] == '1' || $instance['use_headline'] == '1') {
				$query_args = array(
					'tax_query' => array(
						array(
							'taxonomy' => $instance['taxonomy'],
							'field' => 'term_id',
							'terms' => $item->term_id,
						),
					),
				);
				$posts = get_posts($query_args);
			}

			if ($instance['thumbnails'] == '1' && largo_is_series_landing_enabled() ) {
				$landing_array = largo_get_series_landing_page_by_series($item);
				
				// Thumbnail shall be the one for the landing page post
				foreach ($landing_array as $post) {
					$thumbnail = get_the_post_thumbnail($post->ID);
				}
			}

			if ($thumbnail == '') {
				$thumbnail = largo_featured_thumbnail_in_post_array($posts);
			}

			if ($instance['use_headline'] == '1') {
				$headline = largo_first_headline_in_post_array($posts);
			}

			$this->render_li($item, $thumbnail, $headline);
		}
	}

	/**
	 * Find the first thumbnailed post in the category and create an <li>
	 *
	 * @private
	 * @uses largo_taxonomy_list_widget::render_li
	 * @uses largo_featured_thumbnail_in_post_array
	 * @uses largo_first_headline_in_post_array
	 * @since 0.5.3
	 */
	private function render_cat_list($tax_items, $instance) {
		foreach ($tax_items as $item) {
			$headline = '';
			$thumbnail = '';
			$posts = array();

			// Only get posts if we're going to use them.
			if ($instance['thumbnails'] == '1' || $instance['use_headline'] == '1') {
				$posts = get_posts(array(
					'category_name' => $item->name,
				));
			}
			if ($instance['thumbnails'] == '1') {
				$thumbnail = largo_featured_thumbnail_in_post_array($posts);
			}
			if ($instance['use_headline'] == '1') {
				$headline = largo_first_headline_in_post_array($posts);
			}
			$this->render_li($item, $thumbnail, $headline);
		}
	}

	/**
	 * For a tag, find the first thumbnailed post and create an <li>
	 *
	 * @private
	 * @uses largo_taxonomy_list_widget::render_li
	 * @uses largo_featured_thumbnail_in_post_array
	 * @uses largo_first_headline_in_post_array
	 * @since 0.5.3
	 */
	private function render_tag_list($tax_items, $instance) {
		foreach ($tax_items as $item) {
			$headline = '';
			$thumbnail = '';
			$posts = array();

			// Only get posts if we're going to use them.
			if ($instance['thumbnails'] == '1' || $instance['use_headline'] == '1') {
				$posts = get_posts(array(
					'tag' => $item->slug,
				));
			}
			if ($instance['thumbnails'] == '1') {
				$thumbnail = largo_featured_thumbnail_in_post_array($posts);
			}
			if ($instance['use_headline'] == '1') {
				$headline = largo_first_headline_in_post_array($posts);
			}
			$this->render_li($item, $thumbnail, $headline);
		}
	}

	/**
	 * For a generic term in a taxonomy, find the first thumbnailed post in the term and create an <li>
	 *
	 * @private
	 * @uses largo_taxonomy_list_widget::render_li
	 * @uses largo_featured_thumbnail_in_post_array
	 * @uses largo_first_headline_in_post_array
	 * @since 0.5.3
	 */
	private function render_term_list($tax_items, $instance) {
		foreach ($tax_items as $item) {
			$thumbnail = '';
			$headline = '';
			$posts = array();

			// Only get posts if we're going to use them.
			if ($instance['thumbnails'] == '1' || $instance['use_headline'] == '1') {
				$query_args = array(
					'tax_query' => array(
						array(
							'taxonomy' => $instance['taxonomy'],
							'field' => 'term_id',
							'terms' => $item->term_id,
						),
					),
				);
				$posts = get_posts($query_args);
			}
			if ($instance['thumbnails'] == '1') {
				$thumbnail = largo_featured_thumbnail_in_post_array($posts);
			}
			if ($instance['use_headline'] == '1') {
				$headline = largo_first_headline_in_post_array($posts);
			}
			$this->render_li($item, $thumbnail, $headline);
		}
	}

	/**
	 * Sanitize and save widget arguments
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['taxonomy'] = isset($new_instance['taxonomy']) ? strtolower(strip_tags($new_instance['taxonomy'])) : 'series' ;
		$instance['orderby'] = isset($new_instance['orderby']) ? strtolower(strip_tags($new_instance['orderby'])) : 'id' ;
		$instance['order'] = isset($new_instance['order']) ? strtolower(strip_tags($new_instance['order'])) : 'DESC' ;
		$instance['order'] = sanitize_text_field($new_instance['order']);
		$instance['count'] = sanitize_text_field($new_instance['count']);

		// Default is 5 as of 0.5.5, not infinite: see discussion on http://jira.inn.org/browse/HELPDESK-589
		if ($instance['count'] == '' ) {
			$instance['count'] = 5;
		} else if ($instance['count'] < 1) {
			$instance['count'] = 1;
		}

		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;
		$instance['thumbnails'] = !empty($new_instance['thumbnails']) ? 1 : 0;
		$instance['use_headline'] = !empty($new_instance['use_headline']) ? 1 : 0;
		$instance['exclude'] = sanitize_text_field($new_instance['exclude']);

		return $instance;
	}

	/**
	 * Render the widget form
	 */
	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'taxonomy' => '' ) );
		$title = esc_attr( $instance['title'] );
		$count = isset($instance['count']) ? esc_attr( $instance['count'] ) : 5;
		$orderby = esc_attr( $instance['orderby'] );
		$order = esc_attr( $instance['order'] );
		$instance['taxonomy'] = isset( $instance['taxonomy'] ) ? $instance['taxonomy'] : 'series';
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
		$thumbnails = isset( $instance['thumbnails'] ) ? (bool) $instance['thumbnails'] : false;
		$use_headline = isset( $instance['use_headline'] ) ? (bool) $instance['use_headline'] : false;
		$exclude = $instance['exclude'];

		// Create <option>s of taxonomies for the <select>
		$taxonomies = get_taxonomies(null, 'objects');
		$taxonomies_options = '';
		foreach ($taxonomies as $taxonomy) {
			if ($taxonomy->public) {
				$taxonomies_options .= sprintf(
					'<option value="%1$s" %2$s>%3$s</option>',
					$taxonomy->name,
					selected( $instance['taxonomy'], $taxonomy->name, false ),
					$taxonomy->label
				);
			}
		}

		// Create <option>s  of sort orderbys for the <select>
		$sort_orderbys = array('id', 'name', 'slug', 'term_id', 'description', 'count'); // list from https://developer.wordpress.org/reference/functions/get_terms/
		$sort_orderby_options = '';
		foreach ( $sort_orderbys as $sort_orderby ) {
			$sort_orderby_options .= sprintf(
				'<option value="%1$s" %2$s>%3$s</option>',
				$sort_orderby,
				selected( $instance['orderby'], $sort_orderby, false ),
				__(ucwords($sort_orderby), 'largo')
			);
		}

		// Create <option>s of sort orders for the <select>
		$sort_orders = array(
			'ASC' => 'Ascending',
			'DESC' => 'Descending'
		); // list from https://developer.wordpress.org/reference/functions/get_terms/
		$sort_order_options = '';
		foreach ( $sort_orders as $order => $label ) {
			$sort_order_options .= sprintf(
				'<option value="%1$s" %2$s>%3$s</option>',
				$order,
				selected( $instance['order'], $order, false ),
				__(ucwords($label), 'largo')
			);
		}

		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'largo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:', 'largo'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>" type="text" value="<?php echo $instance['taxonomy'] ?>">
				<?php echo $taxonomies_options; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order results by:', 'largo'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" type="text" value="<?php echo $instance['orderby'] ?>">
				<?php echo $sort_orderby_options; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order results in which direction?', 'largo'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" type="text" value="<?php echo $instance['order'] ?>">
				<?php echo $sort_order_options; ?>
			</select>
			<small><?php _e('Using the alphabet as an example, ascending order will sort it as <code>a b c d ... x y z</code>. Descending order will sort the alphabet as <code>z y x q ... c b a</code>. Using numbers, an ascending sort will go <code>1 2 234 593 20450</code> and a descending sort will go <code>20450 593 234 2 1</code>.', 'largo'); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e('Do not display the terms in this comma-separated list of term IDs:', 'largo'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" type="text" value="<?php echo $exclude; ?>" />
			<small><?php _e('Find term IDs by examining the URL of the taxonomy when you click the "Edit" button in the term\'s entry in the taxonomy list or on the term\'s archive page. This does not allow you to exclude individual posts. You can exclude the taxonomy that contains the post.', 'largo'); ?>.</small>
		</p>

		<p>
			<label for"<?php echo $this->get_field_id('count'); ?>"><?php _e('Count: (must be greater than 1)', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count')?>" type="number" value="<?php echo $count; ?>" />
			<small><?php _e('The minimum number of terms shown is 1. While the maximum number is the number of terms in the taxonomy, in practice this number should be no larger than 10.', 'largo'); ?></small>
		</p>

		<p>
		</p>

		<p><input type="checkbox" class="checkbox ltlw-dropdown" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
			<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display terms as dropdown', 'largo' ); ?></label>
			<small><?php _e('If you choose to display terms as a dropdown, no thumbnails or headlines will be displayed.', 'largo'); ?></small>
		</p>

		<p><input type="checkbox" class="checkbox ltlw-thumbnails" id="<?php echo $this->get_field_id('thumbnails'); ?>" name="<?php echo $this->get_field_name('thumbnails'); ?>"<?php checked( $thumbnails ); ?> />
			<label for="<?php echo $this->get_field_id('thumbnails'); ?>"><?php _e( 'Display thumbnails?', 'largo' ); ?></label>
		</p>

		<p><input type="checkbox" class="checkbox ltlw-headline" id="<?php echo $this->get_field_id('use_headline'); ?>" name="<?php echo $this->get_field_name('use_headline'); ?>"<?php checked( $use_headline ); ?> />
			<label for="<?php echo $this->get_field_id('use_headline'); ?>"><?php _e( 'Display headline of most-recent post in taxonomy?', 'largo' ); ?></label>
		</p>

		<script>
			jQuery(document).ready(function($) {
				$('.ltlw-dropdown').click(function() {
					$(this).parent().parent().find('.ltlw-thumbnails').removeAttr('checked');
					$(this).parent().parent().find('.ltlw-headline').removeAttr('checked');
				});
				$('.ltlw-thumbnails').click(function() {
					$(this).parent().parent().find('.ltlw-dropdown').removeAttr('checked');
				});
				$('.ltlw-headline').click(function() {
					$(this).parent().parent().find('.ltlw-dropdown').removeAttr('checked');
				});
			});
		</script>

	<?php
	}
}
