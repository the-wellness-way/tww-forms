<?php
namespace TwwForms\Includes;

class TWW_Templates {
    const TEMPLATE_PATH = 'templates-wp';

    protected $templates = [
        [
            'file' => 'template-register.php',
            'name' => 'TWW Register',
        ],
    ];

    public function add_template($templates) {
        foreach($this->templates as $template) {
            if(file_exists(TWW_FORMS_PLUGIN . self::TEMPLATE_PATH . '/' . $template . '.php')) {
                $templates[$template['file']] = __($template['name'], 'tww-forms');
            }
        }


        return $templates;
    }

    public function load_template($template) {
        foreach($this->templates as $template) {
            if(file_exists(TWW_FORMS_PLUGIN . self::TEMPLATE_PATH . '/' . $template['file'])) {
                return TWW_FORMS_PLUGIN . self::TEMPLATE_PATH . '/' . $template['file'];
            }
        }

        return $template;
    }
}