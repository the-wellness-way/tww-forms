<?php
namespace TWWForms\Integrations;
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}



class TWW_ElementorIntegration extends \Elementor\Core\DynamicTags\Tag {
    public function get_name() {
        return 'custom-image-url-jetengine'; // Unique identifier for your tag
    }

    public function get_title() {
        return __( 'Custom Image URL by JetEngine Query', 'tww-forms' ); // Name of the tag
    }

    public function get_group() {
        return 'image'; // Group where it will appear, in this case, under image tags
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ]; // Register under image category
    }

    protected function register_controls() {
        $this->add_control(
            'jet_engine_query',
            [
                'label' => __( 'JetEngine Query', 'tww-forms' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_jet_engine_queries(), // Populate with JetEngine queries
                'default' => '',
                'label_block' => true,
            ]
        );
    }

    private function get_jet_engine_queries() {
        $queries = [];
        if ( function_exists( 'jet_engine' ) ) {
            $query_manager = \Jet_Engine\Query_Builder\Manager::instance();
            foreach ( $query_manager->queries as $query ) {
                $queries[ $query->get_slug() ] = $query->get_title();
            }
        }
        return $queries;
    }

    public function render() {
        $query_name = $this->get_settings( 'jet_engine_query' );

        // Execute the JetEngine query to get the user ID
        $user_id = null;
        if ( function_exists( 'jet_engine' ) && ! empty( $query_name ) ) {
            $query = \Jet_Engine\Query_Builder\Manager::instance()->get_query_by_slug( $query_name );
            if ( $query && ! empty( $query->get_items() ) ) {
                $user_id = $query->get_items()[0]['user_id']; // Adjust this based on the structure of your query result
            }
        }

        if ( $user_id ) {
            // Query your custom object here using the user ID
            // Assuming you store the image URL as user meta
            $custom_image_url = get_user_meta( $user_id, 'profile_picture', true ); // Modify this to match your structure

            if ( ! empty( $custom_image_url ) ) {
                echo esc_url( $custom_image_url ); // Output the image URL for Elementor to use
            } else {
                echo esc_url( get_avatar_url( $user_id ) ); // Fallback to Gravatar if no custom image
            }
        } else {
            echo __( 'No user found', 'tww-forms' );
        }
    }
}

