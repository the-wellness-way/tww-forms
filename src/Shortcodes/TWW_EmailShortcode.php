<?php
namespace TWWForms\Shortcodes;

class TWW_EmailShortcode extends TWW_Shortcodes {
    const EMAIL_CHAR_LIMIT = 28;
    
    public function set_sc_settings() {
        $this->sc_settings = [
            'name' => 'tww_email',
        ];
    }

    public function render_shortcode($atts, $content = null) {
        $user = wp_get_current_user();
        $email = $user->user_email;
        //if the email is longer than 28 characters we want to truncate it to have a ... at the end
        $email = strlen($email) > self::EMAIL_CHAR_LIMIT ? substr($email, 0, self::EMAIL_CHAR_LIMIT) . '...' : $email;

        $atts = shortcode_atts([
            'email' => $email,
            'include_link' => true,
            'class' => 'tww-email-sc'
        ], $atts);

        if($atts['include_link'] !== 'false' && $atts['include_link'] !== false) {
            return sprintf('<a href="mailto:%s"><span class="tww-email-sc">%s</span></a>', $atts['email'], $atts['email']);
        } 
            
        return '<span class='.$atts['class'].'">' . $atts['email'] . '</span>';
    }
}