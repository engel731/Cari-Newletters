<?php

class Cari_Subscriber {
    public function __construct()
    {                  
        add_shortcode('cari_subscriber_newletters', array($this, 'subscriber_html'));
        add_action('wp_enqueue_scripts', array($this, 'subscriber_scripts'));
    }

    public function subscriber_scripts() {
        wp_register_script('suggestion_engine', plugins_url('js/suggestion_engine.js', __FILE__));
    }

    public function subscriber_html($atts, $content) 
    {
        wp_enqueue_style('bootstrap');
        wp_enqueue_style('template');
        
        wp_enqueue_script('suggestion_engine');
        
        $html = array();
        
        $html[] = '<div class="container">';
            $html[] = '<div class="row justify-content-center">';
                $html[] = '<form method="post" action="" class="col-sm-12">';
                    $html[] = '<div class="form-group">';
                        $html[] = '<input name="mail" type="email" placeholder="Votre courriel" class="form-control" />';
                    $html[] = '</div>';
                
                    $html[] = '<div class="form-group">';
                        $html[] = '<input id="search" type="text" autocomplete="off" placeholder="Nom de votre rue" required class="form-control" />';
                        $html[] = '<div id="results" class="cari-suggestion form-control"></div>';
                    $html[] = '</div>';
                    
                    $html[] = '<div id="results-id" style="display: none"></div>';
                    $html[] = '<div id="results-quartier" style="display: none"></div>';
                    $html[] = '<div id="results-street" style="display: none"></div>';

                    $html[] = '<input id="id" type="hidden" name="id-lieu" />';
                    $html[] = '<input id="quartier" type="hidden" name="quartier" />';
                    $html[] = '<input id="street" type="hidden" name="street" />';
                
                    $html[] = '<input type="submit" value="S\'abonner" class="form-control" />';
                    $html[] = apply_filters('cari_shortcode_response', $response = '');
                $html[] = '</form>';
            $html[] = '</div>';
        $html[] = '</div>';

        return implode('', $html);
    }
}