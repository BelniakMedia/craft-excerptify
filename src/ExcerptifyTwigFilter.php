<?php
/**
 * Excerptify Twig Filter plugin for Craft CMS 3.x
 *
 * Provides the 'excerptify' twig filter which truncates the provided variable's text or html to the nearest whole word based on the provided character length.
 *
 * @link      http://www.belniakmedia.com
 * @copyright Copyright (c) 2017 Belniak Media Inc.
 */

namespace belniakmedia\craftExcerptify;

use belniakmedia\craftExcerptify\twigextensions\ExcerptifyTwigExtension;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use yii\base\Event;

/**
 * Class RemarryTwigFilter
 *
 * @author    Belniak Media Inc.
 * @package   craft-excerptify
 * @since     1.0.0
 *
 */
class ExcerptifyTwigFilter extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var ExcerptifyTwigFilter
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->view->registerTwigExtension(new ExcerptifyTwigExtension());

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'craft-excerptify',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
