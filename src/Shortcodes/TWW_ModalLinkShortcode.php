<?php
namespace TWWForms\Shortcodes;

class TWW_ModalLinkShortcode extends TWW_Shortcodes {
    public function set_sc_settings() {
        $this->sc_settings = [
            'name' => 'tww_modal_link',
        ];
    }

    public function render_shortcode($atts, $content = null) {
        $atts = shortcode_atts([
            'class' => 'tww-modal-link',
            'anchor_text' => 'Login',
            'link' => '#'
        ], $atts);
            
        return '<a class='.$atts['class'].' href='.$atts['link'].'>'.$atts['anchor_text'].'</a>';
    }
}