<?php

namespace hipanel\modules\finance\widgets;

use hipanel\widgets\Type;

class PriceType extends Type
{
    public $values = [];
    public $defaultValues = [
        'success' => [
            'monthly,*'
        ],
        'info' => [
            'overuse,*'
        ],
        'warning' => [
            'monthly,monthly',
        ],
        'primary' => [
            'monthly,leasing',
        ],
        'default' => [
            'monthly,hardware',
        ],
    ];
    public $field = 'type';
    public $i18nDictionary = 'hipanel.finance.priceTypes';

    /** {@inheritdoc} */
    protected function titlelize($label): string
    {
        return parent::titlelize(substr($label, strpos($label, ',')+1));
    }
}


