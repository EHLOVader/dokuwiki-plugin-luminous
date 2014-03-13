<?php
/**
 * Luminous action plugin
 *
 * @author Joseph Richardson <j4h.r8n@gmail.com
 * >
 */

if(!defined('DOKU_INC')) die();


class action_plugin_luminous extends DokuWiki_Action_Plugin {
   
    /**
     * Register its handlers with the DokuWiki's event controller
     */
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this,
                                   '_hookjs');

        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this,
                                   '_hookcss');
    }

    /**
     * Hook js script into page headers.
     *
     * @author Samuele Tognini <samuele@cli.di.unipi.it>
     */
    /**
     * Hook js Script into page headers
     * 
     * @param  [type] $event [description]
     * @param  [type] $param [description]
     * @return [type]        [description]
     */
    public function _hookjs(&$event, $param) {

        $event->data["script"][] = array ("type" => "text/javascript",
            "charset" => "utf-8",
            "_data" => "",
            "src" => DOKU_PLUGIN. "/vendor/luminous-v0.7.0/client/luminous.js"
            );

    }

    /**
     * Hook CSS styles into page headers
     * 
     * @param  [type] $event [description]
     * @param  [type] $param [description]
     * @return [type]        [description]
     */
    public function _hookcss(&$event, $param){

      $event->data["link"][] = array (
        "type" => "text/css",
        "rel" => "stylesheet", 
        "href" => DOKU_PLUGIN. "/vendor/luminous-v0.7.0/style/luminous.css",
      );
      $event->data["link"][] = array (
        "type" => "text/css",
        "rel" => "stylesheet", 
        "href" => DOKU_PLUGIN. "/vendor/luminous-v0.7.0/style/luminous_light.css",
      );

    }

}