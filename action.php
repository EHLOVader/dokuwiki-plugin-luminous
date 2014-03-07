<?php
/**
 * Example Action Plugin:   Example Component.
 *
 * @author     Samuele Tognini <samuele@cli.di.unipi.it>
 */

if(!defined('DOKU_INC')) die();


class action_plugin_example extends DokuWiki_Action_Plugin {

    /**
     * Register its handlers with the DokuWiki's event controller
     */
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this,
                                   '_hookjs');
    }

    /**
     * Hook js script into page headers.
     *
     * @author Samuele Tognini <samuele@cli.di.unipi.it>
     */
    public function _hookjs(&$event, $param) {
        $event->data['script'][] = array(
                            'type'    => 'text/javascript',
                            'charset' => 'utf-8',
                            '_data'   => '',
                            'src'     => DOKU_PLUGIN.'example/example.js');
    }
}