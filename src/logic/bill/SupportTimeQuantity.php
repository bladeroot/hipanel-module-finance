<?php
/**
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\finance\logic\bill;

use Yii;

/**
 * Class SupportTimeQuantity.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class SupportTimeQuantity extends DefaultQuantityFormatter
{
    public function format(): string
    {
        return Yii::t('hipanel:finance', '{hours}:{minutes}', [
            'quantity' => Yii::$app->formatter->asDuration($this->getQuantity()->getQuantity() * 3600),
            'hours' => floor($this->getQuantity()->getQuantity()),
            'minutes' => Yii::$app->formatter->asTime($this->getQuantity()->getQuantity() * 3600, 'mm'),
        ]);
    }
}
