<?php

class Cari_Subscriber {
    public function __construct()
    {                  
        add_shortcode('cari_subscriber_newletters', array($this, 'subscriber_html'));
        add_action('wp_enqueue_scripts', array($this, 'subscriber_scripts'));
    }

    public function subscriber_scripts() {
        wp_register_script('suggestion_engine', '/wp-content/plugins/cari-plugin/js/suggestion_engine.js');
    }

    public function subscriber_html($atts, $content) 
    {
        wp_enqueue_script('suggestion_engine');
        
        $html = array();
        
        $html[] = '<section class="inscription">';
            $html[] = '<h2>Inscription : </h2>';
            
            $html[] = '<form method="post" action="">';
                $html[] = '<p><input class="field" name="mail" type="email" placeholder="Votre courriel" /></p>';
                $html[] = '<input class="field" id="search" type="text" autocomplete="off" placeholder="Votre rue" required />';
                
                $html[] = '<div id="results"></div>';
                $html[] = '<div id="results-id" style="display: none"></div>';
                $html[] = '<div id="results-quartier" style="display: none"></div>';
                $html[] = '<div id="results-street" style="display: none"></div>';

                $html[] = '<input id="id" type="hidden" name="id-lieu" />';
                $html[] = '<input id="quartier" type="hidden" name="quartier" />';
                $html[] = '<input id="street" type="hidden" name="street" /><br />';
            
                $html[] = '<p><input type="submit" value="Envoyer" /></p>';
                $html[] = apply_filters('cari_shortcode_response', $response = '');
            $html[] = '</form>';
        $html[] = '</section>';

        return implode('', $html);
    }
}