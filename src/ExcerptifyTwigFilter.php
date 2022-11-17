<?php
/**
 * Excerptify Twig Filter plugin for Craft CMS 4.x
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

    public ?string $name = 'Excerptify';

    public static ExcerptifyTwigFilter $plugin;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->view->registerTwigExtension(new ExcerptifyTwigExtension());

        Craft::info(
            Craft::t(
                'craft-excerptify',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }
}
