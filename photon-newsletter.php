<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

use Grav\Common\Grav;
use Grav\Common\Page\Collection;
use Grav\Common\Page\Page;
use Grav\Common\Page\Pages;
use Grav\Common\Taxonomy;

/**
 * Class PhotonNewsletterPlugin
 * @package Grav\Plugin
 */
class PhotonNewsletterPlugin extends Plugin
{

    public static function getSubscribedEvents()
    {
      return [
        'onPluginsInitialized' => ['onPluginsInitialized', 0],
        'onGetPageTemplates' => ['onGetPageTemplates', 0]
      ];
    }

    public function onPluginsInitialized()
    {

      if ( $this->isAdmin() ) {

        $this->enable([
          'onAdminSave' => ['onAdminSave', 0], //from events plugin - not sure if necessary
          // 'onGetPageTemplates' => ['onGetPageTemplates', 0]
        ]);

        return;
      }

      $this->enable([
        'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
        'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
      ]);

      return;

    }

    // called when saving page in admin
    public function onAdminSave(Event $event)
    {
      // placeholder
    }


    /** Add blueprint directories for admin templates.     */
    public function onGetPageTemplates(Event $event)
    {
        $types = $event->types;
        $locator = Grav::instance()['locator'];
        $types->scanBlueprints($locator->findResource('plugin://' . $this->name . '/blueprints'));
        $types->scanTemplates($locator->findResource('plugin://' . $this->name . '/templates'));
    }

    /**  Add current directory to twig lookup paths     */
    public function onTwigTemplatePaths()
    {
      $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }


    public function onTwigSiteVariables()
    {
      // setup
      $page = 			$this->grav['page'];
      $pages = 			$this->grav['pages'];
      // $collection = $pages->all()->ofType('event');
      $twig = 			$this->grav['twig'];
      $assets = 		$this->grav['assets'];

      // only load the vars if this datatype page
      if ($page->template() == 'newsletter')
      {

        // styles
        if ($this->config->get('plugins.photon-newsletter.built_in_css')) {
          $css = 'plugin://photon-newsletter/assets/newsletter.css';
          $assets->addCss($css, 100, false, 'photon-plugins' );
        }

        // scripts
        if ($this->config->get('plugins.photon-newsletter.built_in_js')) {
          $js = 'plugin://photon-newsletter/assets/newsletter.js';
          $assets->addJs($js, 100, false, 'defer', 'photon-plugins' );
        }


      }
    }

}
