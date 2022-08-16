<?php
/**
 * Back In Stock plugin for Craft CMS 3.x
 *
 * Back in stock Craft Commerce 2 plugin
 *
 * @link      https://www.mylesthe.dev/
 * @copyright Copyright (c) 2019 Myles Beardsmore
 */

namespace mediabeastnz\backinstock\models;

use mediabeastnz\backinstock\BackInStock;

use Craft;
use craft\base\Model;

/**
 * @author    Myles Beardsmore
 * @package   BackInStock
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $fromEmail = '';

    /**
     * @var string
     */
    public $fromName = '';

    /**
     * @var bool
     */
    public $sendConfirmation = false;

    /**
     * @var string
     */
    public $confirmationEmailTemplate = 'craft-commerce-back-in-stock/emails/confirmation';

    /**
     * @var string
     */
    public $confirmationEmailSubject = 'Back in stock notification confirmation for {{variant.title}}';
    
    /**
     * @var string
     */
    public $emailTemplate = 'craft-commerce-back-in-stock/emails/notification';

    /**
     * @var string
     */
    public $emailSubject = 'Order today, {{variant.title}} is now in stock';

    /**
     * @var bool
     */
    public $purgeRequests = false;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['emailTemplate', 'emailSubject', 'confirmationEmailTemplate', 'confirmationEmailSubject'], 'string'],
            [['purgeRequests', 'sendConfirmation'], 'boolean'],
            [['emailTemplate', 'emailSubject'], 'required']
        ];
    }
}
