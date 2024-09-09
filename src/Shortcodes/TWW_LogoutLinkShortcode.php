<?php
namespace TWWForms\Shortcodes;

class TWW_LogoutLinkShortcode extends TWW_Shortcodes {
    public function set_sc_settings() {
        $this->sc_settings = [
            'name' => 'tww_logout_link',
        ];
    }

    public function render_shortcode($atts, $content = null) {
        $atts = shortcode_atts([
            'class' => 'brand-green',
            'anchor_text' => 'Logout'
        ], $atts);


        $logout_link = trailingslashit(site_url()) . 'wp-login.php?action=logout';
            
        return '<a class='.$atts['class'].'" href='.$logout_link.'>'.$atts['anchor_text'].'</a>';
    }
}