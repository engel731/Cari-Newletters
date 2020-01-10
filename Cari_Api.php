<?php 

use Model\StreetManager;

class Cari_Api 
{
    public function __construct() {
        register_rest_route("cari/v1", "street/(?P<keypress>[%\w]*+)", [
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