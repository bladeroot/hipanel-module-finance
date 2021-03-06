<?php

/*
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

/**
 * @var \yii\web\View
 * @var $search \hipanel\widgets\AdvancedSearch
 * @var array $types
 */
use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\client\widgets\combo\SellerCombo;
use hiqdev\combo\StaticCombo;

?>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('tariff') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('note') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('type')->widget(StaticCombo::class, [
        'data' => $types,
        'hasId' => true,
        'multiple' => true,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('client_id')->widget(ClientCombo::class, ['formElementSelector' => '.form-group']) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('seller_id')->widget(SellerCombo::class, ['formElementSelector' => '.form-group']) ?>
</div>
