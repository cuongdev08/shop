<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
 */

defined( 'ABSPATH' ) || die;

global $product;

$average_rate     = number_format( $product->get_average_rating(), 1 );
$display_rate     = $average_rate * 20;
$count            = $product->get_review_count();
$review_offcanvas = alpha_get_option( 'product_review_offcanvas' );

if ( ! comments_open() ) {
	return;
}

?>
<div id="reviews" class="woocommerce-Reviews">

	<?php if ( 'section' == apply_filters( 'alpha_single_product_data_tab_type', 'tab' ) ) : ?>
		<h2 class="title-wrapper title-underline woocommerce-Reviews-title">
			<span class="title"><?php echo esc_html( alpha_get_option( 'product_reviews_title' ) ); ?></span>
			<?php if ( $review_offcanvas ) : ?>
				<span class="select-box">
				<?php echo alpha_strip_script_tags( apply_filters( 'alpha_single_product_review_filter_select_html', '' ) ); ?>
				</span>
			<?php endif; ?>
		</h2>
	<?php endif; ?>

	<div id="comments"  data-id = "<?php the_ID(); ?>">
		<div class="row">
			<div class="col-md-4 mb-4 mb-md-0">
				<div class="p-sticky">
					<h4 class="avg-rating-container">
						<mark><?php echo '' . $average_rate; ?></mark>
						<span class="avg-rating">
							<span class="avg-rating-title"><?php esc_html_e( 'Average Rating', 'alpha' ); ?></span>
							<span class="star-rating">
								<span style="width: <?php echo alpha_escaped( $display_rate ) . '%'; ?>;"><?php esc_html_e( 'Rated', 'alpha' ); ?></span>
							</span>
							<span class="ratings-review"><?php echo '(' . $count . ')'; ?></span>
						</span>
					</h4>
					<?php do_action( 'alpha_helpful_recommended', $product ); ?>
					<div class="ratings-list">
						<?php
						$ratings_count      = $product->get_rating_counts();
						$total_rating_value = 0;

						foreach ( $ratings_count as $key => $value ) {
							$total_rating_value += intval( $key ) * intval( $value );
						}

						for ( $i = 5; $i > 0; $i-- ) {
							$rating_value = isset( $ratings_count[ $i ] ) ? $ratings_count[ $i ] : 0;
							?>
							<div class="ratings-item">
								<div class="star-rating">
									<span style="width: <?php echo absint( $i ) * 20 . '%'; ?>"><?php esc_html_e( 'Rated', 'alpha' ); ?></span>
								</div>
								<div class="rating-percent">
									<span style="width: 
									<?php
									if ( ! intval( $rating_value ) == 0 ) {
										echo round( floatval( number_format( ( $rating_value * $i ) / $total_rating_value, 3 ) * 100 ), 1 ) . '%';
									} else {
										echo '0%';
									}
									?>
									;"></span>
								</div>
								<div class="progress-value">
									<?php
									if ( ! intval( $rating_value ) == 0 ) {
										echo round( floatval( number_format( ( $rating_value * $i ) / $total_rating_value, 3 ) * 100 ), 1 ) . '%';
									} else {
										echo '0%';
									}
									?>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>
			<div class="col-md-8 mb-4 mb-md-0">
			<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
				<div id="review_form_wrapper" <?php echo ! $review_offcanvas ? '' : 'class="offcanvas"'; ?>>
					<?php echo ! $review_offcanvas ? '' : '<div class="offcanvas-overlay"></div>'; ?>
					<div id="review_form" <?php echo ! $review_offcanvas ? '' : 'class="offcanvas-content"'; ?>>
						<?php
						$commenter    = wp_get_current_commenter();
						$comment_form = array(
							/* translators: %s is product title */
							'title_reply'         => have_comments() ? esc_html__( 'Submit Your Review', 'alpha' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'alpha' ), get_the_title() ),
							/* translators: %s is product title */
							'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'alpha' ),
							'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
							'title_reply_after'   => '</span>',
							'comment_notes_after' => '',
							'label_submit'        => esc_html__( 'Submit', 'alpha' ),
							'logged_in_as'        => '',
							'comment_field'       => '',
						);

						$name_email_required = (bool) get_option( 'require_name_email', 1 );
						$fields              = array(
							'author' => array(
								'label'    => __( 'Name', 'alpha' ),
								'type'     => 'text',
								'value'    => $commenter['comment_author'],
								'required' => $name_email_required,
							),
							'email'  => array(
								'label'    => __( 'Email', 'alpha' ),
								'type'     => 'email',
								'value'    => $commenter['comment_author_email'],
								'required' => $name_email_required,
							),
						);

						$comment_form['fields'] = array();

						foreach ( $fields as $key => $field ) {
							$field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
							$field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] );

							if ( $field['required'] ) {
								$field_html .= '&nbsp;<span class="required">*</span>';
							}

							$field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

							$comment_form['fields'][ $key ] = $field_html;
						}

						$account_page_url = wc_get_page_permalink( 'myaccount' );
						if ( $account_page_url ) {
							/* translators: %s opening and closing link tags respectively */
							$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'alpha' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
						}

						if ( wc_review_ratings_enabled() ) {
							$comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your Rating Of This Product', 'alpha' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">:</span>' : '' ) . '</label><select name="rating" id="rating" required>
								<option value="">' . esc_html__( 'Rate&hellip;', 'alpha' ) . '</option>
								<option value="5">' . esc_html__( 'Perfect', 'alpha' ) . '</option>
								<option value="4">' . esc_html__( 'Good', 'alpha' ) . '</option>
								<option value="3">' . esc_html__( 'Average', 'alpha' ) . '</option>
								<option value="2">' . esc_html__( 'Not that bad', 'alpha' ) . '</option>
								<option value="1">' . esc_html__( 'Very poor', 'alpha' ) . '</option>
							</select></div>';
						}

						$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'alpha' ) . '&nbsp;<span class="required"></span></label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>';

						comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
						?>
					</div>
				</div>
			<?php else : ?>
				<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'alpha' ); ?></p>
			<?php endif; ?>

			<?php if ( have_comments() && ! $review_offcanvas ) : ?>
				<div class="review-header">
					<ul id="alpha_comment_tabs" class="alpha-comment-tabs nav nav-tabs tab-nav-solid" role="tablist">
						<li class="nav-item" role="tab" aria-controls="commentlist">
							<span data-href="#commentlist" class="nav-link type-filter active" data-type="all"><?php esc_html_e( 'All Reviews', 'alpha' ); ?></span>
						</li>
						<li class="nav-item" role="tab" aria-controls="commentlist-with-images">
							<span data-href="#commentlist-with-images" data-type="images" class="nav-link type-filter">
								<?php esc_html_e( 'With Images', 'alpha' ); ?>
							</span>
						</li>
						<li class="nav-item" role="tab" aria-controls="commentlist-with-video">
							<span data-href="#commentlist-with-video" data-type="videos" class="nav-link type-filter">
								<?php esc_html_e( 'With Videos', 'alpha' ); ?>
							</span>
						</li>
					</ul>
				<?php

				// @start feature: fs_addon_product_helpful_comments
				if ( alpha_get_feature( 'fs_addon_product_helpful_comments' ) ) {
					?>
					<span class="select-box" data-id="<?php echo esc_attr( $product->get_id() ); ?>">
						<span><?php esc_html_e( 'Sort By:', 'alpha' ); ?></span>
						<select name="order" class="order-select form-control" aria-label="Review Order" data-filter="order">
							<option value="default"><?php esc_html_e( 'Default', 'alpha' ); ?></option>
							<option value="newest"><?php esc_html_e( 'Newest Comment', 'alpha' ); ?></option>
							<option value="oldest"><?php esc_html_e( 'Oldest Comment', 'alpha' ); ?></option>
							<option value="highest"><?php esc_html_e( 'Highest Rating', 'alpha' ); ?></option>
							<option value="lowest"><?php esc_html_e( 'Lowest Rating', 'alpha' ); ?></option>
							<option value="helpful"><?php esc_html_e( 'Helpful Comment', 'alpha' ); ?></option>
							<option value="unhelpful"><?php esc_html_e( 'Unhelpful Comment', 'alpha' ); ?></option>
						</select>
					</span>	
				</div>
						<?php
				}
					// @end feature: fs_addon_product_helpful_comments
				?>
				<div class="tab-content tab-templates">
					<ol id="commentlist" class="commentlist tab-pane active">
						<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
					</ol>
					<?php
					// @start feature: fs_addon_product_helpful_comments
					if ( alpha_get_feature( 'fs_addon_product_helpful_comments' ) ) {
						?>
					<ol id="commentlist-with-images" class="commentlist tab-pane" data-empty="<li class='review review-empty'><?php esc_html_e( 'No image review exists.', 'alpha' ); ?></li>"></ol>
					<ol id="commentlist-with-video" class="commentlist tab-pane" data-empty="<li class='review review-empty'><?php esc_html_e( 'No video review exists.', 'alpha' ); ?></li>"></ol>
						<?php
					}
					// @end feature: fs_addon_product_helpful_comments
					?>
				</div>

					<?php

					if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
						echo '<nav class="woocommerce-pagination pagination">';

						$args = apply_filters(
							'woocommerce_comment_pagination_args',
							array(
								'echo'      => false,
								'prev_text' => '<i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-left"></i> ' . esc_html__( 'Prev', 'alpha' ),
								'next_text' => esc_html__( 'Next', 'alpha' ) . ' <i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-right"></i>',
							)
						);

						$links = paginate_comments_links( $args );
						if ( $links ) {

							if ( 1 === $page ) {
								$links = sprintf(
									'<span class="prev page-numbers disabled">%s</span>',
									$args['prev_text']
								) . $links;
							} elseif ( get_comment_pages_count() == $page ) {
								$links .= sprintf(
									'<span class="next page-numbers disabled">%s</span>',
									$args['next_text']
								);
							}
						}

						echo alpha_escaped( $links );

						echo '</nav>';
				endif;
					?>
			<?php endif; ?>

			</div>
		</div>

	</div>

	<div class="clear"></div>
</div>
