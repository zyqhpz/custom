<?php
/**
 * The admin specific functionality for WooCommerce RRP.
 *
 * @author     Bradley Davis
 * @package    WooCommerce_RRP
 * @subpackage WooCommerce_RRP/admin
 * @since      3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) :
	exit; // Exit if accessed directly.
endif;

/**
 * Admin parent class that pulls everything together.
 *
 * @since 3.0.0
 */
class WC_UOM_Admin {
	/**
	 * The Constructor.
	 *
	 * @since 3.0.0
	 */
	public function __construct() {
		$this->wc_uom_admin_activate();
	}

	/**
	 * Add all filter type actions.
	 *
	 * @since 3.0.0
	 */
	public function wc_uom_admin_activate() {
		add_action( 'woocommerce_product_options_inventory_product_data', array( $this, 'wc_uom_product_fields' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'wc_uom_save_field_input' ) );
		
		// uom input text field from Dokan dashboard
		add_action( 'uom_dokan_fields', array( $this, 'wc_uom_dokan_fields' ) );
		add_action( 'dokan_process_product_meta', array( $this, 'wc_uom_save_field_input' ) );
		
		// product knowledge settings in WP admin
		add_action( 'woocommerce_extra_product_knowledge_fields', array( $this, 'wc_extra_product_knowledge_fields' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'extra_product_knowledge_meta_fields_save' ) );
		
		// product knowledge settings in Dokan dashboard
		add_action( 'dokan_extra_product_knowledge', array( $this, 'dokan_extra_product_knowledge_fields' ) );
		add_action( 'dokan_process_product_meta', array( $this, 'extra_product_knowledge_meta_fields_save' ) );
		
		add_action( 'woocommerce_after_add_to_cart_quantity', array( $this, 'show_uom_product_page' ) );
	}

	/**
	 * Add the custom fields or the UOM to the prodcut general tab.
	 *
	 * @since 3.0.0
	 */
	public function wc_uom_product_fields() {
		// Security..... make sure the form request comes from the right place people.
		wp_nonce_field( basename( __FILE__ ), 'wc_uom_product_fields_nonce' );

		echo '<div class="wc_uom_input">';
			// Woo_UOM fields will be created here.
			woocommerce_wp_text_input(
				array(
					'id'          => '_woo_uom_input',
					'label'       => __( 'Unit of Measure', 'woo_uom' ),
					'placeholder' => '',
					'desc_tip'    => 'true',
					'description' => __( 'Enter your unit of measure for this product here.', 'woo_uom' ),
				)
			);
		echo '</div>';
	}

