<?php

class MPL_Shipping_Method
{

    public function __construct()
    {
        add_action('woocommerce_shipping_init', array($this, 'mpl_shipping_method_init'));
        add_filter('woocommerce_shipping_methods', array($this, 'add_mpl_shipping_method'));
        add_filter('woocommerce_package_rates', array($this, 'add_mpl_to_packages'), 10, 2);
        // if mpl_shipping is sleceted add extra charge
        add_action('woocommerce_cart_calculate_fees', array($this, 'add_extra_charge'));
    }

    public function mpl_shipping_method_init()
    {
        if (!class_exists('WC_MPL_Shipping_Method')) {
            require_once MPL_KK_PLUGIN_PATH . 'includes/class-wc-mpl-shipping-method.php';
        }
    }

    public function add_mpl_shipping_method($methods)
    {
        $methods['mpl_shipping'] = 'WC_MPL_Shipping_Method';
        return $methods;
    }

    public function add_mpl_to_packages($rates, $package)
    {

        if (!class_exists('WC_MPL_Shipping_Method')) {
            require_once MPL_KK_PLUGIN_PATH . 'includes/class-wc-mpl-shipping-method.php';
        }

        $options = get_option('mpl_sd_settings');
        $delivery_time = isset($options['delivery_time']) ? $options['delivery_time'] : "";
        $extra_charge_name = isset($options['extra_charge_name']) ? $options['extra_charge_name'] : __("Logisztikai költség", "mpl-kiskapu");
        $mpl_shipping_method = new WC_MPL_Shipping_Method();

        // get cart weight
        $weight = WC()->cart->get_cart_contents_weight();

        $options = get_option('mpl_sd_settings');
        $shipping_class = isset($options['shipping_class']) ? $options['shipping_class'] : 1990;

        switch ($weight) {
            case $weight == 0:
                $delivery_price = $shipping_class;
            case $weight < 10:
                $delivery_price = 1990;
                break;
            case $weight < 20:
                $delivery_price = 2990;
                break;
            case $weight < 30:
                $delivery_price = 5990;
                break;
            default:
                $delivery_price = $shipping_class;
                break;
        }

        // Assuming prices entered include tax, back-calculate the base price
        $tax_rate = 0.27; // 27% tax rate
        $base_cost = $delivery_price / (1 + $tax_rate); // Back out the tax from the displayed price

// Calculate tax amount based on the base cost
        $tax_amount = $base_cost * $tax_rate;

// Set up taxes array properly for WooCommerce. This might need to be adjusted depending on how taxes are handled in your setup.
        $taxes = array(
            1 => $tax_amount,
        );

        $rate = array(
            'id' => $mpl_shipping_method->id,
            'label' => __("MPL - Házhozszállítás", "mpl-kiskapu") . " " . __("Kiszállítási idő", "mpl-kiskapu") . ": " . $delivery_time . " + " . $extra_charge_name,
            'description' => __("Házhoszszállítás Magyar Posta álltal", "mpl-kiskapu") . " " . $delivery_time . " " . __("nap alatt.", "mpl-kiskapu"),
            'cost' => $base_cost, // Use the base cost here
            'taxes' => $taxes, // Ensure this is structured correctly for WooCommerce. Might be just an array with one element for the tax amount.
            'package' => $package,
        );

        $rate = apply_filters('mpl_shipping_rate', $rate, $mpl_shipping_method);

// Generate a unique rate ID to avoid conflicts
        $rate_id = $mpl_shipping_method->id . ':' . md5(wp_json_encode($rate));
        $rates[$rate_id] = new WC_Shipping_Rate($rate_id, $rate['label'], $rate['cost'], $taxes, $mpl_shipping_method->id);

        return $rates;

    }

    public function add_extra_charge()
    {
        $options = get_option('mpl_sd_settings');
        $extra_charge = isset($options['extra_charge']) ? $options['extra_charge'] : 0;
        $extra_charge_name = isset($options['extra_charge_name']) ? $options['extra_charge_name'] : __("Logisztikai költség", "mpl-kiskapu");
        $net_extra_charge = $extra_charge / 1.27;
        $chosen_methods = WC()->session->get('chosen_shipping_methods')[0];
        $methode_name = explode(':', $chosen_methods)[0];
        if ($methode_name == 'mpl_shipping') {
            WC()->cart->add_fee($extra_charge_name, $net_extra_charge, true, 'standard');
        }
    }

}
