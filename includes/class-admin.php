<?php

class MPL_SD_Admin
{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_admin_menu()
    {
        add_menu_page('MPL Kiskapu', 'Kiskapu beállítások', 'manage_options', 'mpl_sd_settings', array($this, 'mpl_sd_settings_page'), 'dashicons-admin-generic', 110);
    }

    public function register_settings()
    {
        register_setting('mpl_sd_settings_group', 'mpl_sd_settings');
        add_settings_section('mpl_sd_settings_section', 'Kiskapu beállítások', array($this, 'mpl_sd_settings_section'), 'mpl_sd_settings');
        // felár
        add_settings_field('mpl_sd_settings_field_extra_charge', 'Felár', array($this, 'mpl_sd_settings_field_extra_charge'), 'mpl_sd_settings', 'mpl_sd_settings_section');
        // felár megnevezése
        add_settings_field('mpl_sd_settings_field_extra_charge_name', 'Felár megnevezése', array($this, 'mpl_sd_settings_field_extra_charge_name'), 'mpl_sd_settings', 'mpl_sd_settings_section');
        // szállítási idő (nap)
        add_settings_field('mpl_sd_settings_field_delivery_time', 'Szállítási idő (nap)', array($this, 'mpl_sd_settings_field_delivery_time'), 'mpl_sd_settings', 'mpl_sd_settings_section');
        // select default szállítási osztály
        add_settings_field('mpl_sd_settings_field_shipping_class', 'Álltalános szállítási osztály', array($this, 'mpl_sd_settings_field_shipping_class'), 'mpl_sd_settings', 'mpl_sd_settings_section');
    }

    public function mpl_sd_settings_page()
    {
        ?>
        <div class="wrap">
            <h2>SmallDoor Settings</h2>
            <form method="post" action="options.php">
                <?php
settings_fields('mpl_sd_settings_group');
        do_settings_sections('mpl_sd_settings');
        submit_button();
        ?>
            </form>
        </div>
        <?php
}

    public function mpl_sd_settings_section()
    {
        echo 'Kiskapu beállítások';
    }

    public function mpl_sd_settings_field_extra_charge()
    {
        $options = get_option('mpl_sd_settings');
        $extra_charge = isset($options['extra_charge']) ? $options['extra_charge'] : '';
        echo '<input type="text" name="mpl_sd_settings[extra_charge]" value="' . $extra_charge . '">';
    }

    public function mpl_sd_settings_field_extra_charge_name()
    {
        $options = get_option('mpl_sd_settings');
        $extra_charge_name = isset($options['extra_charge_name']) ? $options['extra_charge_name'] : '';
        echo '<input type="text" name="mpl_sd_settings[extra_charge_name]" value="' . $extra_charge_name . '">';
    }

    public function mpl_sd_settings_field_delivery_time()
    {
        $options = get_option('mpl_sd_settings');
        $delivery_time = isset($options['delivery_time']) ? $options['delivery_time'] : '';
        echo '<input type="text" name="mpl_sd_settings[delivery_time]" value="' . $delivery_time . '">';
    }

    public function mpl_sd_settings_field_shipping_class()
    {
        $options = get_option('mpl_sd_settings');
        $shipping_class = isset($options['shipping_class']) ? $options['shipping_class'] : '';
        // options 1990, 2990, 5990
        echo '<select name="mpl_sd_settings[shipping_class]">';
        echo '<option value="0">Válassz szállítási osztályt</option>';
        echo '<option value="1990" ' . selected($shipping_class, '1990', false) . '>1990</option>';
        echo '<option value="2990" ' . selected($shipping_class, '2990', false) . '>2990</option>';
        echo '<option value="5990" ' . selected($shipping_class, '5990', false) . '>5990</option>';
        echo '</select>';
    }
}