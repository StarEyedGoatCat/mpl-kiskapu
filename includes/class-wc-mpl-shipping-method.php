<?php

if (!class_exists('WC_MPL_Shipping_Method')) {
    class WC_MPL_Shipping_Method extends WC_Shipping_Method
    {

        public function __construct($instance_id = 0)
        {
            parent::__construct($instance_id);

            $this->id = 'mpl_shipping';
            $this->instance_id = absint($instance_id);
            $this->method_title = __('MPL');
            $this->method_description = __('MPL method for demonstration purposes.');
            $this->supports = array(
                'shipping-zones',
                'instance-settings',
            );
            $this->instance_form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable'),
                    'type' => 'checkbox',
                    'label' => __('Enable this shipping method'),
                    'default' => 'yes',
                ),
                'title' => array(
                    'title' => __('Method Title'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.'),
                    'default' => __('Tyche Shipping Method'),
                    'desc_tip' => true,
                ),
            );
            $this->enabled = $this->get_option('enabled');
            $this->title = $this->get_option('title');
            add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));

        }

        public function init()
        {
            $this->init_form_fields();
            $this->init_settings();
            add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
        }

        // public function calculate_shipping($package = array())
        // {
        //     $rate = array(
        //         'id' => $this->id,
        //         'label' => $this->title,
        //         'cost' => '990',
        //     );
        //     $this->add_rate($rate);
        // }

    }
}
