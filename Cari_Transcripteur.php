<?php 

use Entity\Touring;
use Entity\Street;

use Model\StreetManager;
use Model\TouringManager;

use TableReader\ListReader;
use TableReader\GridReader;

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
        $this->_touringManager->cleanWholeTable();
        
        include_once(plugin_dir_path(__FILE__).'config.php');
        $config = $config['touring']();
        
        $reader = new GridReader($config);
        $data = $reader->read();

        $row = 0;
        
        foreach ($data as $item) {
            $touring = new Touring([
                'type_dechet' => $item['type_dechet'],
                'ref_calendrier' => $item['ref_calendrier'],
                'date_passage' => new DateTime($item['date_passage'])
            ]);

            $this->_touringManager->add($touring);
            $row++;
        }

        echo $row . ' lignes insérés';
    }

    public function send_street_listing() 
    {
        $this->_streetManager->cleanWholeTable();
        
        include_once(plugin_dir_path(__FILE__).'config.php');
        $config = $config['street-listing']();
        
        $reader = new ListReader($config);
        $data = $reader->read();

        $double = 0;
        $row = 0;
        
        foreach ($data as $item) {
            $street = new Street([
                'ref_calendrier' => $item['ref_calendrier'],
                'type_voie' => $item['type_voie'],
                'intitule_voie' => $item['intitule_voie'],
                'quartier' => $item['quartier']
            ]);

            $notExistItem = $this->_streetManager->add($street);
            ($notExistItem) ? $row++ : $double++ ;
        }

        $response = $row . ' lignes insérés';
    
        if($double > 0) {
            $response .= '<br /> Attention ! Le tableau comporte ' . $double . ' doublons';
        }
        
        echo $response;
    }

    public function install()
    {
        $this->_streetManager->create();
        $this->_touringManager->create();

        mkdir(__DIR__ . '/ressource/');
    }

    public function uninstall()
    {
        $this->_streetManager->delete();
        $this->_touringManager->delete();
    }
}