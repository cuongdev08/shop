<?php
/**
 * Alpha_Product_Ordering class
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @version    1.0
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Product_Ordering' ) ) {
	/**
	 * Alpha Product Ordering Class
	 *
	 * @since 1.0
	 */
	class Alpha_Product_Ordering {

		/**
		 * The orderby
		 *
		 * Sort retrieved posts by parameter.
		 * @var   string
		 * @since 1.0
		 */
		public $orderby;

		/**
		 * The order
		 * Designates the ascending or descending order of the 'orderby' parameter.
		 *
		 * @var   string
		 * @since 1.0
		 */
		public $order;

		/**
		 * The order from
		 *
		 * @var   string
		 * @since 1.0
		 */
		public $order_from;

		/**
		 * The order to
		 *
		 * @var   string
		 * @since 1.0
		 */
		public $order_to;

		/**
		 * Hide out date.
		 *
		 * @var   string
		 * @since 1.0
		 */
		public $hide_out_date;

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			// manage custom orderbys.
			add_filter( 'woocommerce_get_catalog_ordering_args', array( $this, 'custom_order_args' ), 10, 3 );

			/**
			 * Fires after product ordering.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_after_product_ordering', $this );
		}

		/**
		 * Custom order args.
		 *
		 * @since 1.0
		 */
		public function custom_order_args( $args, $orderby, $order ) {
			$this->order_from    = isset( $_GET['order_from'] ) ? sanitize_text_field( str_replace( '_', ' ', $_GET['order_from'] ) ) : get_query_var( 'order_from' );
			$this->order_to      = isset( $_GET['order_to'] ) ? sanitize_text_field( str_replace( '_', ' ', $_GET['order_to'] ) ) : get_query_var( 'order_to' );
			$this->hide_out_date = isset( $_GET['hide_out_date'] ) ? sanitize_text_field( $_GET['hide_out_date'] ) : get_query_var( 'hide_out_date' );

			switch ( $orderby ) {
				case 'popularity': // Order by popularity in fixed duration
					if ( $this->order_from || $this->order_to ) {
						add_filter( 'posts_clauses', array( $this, 'order_popularity_date' ) );
					}
					break;
				case 'wishqty': // Order By wishlist count
					if ( defined( 'YITH_WCWL' ) ) {
						$this->order = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : $order;
						add_filter( 'posts_clauses', array( $this, 'order_wish_count' ) );
					}
					break;
				case 'sale_date_to': // Order By End date of Sale Products
					$this->order = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : $order;
					add_filter( 'posts_clauses', array( $this, 'order_sale_end_date' ) );
					break;
				case 'sale_date_from': // Order By Start date of Sale Products
					$this->order = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : $order;
					add_filter( 'posts_clauses', array( $this, 'order_sale_start_date' ) );
					break;
				default: // Order by Created Date if start date or enddate is set
					if ( $this->order_from || $this->order_to ) {
						add_filter( 'posts_clauses', array( $this, 'order_created_date' ) );
					}
					break;
			}

			return $args;
		}

		/**
		 * Order by popularity in fixed duration
		 *
		 * @param $args
		 * @since 1.0
		 */
		public function order_popularity_date( $args ) {
			if ( false !== strpos( $args['where'], "post_type = 'product'" ) ) {
				global $wpdb;

				$this->parse_date();

				if ( ! isset( $args['join'] ) ) {
					$args['join'] = '';
				}

				$where = '';
				if ( $this->order_from ) {
					$where .= $wpdb->prepare(
						' date_created >= %s',
						$this->order_from
					);
				}
				if ( $this->order_to ) {
					$where .= $wpdb->prepare(
						( $where ? ' AND ' : '' ) . ' date_created <= %s',
						$this->order_to
					);
				}

				if ( ! strstr( $args['join'], '{$wpdb->prefix}wc_order_product_lookup' ) ) {
					$args['join'] .= ' ' . ( 'yes' == $this->hide_out_date ? 'RIGHT' : 'LEFT' ) . " JOIN ( SELECT *, SUM(product_qty) AS sales_duration FROM {$wpdb->prefix}wc_order_product_lookup WHERE " . $where . " GROUP BY product_id ) AS wc_order_product_lookup ON $wpdb->posts.ID = wc_order_product_lookup.product_id ";
				}

				$args['orderby'] = " wc_order_product_lookup.sales_duration DESC, $wpdb->posts.post_title";
			}

			remove_filter( 'posts_clauses', array( $this, 'order_popularity_date' ) );

			/**
			 * Filters product ordered by popularity in woocommerce.
			 *
			 * @since 1.0
			 */
			return apply_filters( 'alpha_wc_order_popularity_date', $args );
		}

		/**
		 * Order By Wishlist Count
		 *
		 * @since 1.0
		 */
		public function order_wish_count( $args ) {
			if ( false !== strpos( $args['where'], "post_type = 'product'" ) ) {
				global $wpdb;

				$this->parse_date();

				if ( ! strstr( $args['join'], "{$wpdb->prefix}yith_wcwl" ) ) {
					$args['join'] .= ' ' . ( 'yes' == $this->hide_out_date ? 'RIGHT' : 'LEFT' ) . " JOIN (SELECT prod_id, COUNT(quantity) AS yith_count FROM {$wpdb->prefix}yith_wcwl GROUP BY prod_id ) AS yith_wcwl ON {$wpdb->prefix}posts.ID = yith_wcwl.prod_id ";
				}
				$args['orderby'] = ' yith_wcwl.yith_count ' . ( 'ASC' == $this->order ? 'ASC' : 'DESC' ) . ", {$wpdb->prefix}posts.post_title";
			}

			// remove_filter( 'posts_clauses', array( $this, 'order_wish_count' ) );

			/**
			 * Filters product ordered by wishlist count.
			 */
			return apply_filters( 'alpha_wc_order_wish_count', $args );
		}

		/**
		 * Order By End date of Sale Products
		 *
		 * @since 1.0
		 */
		public function order_sale_end_date( $args ) {
			if ( false !== strpos( $args['where'], "post_type = 'product'" ) ) {
				global $wpdb;

				$this->parse_date();

				$where = '';
				if ( $this->order_from ) {
					$where .= $wpdb->prepare(
						' AND meta_value >= %d',
						strtotime( $this->order_from )
					);
				}
				if ( $this->order_to ) {
					$where .= $wpdb->prepare(
						' AND meta_value <= %d',
						strtotime( $this->order_to )
					);
				}

				if ( ! strstr( $args['join'], "{$wpdb->prefix}postmeta" ) ) {
					$args['join'] .= ' ' . ( 'yes' == $this->hide_out_date ? 'RIGHT' : 'LEFT' ) . " JOIN (SELECT post_id, meta_value AS _sale_price_dates_to FROM {$wpdb->prefix}postmeta where meta_key='_sale_price_dates_to'" . $where . " GROUP BY post_id ) AS postmeta ON $wpdb->posts.ID = postmeta.post_id ";
				}
				$args['orderby'] = ' postmeta._sale_price_dates_to ' . ( 'ASC' == $this->order ? 'ASC' : 'DESC' ) . ", $wpdb->posts.post_title";
			}

			remove_filter( 'posts_clauses', array( $this, 'order_sale_end_date' ) );

			/**
			 * Filters product ordered by end date of sale products.
			 *
			 * @since 1.0
			 */
			return apply_filters( 'alpha_wc_order_sale_end_date', $args );
		}

		/**
		 * Order By Start date of Sale Products
		 *
		 * @since 1.0
		 */
		public function order_sale_start_date( $args ) {
			if ( false !== strpos( $args['where'], "post_type = 'product'" ) ) {
				global $wpdb;

				$this->parse_date();

				$where = '';
				if ( $this->order_from ) {
					$where .= $wpdb->prepare(
						' AND meta_value >= %d',
						strtotime( $this->order_from )
					);
				}
				if ( $this->order_to ) {
					$where .= $wpdb->prepare(
						' AND meta_value <= %d',
						strtotime( $this->order_to )
					);
				}

				if ( ! strstr( $args['join'], "{$wpdb->prefix}postmeta" ) ) {
					$args['join'] .= ' ' . ( 'yes' == $this->hide_out_date ? 'RIGHT' : 'LEFT' ) . " JOIN (SELECT post_id, meta_value AS _sale_price_dates_from FROM {$wpdb->prefix}postmeta where meta_key='_sale_price_dates_from'" . $where . " GROUP BY post_id ) AS postmeta ON $wpdb->posts.ID = postmeta.post_id ";
				}
				$args['orderby'] = ' postmeta._sale_price_dates_from ' . ( 'ASC' == $this->order ? 'ASC' : 'DESC' ) . ", $wpdb->posts.post_title";
			}

			remove_filter( 'posts_clauses', array( $this, 'order_sale_start_date' ) );

			/**
			 * Filters product ordered by start date of sale products.
			 *
			 * @since 1.0
			 */
			return apply_filters( 'alpha_wc_order_sale_start_date', $args );
		}

		/**
		 * Order By Created Date
		 *
		 * @since 1.0
		 */
		public function order_created_date( $args ) {
			if ( false !== strpos( $args['where'], "post_type = 'product'" ) ) {
				global $wpdb;

				$this->parse_date();

				$where = '';
				if ( $this->order_from ) {
					$where .= $wpdb->prepare(
						' AND post_date >= %s',
						$this->order_from
					);
				}
				if ( $this->order_to ) {
					$where .= $wpdb->prepare(
						' AND post_date <= %s',
						$this->order_to
					);
				}

				$args['where'] .= $where;
			}

			remove_filter( 'posts_clauses', array( $this, 'order_sale_start_date' ) );

			/**
			 * Filters product ordered by created date.
			 *
			 * @since 1.0
			 */
			return apply_filters( 'alpha_wc_order_create_date', $args );
		}

		/**
		 * Parse date.
		 *
		 * @since 1.0
		 */
		protected function parse_date() {
			if ( $this->order_from ) {
				if ( 'today' == $this->order_from ) {
					$this->order_from = date( 'Y-m-d', strtotime( 'today' ) );
				} elseif ( 'week' == $this->order_from ) {
					$this->order_from = date( 'Y-m-d', strtotime( 'this Monday' ) );
				} elseif ( 'month' == $this->order_from ) {
					$this->order_from = date( 'Y-m-01' );
				} elseif ( 'year' == $this->order_from ) {
					$this->order_from = date( 'Y-01-01' );
				}
			}
			if ( $this->order_to ) {
				if ( 'today' == $this->order_to ) {
					$this->order_to = date( 'Y-m-d', strtotime( 'tomorrow' ) );
				} elseif ( 'week' == $this->order_to ) {
					$this->order_to = date( 'Y-m-d', strtotime( 'this Sunday' ) );
				} elseif ( 'month' == $this->order_to ) {
					$this->order_to = date( 'Y-m-01', mktime( 0, 0, 0, date( 'm' ) + 1, date( 'd' ), date( 'Y' ) ) );
				} elseif ( 'year' == $this->order_to ) {
					$this->order_to = date( 'Y-01-01', mktime( 0, 0, 0, date( 'm' ), date( 'd' ), date( 'Y' ) + 1 ) );
				}
			}
		}

		/**
		 * Clear var
		 *
		 * @since 1.0
		 */
		protected function clear_var() {
			$this->orderby       = '';
			$this->order         = '';
			$this->order_from    = '';
			$this->order_to      = '';
			$this->hide_out_date = '';
		}
	}
}

// new Alpha_Product_Ordering();
