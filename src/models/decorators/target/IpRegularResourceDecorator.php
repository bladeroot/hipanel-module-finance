<?php
/**
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

declare(strict_types=1);

namespace hipanel\modules\finance\models\decorators\target;

use hipanel\modules\finance\models\decorators\server\AbstractServerResourceDecorator;
use Yii;

class IpRegularResourceDecorator extends AbstractServerResourceDecorator
{
    public function displayTitle()
    {
        return Yii::t('hipanel.finance.resource', 'Regular IP');
    }

    public function displayUnit()
    {
        return Yii::t('hipanel', 'IP');
    }

    public function displayValue()
    {
        return $this->resource->quantity;
    }
}
