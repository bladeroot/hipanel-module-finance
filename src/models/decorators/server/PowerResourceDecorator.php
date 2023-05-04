<?php
/**
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\finance\models\decorators\server;

use Yii;

class PowerResourceDecorator extends AbstractServerResourceDecorator
{
    public function displayTitle()
    {
        return Yii::t('hipanel:finance:tariff', 'Power consumption');
    }

    public function displayValue()
    {
        return Yii::t('yii', '{nFormatted} W', ['nFormatted' => $this->getPrepaidQuantity()]);
    }

    public function displayUnit()
    {
        return Yii::t('hipanel:finance:tariff', 'kW');
    }

    public function toUnit(): string
    {
        return 'kW';
    }

    public function getPrepaidQuantity()
    {
        return 1;
    }

    public function getOverusePrice()
    {
        return null;
    }

    public function displayOverusePrice()
    {
        return null;
    }
}
