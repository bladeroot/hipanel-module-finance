<?php

/*
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\finance;

use hipanel\modules\finance\models\Merchant;
use Yii;

class Plugin extends \hiqdev\pluginmanager\Plugin
{
    public function items()
    {
        return [
            'aliases' => [
                '@bill'   => '/finance/bill',
                '@purse'  => '/finance/purse',
                '@tariff' => '/finance/tariff',
                '@pay'    => '/merchant/pay',
                '@cart'   => '/cart/cart',
            ],
            'menus' => [
                'hipanel\modules\finance\SidebarMenu',
            ],
            'modules' => [
                'finance' => [
                    'class' => 'hipanel\modules\finance\Module',
                ],
                'cart' => [
                    'class'          => 'hiqdev\yii2\cart\Module',
                    'termsPage'      => Yii::$app->params['orgUrl'] . 'rules',
                    'orderPage'      => '/finance/cart/select',
                    /*'orderButton'    => function ($module) {
                        return Yii::$app->getView()->render('@hipanel/modules/finance/views/cart/order-button', [
                            'module' => $module,
                        ]);
                    },*/
                    'paymentMethods' => function () {
                        return Yii::$app->getView()->render('@hipanel/modules/finance/views/cart/payment-methods', [
                            'merchants' => Yii::$app->getModule('merchant')->getCollection([])->getItems(),
                        ]);
                    },
                    'shoppingCartOptions' => [
                        'on cartChange' => ['hipanel\modules\finance\cart\CartCalculator', 'execute'],
                    ],
                ],
                'merchant' => [
                    'class'           => 'hiqdev\yii2\merchant\Module',
                    'returnPage'      => '/finance/pay/return',
                    'notifyPage'      => '/finance/pay/notify',
                    'finishPage'      => '/finance/bill',
                    'depositClass'    => 'hipanel\modules\finance\merchant\Deposit',
                    'collectionClass' => 'hipanel\modules\finance\merchant\Collection',
                ],
            ],
            'components' => [
                'i18n' => [
                    'translations' => [
                        'hipanel/finance' => [
                            'class' => 'yii\i18n\PhpMessageSource',
                            'basePath' => '@hipanel/modules/finance/messages',
                            'fileMap' => [
                                'hipanel/finance' => 'finance.php',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
