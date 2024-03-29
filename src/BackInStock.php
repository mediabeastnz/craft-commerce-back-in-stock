<?php
/**
 * Back In Stock plugin for Craft CMS 3.x
 *
 * Back in stock Craft Commerce 2 plugin
 *
 * @link      https://www.mylesthe.dev/
 * @copyright Copyright (c) 2019 Myles Beardsmore
 */

namespace mediabeastnz\backinstock;

use mediabeastnz\backinstock\services\BackInStockService as BackInStockServiceService;
use mediabeastnz\backinstock\records\BackInStockRecord;
use mediabeastnz\backinstock\models\BackInStockModel;
use mediabeastnz\backinstock\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\ModelEvent;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use craft\commerce\elements\Variant;
use craft\services\Elements;

use yii\base\Event;

/**
 * Class BackInStock
 *
 * @author    Myles Beardsmore
 * @package   BackInStock
 *
 * @property  BackInStockServiceService $backInStockService
 */
class BackInStock extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var BackInStock
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    public bool $hasCpSettings = true;
    public bool $hasCpSection = true;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->setComponents([
            'backInStockService' => BackInStockServiceService::class,
        ]);

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['register-interest'] = '/craft-commerce-back-in-stock/base/register-interest';
            }
        );

        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function (RegisterUrlRulesEvent $event) {
            $event->rules = array_merge($event->rules, [
                'back-in-stock' => 'craft-commerce-back-in-stock/base/logs',
                'back-in-stock/logs' => 'craft-commerce-back-in-stock/base/logs',
            ]);
        });

        Craft::info(
            Craft::t(
                'craft-commerce-back-in-stock',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );

        Event::on(Variant::class, Variant::EVENT_BEFORE_SAVE, function(ModelEvent $event) {
            $variant = $event->sender;
            if ($variant->stock > 0 || $variant->hasUnlimitedStock) {
                $this->backInStockService->isBackInStock($variant);
            }
        });

    }

    public function getCpNavItem(): ?array
    {
        $navItem = parent::getCpNavItem();
        $navItem['label'] = "Back in Stock";
        $navItem['url'] = 'back-in-stock';
        return $navItem;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): ?\craft\base\Model
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate(
            'craft-commerce-back-in-stock/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
