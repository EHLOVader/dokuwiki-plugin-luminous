<?php
/**
 * Code Plugin: replaces Dokuwiki's own code syntax
 *
 * Syntax:     <code lang |title>
 *   lang      (optional) programming language name, is passed to geshi for code highlighting
 *             if not provided, the plugin will attempt to derive a value from the file name
 *             (refer $extensions in render() method)
 *   title     (optional) all text after '|' will be rendered above the main code text with a
 *             different style.
 *
 * if no title is provided will render as native dokuwiki code syntax mode, e.g.
 *   <pre class='code {lang}'> ... </pre>
 *
 * if title is provide will render as follows
 *   <div class='source'>
 *     <p>{title}</p>
 *     <pre class='code {lang}'> ... </pre>
 *   </div>
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Christopher Smith <chris@jalakai.co.uk>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'luminous/luminous-v0.7.0/luminous.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_luminous_code extends DokuWiki_Syntax_Plugin {

    var $syntax = "";

    /**
     * return some info
     */
    function getInfo(){
      return array(
        'author' => 'Joseph Richardson',
        'email'  => 'j4h.r8n@gmail.com',
        'date'   => '2014-03-05',
        'name'   => 'Luminous Plugin',
        'desc'   => 'Replacement for Dokuwiki\'s own <code> handler, uses Luminous for syntax highlighting.',
        'url'    => 'http://www.dokuwiki.org/plugin:luminous',
      );
    }

    function getType(){ return 'protected';}
    function getPType(){ return 'block';}

    // must return a number lower than returned by native 'code' mode (200)
    function getSort(){ return 195; }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
      $this->Lexer->addEntryPattern('<code(?=[^\r\n]*?>.*?</code>)',$mode,'plugin_luminous_code');
    }

    function postConnect() {
      $this->Lexer->addExitPattern('</code>', 'plugin_luminous_code');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler){

        switch ($state) {
          case DOKU_LEXER_ENTER:
            $this->syntax = substr($match, 1);
            return false;

          case DOKU_LEXER_UNMATCHED:
             // will include everything from <code ... to ... </code >
             // e.g. ... [lang] [|title] > [content]
             list($attr, $content) = preg_split('/>/u',$match,2);
             list($lang, $title) = preg_split('/\|/u',$attr,2);

             if ($this->syntax == 'code') {
               $lang = trim($lang);
               if (!$lang) $lang = NULL;
             } else {
               $lang = NULL;
             }

             return array($this->syntax, $lang, trim($title), $content);
        }
        return false;
    }


    /**
     * Create output
     */
    function render($mode, &$renderer, $data) {

        if (count($data) == 4) {
          list($syntax, $lang, $title, $content) = $data;

          if($lang === null)
          {
            $lang = luminous::guess_language($content, $confidence=0.05, $default='plain');
          }

          $renderer->doc .= luminous::highlight($lang, trim($content));

        return true;
      }
      return false;
    }
}

//Setup VIM: ex: et ts=4 enc=utf-8 :