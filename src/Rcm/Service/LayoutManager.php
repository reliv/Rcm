<?php
/**
 * Rcm Layout Manager
 *
 * This file contains the class definition for the Layout Manager
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */
namespace Rcm\Service;

use Rcm\Exception\RuntimeException;

/**
 * Rcm Layout Manager
 *
 * Rcm Layout Manager.  This class handles the RCM theme engine.  Use this to get
 * the correct layouts for pages and sites.  Themes are defined in config as well
 * as in the database.  The Site Entity stores which theme is to be use on the site,
 * while the Page Entity will define a page template to use but can also override
 * the site layout default set from the site entity.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 */
class LayoutManager
{
    /** @var \Rcm\Service\SiteManager  */
    protected $siteManager;

    /** @var Array  */
    protected $config;

    /**
     * Constructor
     *
     * @param SiteManager $siteManager Rcm Site Manager
     * @param Array       $config      Config Array
     */
    public function __construct(SiteManager $siteManager, $config)
    {
        $this->siteManager = $siteManager;
        $this->config = $config;
    }

    /**
     * Get Layout method will attempt to locate and fetch the correct layout
     * for the site and page.  If found it will pass back the path to correct
     * view template so that the indexAction can pass that value on to the
     * renderer.
     *
     * @param string $layout Layout to find
     *
     * @return string
     * @throws RuntimeException
     */
    public function getLayout($layout=null)
    {
        $themeLayoutConfig = $this->getThemeLayoutConfig();

        if (empty($layout)) {
            $layout = $this->siteManager->getCurrentSiteDefaultLayout();
        }

        if (!empty($themeLayoutConfig[$layout])
            && !empty($themeLayoutConfig[$layout]['file'])
        ) {
            return $themeLayoutConfig[$layout]['file'];

        } elseif (!empty($themeLayoutConfig['default'])
            && !empty($themeLayoutConfig['default']['file'])
        ) {
            return $themeLayoutConfig['default']['file'];
        } else {
            throw new RuntimeException('No Layouts Found in config');
        }
    }

    /**
     * Get Page Template method will attempt to locate and fetch the correct template
     * for the page.  If found it will pass back the path to correct
     * view template so that the indexAction can pass that value on to the
     * renderer.  Default page template is set by the themes configuration and can
     * also be set per page.
     *
     * @param null $template Template to find
     *
     * @return string
     * @throws RuntimeException
     */
    public function getPageTemplate($template=null)
    {
        $themePageConfig = $this->getThemePageTemplateConfig();

        if (!empty($themePageConfig[$template])
            && !empty($themePageConfig[$template]['file'])
        ) {
            return $themePageConfig[$template]['file'];

        } elseif (!empty($themePageConfig['default'])
            && !empty($themePageConfig['default']['file'])
        ) {
            return $themePageConfig['default']['file'];
        } else {
            throw new RuntimeException('No Page Template Found in config');
        }
    }

    /**
     * Get the installed RCM Themes configuration.
     *
     * @return array
     * @throws \Rcm\Exception\RuntimeException
     */
    protected function getThemesConfig()
    {
        if (empty($this->config['Rcm'])
            || empty($this->config['Rcm']['themes'])
        ) {
            throw new RuntimeException(
                'No Themes found defined in configuration.  Please report the issue
                 with the themes author.'
            );
        }

        return $this->config['Rcm']['themes'];
    }

    /**
     * Find out if selected theme exists and has site layouts defined in config
     * or fallback to generic theme
     *
     * @return array Config Array For Theme
     * @throws RuntimeException
     */
    protected function getThemeLayoutConfig()
    {
        $theme = $this->siteManager->getCurrentSiteTheme();

        $rcmThemesConfig = $this->getThemesConfig();

        if (!empty($rcmThemesConfig[$theme])
            && !empty($rcmThemesConfig[$theme]['layouts'])
        ) {
            return $rcmThemesConfig[$theme]['layouts'];
        } elseif (!empty($rcmThemesConfig['generic'])
            && !empty($rcmThemesConfig['generic']['layouts'])
        ) {
            return $rcmThemesConfig['generic']['layouts'];
        }

        throw new RuntimeException(
            'No theme config found for site and no default theme found'
        );
    }

    /**
     * Find out if selected theme exists and has site layouts defined in config
     * or fallback to generic theme
     *
     * @return array Config Array For Theme
     * @throws RuntimeException
     */
    protected function getThemePageTemplateConfig()
    {
        $theme = $this->siteManager->getCurrentSiteTheme();
        $rcmThemesConfig = $this->getThemesConfig();

        if (!empty($rcmThemesConfig[$theme])
            && !empty($rcmThemesConfig[$theme]['pages'])
        ) {
            return $rcmThemesConfig[$theme]['pages'];
        } elseif (!empty($rcmThemesConfig['generic'])
            && !empty($rcmThemesConfig['generic']['pages'])
        ) {
            return $rcmThemesConfig['generic']['pages'];
        }

        throw new RuntimeException(
            'No theme config found for site and no default theme found'
        );
    }
}