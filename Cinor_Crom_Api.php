<?php 

use Model\StreetManager;

class Cinor_Crom_Api 
{
    public function __construct() {
        register_rest_route("cinor_crom/v1", "street/(?P<keypress>[%\w]*+)", [
            'methods' => 'GET',
            'callback' => array($this, 'get_street')
        ]);
    }

    public function get_street($slug) {
        global $wpdb;
        
        $streetManager = new StreetManager($wpdb);
        
        $keypres = urldecode($slug['keypress']);
        return $streetManager->search($keypres);
    }
}