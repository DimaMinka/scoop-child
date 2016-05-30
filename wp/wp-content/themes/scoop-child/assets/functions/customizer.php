<?php

/**
 * Contains methods for customizing the theme customization screen.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since Scoop 1.0
 */
class Scoop_Customize
{

    public static function scoop_sanitize_integer($input)
    {
        if (is_numeric($input)) {
            return intval($input);
        }
    }

    /**
     * This hooks into 'customize_register' (available as of WP 3.4) and allows
     * you to add new sections and controls to the Theme Customize screen.
     *
     * Note: To enable instant preview, we have to actually write a bit of custom
     * javascript. See live_preview() for more.
     *
     * @see add_action('customize_register',$func)
     * @param \WP_Customize_Manager $wp_customize
     * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
     * @since Scoop 1.0
     */
    public static function register($wp_customize)
    {
        $scoop_choises = '';

        $wp_customize->get_setting('blogname')->transport = 'postMessage';
        $wp_customize->get_setting('blogdescription')->transport = 'postMessage';

        $scoop_panel = array('global_settings' => __('Advanced Options'));
        $scoop_panel_section['global_settings'] = array('global');
        $scoop_section = array(
            'global'  => __('Global')
        );

        $scoop_fields['global']  = array('' => __('', 'scoop'));


        $scoop_fields_type = array();

        $i = 0;
        foreach ($scoop_panel as $p => $p_label) {
            $p_num = 30 + $i;
            $wp_customize->add_panel(
                $p,
                array(
                    'priority' => $p_num,
                    'theme_supports' => '',
                    'title' => __($p_label, 'scoop'),
                )
            );
            $i++;
        }
        $i = 0;
        foreach ($scoop_section as $section => $s_label) {
            $p_num = 30 + $i;
            $wp_customize->add_section($section, array(
                'title' => __($s_label, 'scoop'),
                'priority' => $p_num,
            ));
            $i++;
        }
        foreach ($scoop_section as $section => $s_label) {
            foreach ($scoop_fields[$section] as $fields => $f_label) {
                if (array_key_exists($fields, $scoop_fields_type)) {
                    $scoop_type = $scoop_fields_type[$fields];
                } else {
                    $scoop_type = 'text';
                }
                if ($scoop_type == 'img') {
                    $wp_customize->add_setting(
                        $fields,
                        array(
                            'default' => '',
                            'capability' => 'edit_theme_options',
                            'sanitize_callback' => 'esc_url_raw'
                        )
                    );
                } elseif ($scoop_type == 'dropdown') {
                    $wp_customize->add_setting(
                        $fields,
                        array(
                            'sanitize_callback' => 'scoop_sanitize_integer'
                        )
                    );
                } else {
                    $wp_customize->add_setting(
                        $fields,
                        array(
                            'default' => '',
                            'capability' => 'edit_theme_options',
                            'sanitize_callback' => ''
                        )
                    );
                }
            }
        }

        foreach ($scoop_panel as $panel => $p_label) {
            foreach ($scoop_panel_section[$panel] as $section) {
                $wp_customize->get_section($section)->panel = $panel;
            }
        }
        foreach ($scoop_section as $section => $s_label) {
            foreach ($scoop_fields[$section] as $fields => $f_label) {
                if (array_key_exists($fields, $scoop_fields_type)) {
                    $scoop_type = $scoop_fields_type[$fields];
                } else {
                    $scoop_type = 'text';
                }
                if ($scoop_type == 'img') {
                    $wp_customize->add_control(
                        new WP_Customize_Image_Control(
                            $wp_customize,
                            $fields,
                            array(
                                'label' => __($f_label, 'scoop'),
                                'section' => $section,
                                'settings' => $fields,
                            )
                        )
                    );
                } elseif ($scoop_type == 'dropdown') {
                    $wp_customize->add_control(
                        $fields,
                        array(
                            'type' => 'dropdown-pages',
                            'label' => __($f_label, 'scoop'),
                            'section' => $section,
                        )
                    );
                } elseif ($scoop_type == 'select') {
                    $wp_customize->add_control(
                        new WP_Customize_Control(
                            $wp_customize,
                            $fields,
                            array(
                                'label' => __($f_label, 'scoop'),
                                'section' => $section,
                                'settings' => $fields,
                                'type' => $scoop_type,
                                'choices' => $scoop_choises[$fields]
                            )
                        )
                    );
                } elseif ($scoop_type == 'textarea') {
                    $wp_customize->add_control(
                        new WP_Customize_Control(
                            $wp_customize,
                            $fields,
                            array(
                                'label' => __($f_label, 'scoop'),
                                'section' => $section,
                                'settings' => $fields,
                                'type' => $scoop_type,
                            )
                        )
                    );
                } else {
                    $wp_customize->add_control(
                        new WP_Customize_Control(
                            $wp_customize,
                            $fields,
                            array(
                                'label' => __($f_label, 'scoop'),
                                'section' => $section,
                                'settings' => $fields,
                                'type' => $scoop_type,
                            )
                        )
                    );
                }
            }
        }
    }

    /**
     * This will output the custom WordPress settings to the live theme's WP head.
     *
     * Used by hook: 'wp_head'
     *
     * @see add_action('wp_head',$func)
     * @since Scoop 1.0
     */
    public static function header_output()
    {

    }

    /**
     * This outputs the javascript needed to automate the live settings preview.
     * Also keep in mind that this function isn't necessary unless your settings
     * are using 'transport'=>'postMessage' instead of the default 'transport'
     * => 'refresh'
     *
     * Used by hook: 'customize_preview_init'
     *
     * @see add_action('customize_preview_init',$func)
     * @since Scoop 1.0
     */
    public static function live_preview()
    {
        wp_enqueue_script(
            'scoop-themecustomizer',
            get_stylesheet_directory_uri() . '/assets/scripts/customizer.js',
            array('jquery', 'customize-preview'),
            '',
            true
        );
    }

    /**
     * This will generate a line of CSS for use in header output. If the setting
     * ($mod_name) has no defined value, the CSS will not be output.
     *
     * @uses get_theme_mod()
     * @param string $selector CSS selector
     * @param string $style The name of the CSS *property* to modify
     * @param string $mod_name The name of the 'theme_mod' option to fetch
     * @param string $prefix Optional. Anything that needs to be output before the CSS property
     * @param string $postfix Optional. Anything that needs to be output after the CSS property
     * @param bool $echo Optional. Whether to print directly to the page (default: true).
     * @return string Returns a single line of CSS with selectors and a property.
     * @since Scoop 1.0
     */
    public static function generate_css($selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = true)
    {
        $return = '';
        $mod = get_theme_mod($mod_name);
        if (!empty($mod)) {
            $return = sprintf('%s { %s:%s; }',
                $selector,
                $style,
                $prefix . $mod . $postfix
            );
            if ($echo) {
                echo $return;
            }
        }
        return $return;
    }
}

add_action('customize_register', array('Scoop_Customize', 'register'));

add_action('wp_head', array('Scoop_Customize', 'header_output'));

add_action('customize_preview_init', array('Scoop_Customize', 'live_preview'));