	/**
	 * Update the database with the new input
	 *
	 * @since 1.0
	 * @param int $post_id Used to save the input field to a specific id.
	 */
	public function wc_uom_save_field_input( $post_id ) {
		if ( isset( $_POST['_woo_uom_input'], $_POST['wc_uom_product_fields_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['wc_uom_product_fields_nonce'] ), basename( __FILE__ ) ) ) :
			$woo_uom_input = sanitize_text_field( wp_unslash( $_POST['_woo_uom_input'] ) );
			update_post_meta( $post_id, '_woo_uom_input', esc_attr( $woo_uom_input ) );
		endif;
	}
	/**
	 * new input text-field for uom in Dokan dashboard
	 * 
	 * customized by Haziq (02/02/2022)
	 */
	public function wc_uom_dokan_fields( $post_id ) {
		// Security..... make sure the form request comes from the right place people.
		wp_nonce_field( basename( __FILE__ ), 'wc_uom_product_fields_nonce' );

		echo '<div class="wc_uom_input">';
		    ?> <label for="_woo_uom_input" class="form-label"><?php esc_html_e( 'UOM', 'dokan-lite' ); ?></label> <?php
			dokan_post_input_box( $post_id, '_woo_uom_input' );
		echo '</div>';
	}
	
	/**
	 * Update database with the new product knowledge input
	 * 
	 * customized by Haziq (01/02/2022)
	 */
		public function extra_product_knowledge_meta_fields_save( $post_id ) {
		if ( isset( $_POST['_plastic_number'] ) ) {
			$woo_data = $_POST['_plastic_number'];
			update_post_meta( $post_id, '_plastic_number', $woo_data );
		}

		if ( isset( $_POST['_lifespan_duration'] ) ) {
			$woo_data = $_POST['_lifespan_duration'];
			update_post_meta( $post_id, '_lifespan_duration', $woo_data );
		}

		if ( isset( $_POST['_lifespan_time'] ) ) {
			$woo_data = $_POST['_lifespan_time'];
			update_post_meta( $post_id, '_lifespan_time', $woo_data );
		}
		
		if ( isset( $_POST['_bpa_free'] ) ) {
			$woo_data = $_POST['_bpa_free'];
			update_post_meta( $post_id, '_bpa_free', $woo_data );
		}

		if ( isset( $_POST['_reusable'] ) ) {
			$woo_data = $_POST['_reusable'];
			update_post_meta( $post_id, '_reusable', $woo_data );
		}
		
		if ( isset( $_POST['_temperature'] ) ) {
			$woo_data = $_POST['_temperature'];
			update_post_meta( $post_id, '_temperature', $woo_data );
		}
		
		if ( isset( $_POST['_microwave_safe'] ) ) {
			$woo_data = $_POST['_microwave_safe'];
			update_post_meta( $post_id, '_microwave_safe', $woo_data );
		}
		
		if ( isset( $_POST['_recycle_material'] ) ) {
			$woo_data = $_POST['_recycle_material'];
			update_post_meta( $post_id, '_recycle_material', $woo_data );
		}
		
		if ( isset( $_POST['_percentage_recyclable'] ) ) {
			$woo_data = $_POST['_percentage_recyclable'];
			update_post_meta( $post_id, '_percentage_recyclable', $woo_data );
		}
		
		if ( isset( $_POST['_recyclable'] ) ) {
			$woo_data = $_POST['_recyclable'];
			update_post_meta( $post_id, '_recyclable', $woo_data );
		}

		if ( isset( $_POST['_disposable'] ) ) {
			$woo_data = $_POST['_disposable'];
			update_post_meta( $post_id, '_disposable', $woo_data );
		}

		if ( isset( $_POST['_biodegradable'] ) ) {
			$woo_data = $_POST['_biodegradable'];
			update_post_meta( $post_id, '_biodegradable', $woo_data );
		}

		if ( isset( $_POST['_content_formula'] ) ) {
			$woo_data = $_POST['_content_formula'];
			update_post_meta( $post_id, '_content_formula', $woo_data );
		}

		if ( isset( $_POST['_raw_material'] ) ) {
			$woo_data = $_POST['_raw_material'];
			update_post_meta( $post_id, '_raw_material', $woo_data );
		}
	}

	/**
	 * add text field for product knowledge input in WP admin
	 * 
	 * customized by Haziq (01/02/2022)
	 */
	public function wc_extra_product_knowledge_fields() {
		echo '<h2>' . esc_html__( 'Product Knowledge', 'martfury' ) . '</h2>';

		woocommerce_wp_text_input(
			array(
				'id'       => '_plastic_number',
				'type'     => 'number',
				'label'    => esc_html__( 'Plastic number (1-7)', 'martfury' ),
				'desc_tip' => esc_html__( 'Enter plastic number of the product.', 'martfury' ),
				'min'      => 1, // fix this
				'max'      => 7,
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'       => '_lifespan',
				'type'	   => 'number',
				'label'    => esc_html__( 'Product lifespan', 'martfury' ),
				'desc_tip' => esc_html__( 'Enter lifespan of the product.', 'martfury' ),
				'min'	  => 0,
			)
		);

		woocommerce_wp_select(
			array(
				'id'          => '_lifespan_select',
				'label'       => esc_html__( '', 'martfury' ),
				'options' => array(
					''  => esc_html__( 'Select timeframe', 'martfury' ),
					'day'  => esc_html__( 'Days', 'martfury' ),
					'month' => esc_html__( 'Months', 'martfury' ),
					'year'  => esc_html__( 'Years', 'martfury' ),
				)
			)
		);

		woocommerce_wp_radio(
			array(
				'id'          => '_bpa_free',
				'label'       => esc_html__( 'BPA Free?', 'martfury' ),
				'options' => array(
					'yes' => esc_html__( 'Yes', 'martfury' ),
					'no'  => esc_html__( 'No', 'martfury' ),
					'undefined'  => esc_html__( 'Not Applicable', 'martfury' ),
				)
			)
		);

		woocommerce_wp_radio(
			array(
				'id'          => '_reusable',
				'label'       => esc_html__( 'Safe to reuse?', 'martfury' ),
				'options' => array(
					'yes' => esc_html__( 'Yes', 'martfury' ),
					'no'  => esc_html__( 'No', 'martfury' ),
					'undefined'  => esc_html__( 'Not Applicable', 'martfury' ),
				)
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'       => '_temperature',
				'type'	   => 'number',
				'label'    => esc_html__( 'Temperature resistance (°C)', 'martfury' ),
				'desc_tip' => esc_html__( 'Enter temperature resistance.', 'martfury' ),
			)
		);

		woocommerce_wp_radio(
			array(
				'id'          => '_microwave_safe',
				'label'       => esc_html__( 'Microwave safe?', 'martfury' ),
				'options' => array(
					'yes' => esc_html__( 'Yes', 'martfury' ),
					'no'  => esc_html__( 'No', 'martfury' ),
					'undefined'  => esc_html__( 'Not Applicable', 'martfury' ),
				)
			)
		);

		woocommerce_wp_radio(
			array(
				'id'          => '_recycle_material',
				'label'       => esc_html__( 'Recycle material?', 'martfury' ),
				'options' => array(
					'yes' => esc_html__( 'Yes', 'martfury' ),
					'no'  => esc_html__( 'No', 'martfury' ),
					'undefined'  => esc_html__( 'Not Applicable', 'martfury' ),
				)
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'          => '_percentage_recyclable',
				'label'       => esc_html__( 'Percentage of recycle material? (%)', 'martfury' ),
				'desc_tip' => esc_html__( 'Enter percentage of recycle material.', 'martfury' ),
			)
		);

		woocommerce_wp_radio(
			array(
				'id'          => '_recyclable',
				'label'       => esc_html__( 'Recyclable?', 'martfury' ),
				'options' => array(
					'yes' => esc_html__( 'Yes', 'martfury' ),
					'no'  => esc_html__( 'No', 'martfury' ),
					'undefined'  => esc_html__( 'Not Applicable', 'martfury' ),
				)
			)
		);
		
		woocommerce_wp_radio(
			array(
				'id'          => '_disposable',
				'label'       => esc_html__( 'Disposable?', 'martfury' ),
				'options' => array(
					'yes' => esc_html__( 'Yes', 'martfury' ),
					'no'  => esc_html__( 'No', 'martfury' ),
					'undefined'  => esc_html__( 'Not Applicable', 'martfury' ),
				)
			)
		);

		woocommerce_wp_radio(
			array(
				'id'          => '_biodegradable',
				'label'       => esc_html__( 'Biodegradable?', 'martfury' ),
				'options' => array(
					'yes' => esc_html__( 'Yes', 'martfury' ),
					'no'  => esc_html__( 'No', 'martfury' ),
					'undefined'  => esc_html__( 'Not Applicable', 'martfury' ),
				)
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'          => '_content_formula',
				'label'       => esc_html__( 'Content formula?', 'martfury' ),
				'desc_tip' => esc_html__( 'Enter content formula', 'martfury' ),
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'          => '_raw_material',
				'label'       => esc_html__( 'Raw material origin?', 'martfury' ),
				'desc_tip' => esc_html__( 'Enter percentage of recycle material.', 'martfury' ),
			)
		);
		
		echo '</div>';

		echo '<style>
		#product_attributes_extra fieldset ul.wc-radios {
			display: flex;
		}
		#product_attributes_extra fieldset ul li label {
			display: inline;
   		 	margin-left: 0px;
		}
		</style>';
	}
	
	/**
	 * add text field for product knowledge input in Dokan dashboard
	 * 
	 * customized by Haziq (01/02/2022)
	 */
	public function dokan_extra_product_knowledge_fields( $post_id ) {
		global $post;
		?>
		<div class="content dokan-form-group" style="padding: 5px;">
            <label for="_plastic_number" class="form-label"><?php esc_html_e( 'Plastic number', 'dokan-lite' ); ?> <span><?php esc_html_e( '(1-7)', 'dokan-lite' ); ?></span></label>
            <?php dokan_post_input_box( $post_id, '_plastic_number' ); ?>
        </div>

		<div class="content-half-part dokan-form-group" style="padding: 5px;">
            <label for="_lifespan_duration" class="form-label"><?php esc_html_e( 'Lifespan', 'dokan-lite' ); ?> <span><?php esc_html_e( '(duration)', 'dokan-lite' ); ?></span></label>
            <?php dokan_post_input_box( $post_id, '_lifespan_duration', 'number' ); ?>
        </div>
		
		<div class="content-half-part dokan-form-group" style="padding: 5px;">
			<label for="_lifespan_time" class="form-label"><?php esc_html_e( 'Timeframe', 'dokan-lite' ); ?> <span><?php //esc_html_e( '', 'dokan-lite' ); ?></span></label>
			<?php
				dokan_post_input_box( $post_id, '_lifespan_time', array( 'options' => array(
					''     => __( 'Select timeframe', 'dokan-lite' ),
                    'day' => __( 'Days', 'dokan-lite' ),
                    'month'    => __( 'Months', 'dokan-lite' ),
                    'year'    => __( 'Years', 'dokan-lite' )
				) ), 'select' ); ?>
        </div>

		<div class="content-half-part dokan-form-group" style="padding: 5px;">
			<label for="_bpa_free" class="form-label"><?php esc_html_e( 'BPA Free', 'dokan-lite' ); ?> <span><?php esc_html_e( '', 'dokan-lite' ); ?></span></label>
			<?php
				dokan_post_input_box( $post_id, '_bpa_free', array( 'options' => array(
					''  => __( 'Select', 'dokan-lite' ),
					'yes'     => __( 'Yes', 'dokan-lite' ),
                    'no' => __( 'No', 'dokan-lite' ),
                    'undefined'    => __( 'Not Applicable', 'dokan-lite' )
				) ), 'select' ); ?>
        </div>
		
		<div class="content-half-part dokan-form-group" style="padding: 5px;">
			<label for="_reusable" class="form-label"><?php esc_html_e( 'Safe to reuse', 'dokan-lite' ); ?> <span><?php esc_html_e( '', 'dokan-lite' ); ?></span></label>
			<?php
				dokan_post_input_box( $post_id, '_reusable', array( 'options' => array(
					''  => __( 'Select', 'dokan-lite' ),
					'yes'     => __( 'Yes', 'dokan-lite' ),
                    'no' => __( 'No', 'dokan-lite' ),
                    'undefined'    => __( 'Not Applicable', 'dokan-lite' )
				) ), 'select' ); ?>
        </div>
		
		<div class="content-half-part dokan-form-group" style="padding: 5px;">
			<label for="_temperature" class="form-label"><?php esc_html_e( 'Temperature resistance', 'dokan-lite' ); ?> <span><?php esc_html_e( ' (°C)', 'dokan-lite' ); ?></span></label>
			<?php dokan_post_input_box( $post_id, '_temperature', 'number' ); ?>
		</div>
		
		<div class="content-half-part dokan-form-group" style="padding: 5px;">
			<label for="_microwave_safe" class="form-label"><?php esc_html_e( 'Microwave safe', 'dokan-lite' ); ?> <span><?php esc_html_e( '', 'dokan-lite' ); ?></span></label>
			<?php
				dokan_post_input_box( $post_id, '_microwave_safe', array( 'options' => array(
					''  => __( 'Select', 'dokan-lite' ),
					'yes'     => __( 'Yes', 'dokan-lite' ),
					'no' => __( 'No', 'dokan-lite' ),
					'undefined'    => __( 'Not Applicable', 'dokan-lite' )
				) ), 'select' ); ?>
		</div>
		
		<div class="content-half-part dokan-form-group" style="padding: 5px;">
			<label for="_recycle_material" class="form-label"><?php esc_html_e( 'Recycle material', 'dokan-lite' ); ?> <span><?php esc_html_e( '', 'dokan-lite' ); ?></span></label>
			<?php
				dokan_post_input_box( $post_id, '_recycle_material', array( 'options' => array(
					''  => __( 'Select', 'dokan-lite' ),
					'yes'     => __( 'Yes', 'dokan-lite' ),
					'no' => __( 'No', 'dokan-lite' ),
					'undefined'    => __( 'Not Applicable', 'dokan-lite' )
				) ), 'select' ); ?>
		</div>
		
		<div class="content-half-part dokan-form-group" style="padding: 5px;">
			<label for="_percentage_recyclable" class="form-label"><?php esc_html_e( 'Percentage of recycle material', 'dokan-lite' ); ?> <span><?php esc_html_e( ' (%)', 'dokan-lite' ); ?></span></label>
			<?php dokan_post_input_box( $post_id, '_percentage_recyclable', 'number' ); ?>
		</div>
		
		<div class="content-half-part dokan-form-group" style="padding: 5px;">
			<label for="_recyclable" class="form-label"><?php esc_html_e( 'Recyclable', 'dokan-lite' ); ?> <span><?php esc_html_e( '', 'dokan-lite' ); ?></span></label>
			<?php
				dokan_post_input_box( $post_id, '_recyclable', array( 'options' => array(
					''  => __( 'Select', 'dokan-lite' ),
					'yes'     => __( 'Yes', 'dokan-lite' ),
					'no' => __( 'No', 'dokan-lite' ),
					'undefined'    => __( 'Not Applicable', 'dokan-lite' )
				) ), 'select' ); ?>
		</div>
		
		<div class="content-half-part dokan-form-group" style="padding: 5px;">
			<label for="_disposable" class="form-label"><?php esc_html_e( 'Disposable', 'dokan-lite' ); ?> <span><?php esc_html_e( '', 'dokan-lite' ); ?></span></label>
			<?php
				dokan_post_input_box( $post_id, '_disposable', array( 'options' => array(
					''  => __( 'Select', 'dokan-lite' ),
					'yes'     => __( 'Yes', 'dokan-lite' ),
					'no' => __( 'No', 'dokan-lite' ),
					'undefined'    => __( 'Not Applicable', 'dokan-lite' )
				) ), 'select' ); ?>
		</div>
		
		<div class="content-half-part dokan-form-group" style="padding: 5px;">
			<label for="_biodegradable" class="form-label"><?php esc_html_e( 'Biodegradable', 'dokan-lite' ); ?> <span><?php esc_html_e( '', 'dokan-lite' ); ?></span></label>
			<?php
				dokan_post_input_box( $post_id, '_biodegradable', array( 'options' => array(
					''  => __( 'Select', 'dokan-lite' ),
					'yes'     => __( 'Yes', 'dokan-lite' ),
					'no' => __( 'No', 'dokan-lite' ),
					'undefined'    => __( 'Not Applicable', 'dokan-lite' )
				) ), 'select' ); ?>
		</div>
		
		<div class="content-half-part dokan-form-group" style="padding: 5px;">
			<label for="_content_formula" class="form-label"><?php esc_html_e( 'Content formula', 'dokan-lite' ); ?> <span><?php esc_html_e( '', 'dokan-lite' ); ?></span></label>
			<?php dokan_post_input_box( $post_id, '_content_formula', 'text' ); ?>
		</div>

		<div class="content dokan-form-group last-child" style="padding: 0 5px 10px 5px;">
			<label for="_raw_material" class="form-label"><?php esc_html_e( 'Raw material origin', 'dokan-lite' ); ?> <span><?php esc_html_e( '', 'dokan-lite' ); ?></span></label>
			<?php dokan_post_input_box( $post_id, '_raw_material', 'text' ); ?>
		</div>

		<?php
	}
	
	public function show_uom_product_page() {
		global $post;
		$woo_uom_output = get_post_meta( $post->ID, '_woo_uom_input', true );
		echo '<h2 style="font-size: 18px; margin: 12px; padding-top: 24px; display: block;">' . esc_attr( $woo_uom_output, 'woocommerce-uom' ) . '</h2>';
	}
	 
}

/**
 * Instantiate the class
 *
 * @since 3.0.0
 */
$wc_uom_admin = new WC_UOM_Admin();
