<?php
namespace TWWForms\Shortcodes;

class TWW_Grams2OuncesShortcode extends TWW_Shortcodes {
    public function set_sc_settings() {
        $this->sc_settings = [
            'name' => 'tww_grams2ounces',
            'handle' => 'tww-grams2ounces-shortcode',
            'css_handle' => 'tww-grams2ounces-shortcode',
        ];
    }

    public function render_shortcode($atts, $content = null) {
        wp_enqueue_style('tww-grams2ounces-shortcode');

        $atts = shortcode_atts([
            'justify' => 'flex-start',
        ], $atts);
        
        ob_start();
        include TWW_FORMS_PLUGIN . 'templates/grams2ounces-shortcode.php';
        return ob_get_clean();
    }
}