<?php 

use Model\StreetManager;
use Model\TouringManager;

class Cari_Transcripteur
{
    private $_streetManager;
    private $_touringManager;

    public function __construct() {
        global $wpdb; 
        
        $this->_streetManager = new StreetManager($wpdb);
        $this->_touringManager = new TouringManager($wpdb);
    }

    public function send_touring() 
    {

    }

    public function send_street_listing() 
    {

    }

    public function install()
    {
        $this->_streetManager->create();
        $this->_touringManager->create();
    }

    public function uninstall()
    {
        $this->_streetManager->delete();
        $this->_touringManager->delete();
    }
}