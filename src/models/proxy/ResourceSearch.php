<?php

namespace hipanel\modules\finance\models\proxy;

use hipanel\base\SearchModelTrait;
use hipanel\helpers\ArrayHelper;

class ResourceSearch extends Resource
{
    use SearchModelTrait {
        SearchModelTrait::searchAttributes as defaultSearchAttributes;
    }

    public function searchAttributes(): array
    {
        return ArrayHelper::merge(
            $this->defaultSearchAttributes(),
            [
                'object',
                'groupby',
                'time_from',
                'time_till',
            ]
        );
    }
}
