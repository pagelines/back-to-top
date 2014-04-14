<?php

/**
  Author: Kyle & Irving
  Author URI: http://kyle-irving.co.uk/
  Plugin Name: Back To Top
  Plugin URI: http://pagelines.kyle-irving.co.uk/back-to-top/
  Version: 1.0.1
  Description: Allows users to get back to the top of your web page effortlessly. Adds a new options panel to DMS Settings for Back to top settings.
  Class Name: Back_To_Top
  PageLines: true
  Section: false
 * 
 */
class Back_To_Top {

    function __construct() {
        add_action('init', array(&$this, 'init'));
        add_filter('pl_settings_array', array(&$this, 'add_settings'));
        add_action('wp_enqueue_scripts', array(&$this, 'add_scripts'));
        add_action('wp_print_styles', array(&$this, 'print_styles'));
    }

    function init() {
        load_plugin_textdomain('back-to-top', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    function print_styles() {
        $options = array(
            'position' => array(
                'bottom' => (int) pl_setting('back-to-top-position-bottom', array('default' => 20)),
                'right' => (int) pl_setting('back-to-top-position-right', array('default' => 20))
            ),
            'padding' => array(
                'vertical' => (int) pl_setting('back-to-top-padding-vertical', array('default' => 10)),
                'horizontal' => (int) pl_setting('back-to-top-padding-horizontal', array('default' => 20))
            ),
            'styling' => array(
                'style' => pl_setting('back-to-top-style', array('default' => 'link')),
                'z-index' => (int) pl_setting('back-to-top-zindex', array('default' => 100)),
                'background-color' => pl_hashify(pl_setting('back-to-top-background-color', array('default' => '#555555'))),
                'background-hover-color' => pl_hashify(pl_setting('back-to-top-background-hover-color', array('default' => '#cccccc'))),
                'text-color' => pl_hashify(pl_setting('back-to-top-text-color', array('default' => '#FFFFFF'))),                
            )
        );
        ?>
        <style type="text/css">
            #button-back-to-top{
                bottom: <?php echo $options['position']['bottom']; ?>px;
                right: <?php echo $options['position']['right']; ?>px;
                padding: <?php echo $options['padding']['vertical']; ?>px <?php echo $options['padding']['horizontal']; ?>px;                
                color: <?php echo $options['styling']['text-color']; ?>;
                z-index: <?php echo $options['styling']['z-index']; ?>;
                font-size: 12px !important;                
            }
            <?php if ('image' != $options['styling']['style']): ?>
                #button-back-to-top{
                    background-color: <?php echo $options['styling']['background-color']; ?>;
                }
                #button-back-to-top:hover{
                    background-color: <?php echo $options['styling']['background-hover-color']; ?>;
                }     
            <?php endif; ?>

        </style>
        <?php
    }

    function add_scripts() {
        $style = pl_setting('back-to-top-style', array('default' => 'link'));
        $zindex = (int) pl_setting('back-to-top-zindex', array('default' => 50));
        wp_enqueue_script('jquery-scrollup', plugin_dir_url(__FILE__) . 'js/jquery.scrollup.js', array('jquery'), NULL, true);
        wp_enqueue_script('back-to-top', plugin_dir_url(__FILE__) . 'js/back-to-top.js', array('jquery', 'jquery-scrollup'), NULL, true);

        wp_localize_script('back-to-top', 'pagelines_scroll_up', array(
            'text' => (pl_setting('back-to-top-text')) ? pl_setting('back-to-top-text') : __('Back to top', 'back-to-top'),
            'style' => $style,
            'zIndex' => $zindex
        ));

        wp_enqueue_style('back-to-top', plugin_dir_url(__FILE__) . "css/{$style}.css", array(), NULL);
    }

    function add_settings($settings) {

        $settings['back-to-top'] = array(
            'name' => 'Back To Top',
            'icon' => 'icon-circle-arrow-up',
            'pos' => 3,
            'opts' => $this->options()
        );

        return $settings;
    }

    function options() {

        $settings = array(
            array(
                'type' => 'multi',
                'col'	=> 1,
                'title' => __('Styling', 'back-to-top'),
                'help' => '',
                'opts' => array(
                    array(
                        'key' => 'back-to-top-style',
                        'type' => 'select_same',
                        'label' => __('Select style of button', 'back-to-top'),
                        'default' => 'link',
                        'opts' => array(
                            'link',
                            'tab',
                            'pill',
                            'image'
                        )
                    ),
                    array(
                        'key' => 'back-to-top-text',
                        'type' => 'text',
                        'default' => __('Back to top', 'back-to-top'),
                        'label' => __('Button Text', 'back-to-top'),
                    ),
                    array(
                        'key' => 'back-to-top-zindex',
                        'help' => 'Only use zindex if required, i.e your Back To Top link is behind another object.',
                        'type' => 'text',
                        'default' => 50,
                        'label' => __('Button z-index', 'back-to-top'),
                    ),
                    array(
                        'key' => 'back-to-top-background-color',
                        'type' => 'color',
                        'label' => __('Background color', 'back-to-top'),
                        'default' => '#555555'
                    ),
                    array(
                        'key' => 'back-to-top-background-hover-color',
                        'type' => 'color',
                        'label' => __('Background hover color', 'back-to-top'),
                        'default' => '#cccccc'
                    ),
                    array(
                        'key' => 'back-to-top-text-color',
                        'type' => 'color',
                        'label' => __('Text color', 'back-to-top'),
                        'default' => '#FFFFFF'
                    )              
                )
            ),
            array(
                'type' => 'multi',
                'col'	=> 2,
                'title' => __('Position', 'back-to-top'),
                'help' => '',
                'opts' => array(
                    array(
                        'key' => 'back-to-top-position-bottom',
                        'type' => 'select_same',
                        'label' => __('Bottom (px)', 'back-to-top'),
                        'default' => 20,
                        'opts' => range(0, 100, 5)
                    ),
                    array(
                        'key' => 'back-to-top-position-right',
                        'type' => 'select_same',
                        'label' => __('Right (px)', 'back-to-top'),
                        'default' => 20,
                        'opts' => range(0, 100, 5)
                    )
                )
            ),
            array(
                'type' => 'multi',
                'col'	=> 2,
                'title' => __('Padding', 'back-to-top'),
                'help' => '',
                'opts' => array(
                    array(
                        'key' => 'back-to-top-padding-vertical',
                        'type' => 'select_same',
                        'label' => __('Top & Bottom (px)', 'back-to-top'),
                        'default' => 10,
                        'opts' => range(0, 100, 5)
                    ),
                    array(
                        'key' => 'back-to-top-padding-horizontal',
                        'type' => 'select_same',
                        'label' => __('Left & Right (px)', 'back-to-top'),
                        'default' => 20,
                        'opts' => range(0, 100, 5)
                    )
                )
            )
        );


        return $settings;
    }

}

new Back_To_Top();
