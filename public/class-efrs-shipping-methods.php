<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Efrs_Shipping_Method' ) ) {
	class Efrs_Shipping_Method extends WC_Shipping_Method {
		public function __construct() {
			$get_id     = sanitize_text_field(filter_input( INPUT_GET, 'id'));
			$post_title = isset( $get_id ) ? get_the_title( $get_id ) : '';
			
			$method_id    = isset( $get_id ) && ! empty( $get_id ) ? $get_id : 'pisol_extended_flat_shipping';
			$method_title = ! empty( $post_title ) ? $post_title : 'Extended Flat Rate Shipping';
			
			$this->id                 = $method_id;
			$this->method_title       = __( $method_title, 'advanced-free-flat-shipping-woocommerce' ); 
			$this->enabled            = "yes"; 
			$this->title              = __('Extended Flat Rate Shipping', 'advanced-free-flat-shipping-woocommerce'); 

			$this->supports           = array();
			
			$this->init();

			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		}

		function init() {
			$this->init_form_fields(); 
			$this->init_settings(); 
			
		}

		public function calculate_shipping( $package=array() ) {
			$applicable_shipping_methods = $this->matchedShippingMethods($package);
			$free_shipping_coupon_applied = self::isFreeShippingCouponApplied();
			foreach($applicable_shipping_methods as $method){
				$cost = get_post_meta($method->ID, 'pi_cost', true);
				$extra_cost_type = get_post_meta($method->ID, 'pi_extra_cost_calc_type', true);
				$taxable = get_post_meta($method->ID, 'pi_is_taxable', true) === 'no' ? false : "";


				$extra_cost_type = get_post_meta($method->ID, 'pi_extra_cost_calc_type', true);
				$extra_cost = get_post_meta($method->ID, 'shipping_extra_cost', true);
				
				$extra_class_cost = $this->classCostCalculation($package, $extra_cost, $extra_cost_type, $method->ID);

				if($cost != "" & $cost > 0){
					$cost = $cost + $extra_class_cost;
				}else{
					$cost = $extra_class_cost;
				}

				$cost = apply_filters('pi_efrs_add_additional_charges', $cost, $method->ID, $package);

				$make_free_on_free_shipping_coupon = get_post_meta($method->ID, 'pi_free_when_free_shipping_coupon', true);

				if($make_free_on_free_shipping_coupon == 'on' && $free_shipping_coupon_applied){
					$cost = 0;
				}

				if($cost < 0 && apply_filters('pi_efrs_disable_negative_shipping', true)){
					$cost = 0;
				}

				$rate = array(
					'id' => 'pisol_extended_flat_shipping' . ':' . $method->ID,
					'label' => $method->post_title,
					'cost' => $cost,
					'taxes' => $taxable,
					'package' => $package
				);

				// Register the rate
				$this->add_rate( $rate );
				do_action( 'woocommerce_' . $this->id . '_shipping_add_rate', $this,$rate, $package );
			}
		}

		function extraCostAddition($found_shipping_classes, $shipping_method_extra_costs, $extra_cost_type){
			$extra_costs = array();
			if(is_array($found_shipping_classes) && count($found_shipping_classes) > 0){
				foreach($found_shipping_classes as $key => $found_shipping_class){
					if( !empty($shipping_method_extra_costs)  && is_array($shipping_method_extra_costs) ){
					foreach($shipping_method_extra_costs as $id => $cost){
						if($id === $key){
							if($cost != "" && is_numeric($cost)){
								$extra_costs[] = (float)$cost;
							}
						}
					}
					}
				}
			}
			if(count($extra_costs) > 0){
				if($extra_cost_type == 'class'){
					return array_sum($extra_costs);
				}else{
					return max($extra_costs);
				}
			}else{
				return 0;
			}
		}

		function matchedShippingMethods($package){
			$matched_methods = array();
			$args         = array(
				'post_type'      => 'pi_shipping_method',
				'posts_per_page' => - 1
			);
			$all_methods        = get_posts( $args );
			foreach ( $all_methods as $method ) {

				if(!pisol_efrs_CurrencyValid($method->ID)) continue;
				
				$is_match = $this->matchConditions( $method, $package );

				if ( $is_match === true ) {
					$matched_methods[] = $method;
				}
			}

			return $matched_methods;
		}

		public function matchConditions( $method = array(), $package = array() ) {

			if ( empty( $method ) ) {
				return false;
			}

			if ( ! empty( $method ) ) {
				$method_eval_obj = new Pisol_efrs_method_evaluation($method, $package);
				$final_condition_match = $method_eval_obj->finalResult();

				if ( $final_condition_match ) {
					return true;
				}
			}

			return false;
		}

		public function find_shipping_classes( $package ) {
			$found_shipping_classes = array();
	
			foreach ( $package['contents'] as $item_id => $values ) {
				if ( $values['data']->needs_shipping() ) {
					$found_class = $values['data']->get_shipping_class();
	
					if ( ! empty($found_class) ) {
						$found_shipping_classes[ $found_class ][ $item_id ] = $values;
					}else{
						$found_shipping_classes[ 'pi-no-shipping-class' ][ $item_id ] = $values;
					}
	
					
				}
			}
	
			return $found_shipping_classes;
		}

		function classCostCalculation($package, $extra_cost, $extra_cost_type, $shipping_method_id){
			$shipping_classes = WC()->shipping()->get_shipping_classes();
			$class_total_cost = 0;
			if ( ! empty( $shipping_classes ) ) {
				$found_shipping_classes = $this->find_shipping_classes( $package );
				$highest_class_cost     = 0;

				foreach ( $found_shipping_classes as $shipping_class => $products ) {
					// Also handles BW compatibility when slugs were used instead of ids.
					
					if($shipping_class != 'pi-no-shipping-class'){
						$shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );

						$default_lang = apply_filters('wpml_default_language', NULL );
						$shipping_class_id = pisol_wpml_affsw_object($shipping_class_term->term_id, 'product_shipping_class', $default_lang);
					}else{
						$shipping_class_id = 'pi-no-shipping-class';
					}
						
						$class_cost_string   = isset($extra_cost[$shipping_class_id]) ? $extra_cost[$shipping_class_id] : "";

					if ( '' === $class_cost_string ) {
						continue;
					}

					$has_costs  = true;
					$class_cost = $this->evaluate_cost(
						$class_cost_string,
						array(
							'qty'  => array_sum( wp_list_pluck( $products, 'quantity' ) ),
							'cost' => array_sum( wp_list_pluck( $products, 'line_total' ) ),
						)
					);

					if ( 'class' === $extra_cost_type ) {
						$class_total_cost += $class_cost;
					} else {
						$highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
					}
				}

				if ( 'order' === $extra_cost_type && $highest_class_cost ) {
					$class_total_cost += $highest_class_cost;
				}
			}
			return apply_filters('pi_efrs_class_cost_total',$class_total_cost, $shipping_method_id);
		}

		static function isFreeShippingCouponApplied(){
			$free_shipping_coupon_present = false;
			if(function_exists('WC') && is_object(WC()->cart)){
				$codes = WC()->cart->get_applied_coupons();
				foreach( $codes as $code){
					$coupon_obj = new WC_Coupon($code);
					if($coupon_obj->get_free_shipping()){
						$free_shipping_coupon_present = true;
					}
				}
			}
			return $free_shipping_coupon_present;
		}


	/**
		 * function taken from woocommerce / includes / shipping / flat_rate / class-wc-shipping-flat-rate.php
		 * https://docs.woocommerce.com/document/flat-rate-shipping/
		 * https://github.com/woocommerce/woocommerce/blob/9431b34f0dc3d1ed7b45807ffde75de4bb58f831/includes/shipping/flat-rate/class-wc-shipping-flat-rate.php
		 */
		protected function evaluate_cost( $sum, $args = array() ) {
			// Add warning for subclasses.
			if ( ! is_array( $args ) || ! array_key_exists( 'qty', $args ) || ! array_key_exists( 'cost', $args ) ) {
				wc_doing_it_wrong( __FUNCTION__, '$args must contain `cost` and `qty` keys.', '4.0.1' );
			}
	
			include_once WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php';
	
			// Allow 3rd parties to process shipping cost arguments.
			$args           = apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $sum, $this );
			$locale         = localeconv();
			$decimals       = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',' );
	
			// Expand shortcodes.
	
			$sum = do_shortcode(
				str_replace(
					array(
						'[qty]',
						'[cost]',
					),
					array(
						$args['qty'],
						$args['cost'],
					),
					$sum
				)
			);
	
	
			// Remove whitespace from string.
			$sum = preg_replace( '/\s+/', '', $sum );
	
			// Remove locale from string.
			$sum = str_replace( $decimals, '.', $sum );
	
			// Trim invalid start/end characters.
			$sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );
	
			// Do the math.
			return $sum ? WC_Eval_Math::evaluate( $sum ) : 0;
		}
	}
}
