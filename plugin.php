<?php

/*
Plugin Name: BranchLabs ORM
Description: Simple ORM built to assess that I am, in fact, a WP/PHP programmer.
Version: 1.0
Author: Carlos Sariñana
*/


class BranchLabsORM_Plugin
{
    
    private $blo_options = [];
    
    public function __construct()
    {
        add_action('admin_menu', array($this, 'blo_add_plugin_page'));
        add_action('admin_init', array($this, 'blo_page_init'));
        
        add_action( 'init', array( $this, 'add_rewrite_rules' ) );
        add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
        add_filter( 'template_include', array( $this, 'add_template' ) );
    }
    
    public function add_template( $template ) {
        $contact_page = get_query_var( 'contact_page' );
        if ( $contact_page ) {
            return WP_PLUGIN_DIR . '/bl_orm/' . 'ContactController.php';
        }
        return $template;
    }
    
    public function flush_rules() {
        $this->add_rewrite_rules();
        flush_rewrite_rules();
    }
    
    public function add_rewrite_rules() {
        add_rewrite_rule( 'contact/(.+?)/?$', 'index.php?contact_page=$matches[1]&action=$matches[2]', 'top' );
    }
    
    public function add_query_vars( $vars ) {
        $vars[] = 'contact_page';
        $vars[] = 'action';
        return $vars;
    }
    
    public function blo_add_plugin_page()
    {
        add_menu_page(
            'BranchLabs ORM Settings', 
            'BLO Settings', 
            'manage_options', 
            'blo-plugin',
            array($this, 'blo_create_admin_page'),
            'dashicons-admin-users',
            2
        );
    }
    
    public function blo_create_admin_page()
    {
        $this->blo_options = get_option('blo_options');
        ?>

<div class="wrap">
    <?php settings_errors(); ?>

    <form method="post" action="options.php">
        <?php
        settings_fields('blo_option_group');
        do_settings_sections('blo-admin');
        submit_button();
        ?>
    </form>
    <br>
    <br>
    <p><small>contact_tests.php output:</small></p>
    <p><small>
            <?php
        
        require_once('contact_tests.php');
        
        ?>
        </small>
    </p>
</div>
<?php 
    }
    
    public function blo_page_init()
    {
        register_setting(
            'blo_option_group', // option_group
            'blo_options', // option_name
            array($this, 'blo_sanitize') // sanitize_callback
        );
        
        add_settings_section(
            'blo_setting_section', // id
            'Database Settings', // title
            array($this, 'blo_section_info'), // callback
            'blo-admin' // page
        );
        
        //DB Host
        add_settings_field(
            'db_host', // id
            'DB Host', // title
            array($this, 'db_host_callback'), // callback
            'blo-admin', // page
            'blo_setting_section' // section
        );
        
        //DB Name
        add_settings_field(
            'db_name', // id
            'DB Name', // title
            array($this, 'db_name_callback'), // callback
            'blo-admin', // page
            'blo_setting_section' // section
        );
        
        //DB Username
        add_settings_field(
            'db_user', // id
            'DB Username', // title
            array($this, 'db_user_callback'), // callback
            'blo-admin', // page
            'blo_setting_section' // section
        );
        
        //DB Password
        add_settings_field(
            'db_password', // id
            'DB Password', // title
            array($this, 'db_password_callback'), // callback
            'blo-admin', // page
            'blo_setting_section' // section
        );
        
    }
    
    public function blo_sanitize($input)
    {
        $sanitized_values = array();
        
        if (isset($input['db_host'])) {
            $sanitized_values['db_host'] = sanitize_text_field($input['db_host']);
        }
        
        if (isset($input['db_name'])) {
            $sanitized_values['db_name'] = sanitize_text_field($input['db_name']);
        }
        
        if (isset($input['db_user'])) {
            $sanitized_values['db_user'] = sanitize_text_field($input['db_user']);
        }
        
        if (isset($input['db_password'])) {
            $sanitized_values['db_password'] = sanitize_text_field($input['db_password']);
        }
        
        return $sanitized_values;
    }
    
    public function blo_section_info() {
        printf(
            '<p>Not secure at all, but easier to test.</p><br><p>Get contact data on ' . site_url() . '/contact/{id}/view</p>'
        );
    }
    
    public function db_host_callback()
    {
        printf(
            '<input placeholder="127.0.0.1:3306" class="regular-text" type="text" name="blo_options[db_host]" id="db_host" value="%s">',
            isset($this->blo_options['db_host']) ? esc_attr($this->blo_options['db_host']) : ''
        );
    }
    
    public function db_name_callback()
    {
        printf(
            '<input placeholder="branchlabstest" class="regular-text" type="text" name="blo_options[db_name]" id="db_name" value="%s">',
            isset($this->blo_options['db_name']) ? esc_attr($this->blo_options['db_name']) : ''
        );
    }
    
    public function db_user_callback()
    {
        printf(
            '<input placeholder="taylorswift" class="regular-text" type="text" name="blo_options[db_user]" id="db_user" value="%s">',
            isset($this->blo_options['db_user']) ? esc_attr($this->blo_options['db_user']) : ''
        );
    }
    
    public function db_password_callback()
    {
        printf(
            '<input placeholder="thepassword" class="regular-text" type="password" name="blo_options[db_password]" id="db_password" value="%s">',
            isset($this->blo_options['db_password']) ? esc_attr($this->blo_options['db_password']) : ''
        );
    }
    
}

$blo = new BranchLabsORM_Plugin();