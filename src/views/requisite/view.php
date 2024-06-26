<?php

use hipanel\modules\client\grid\ContactGridView;
use hipanel\modules\finance\grid\RequisiteGridView;
use hipanel\modules\finance\menus\RequisiteDetailMenu;
use hipanel\modules\finance\models\Requisite;
use hipanel\widgets\Box;
use hipanel\widgets\ClientSellerLink;
use hiqdev\assets\flagiconcss\FlagIconCssAsset;
use yii\base\ViewNotFoundException;
use yii\helpers\Inflector;

/**
 * @var Requisite $model
 */
$this->title = Inflector::titleize($model->name, true);
$this->params['subtitle'] = Yii::t('hipanel:finance', 'Requisite detailed information') . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:finance', 'Requisites'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

FlagIconCssAsset::register($this);

?>

<div class="row">
    <div class="col-md-3">
        <?php Box::begin([
            'options' => [
                'class' => 'box-solid',
            ],
            'bodyOptions' => [
                'class' => 'no-padding',
            ],
        ]) ?>
            <?php try {
            ?>
                <div class="profile-user-img text-center">
                    <?= $this->render('//layouts/gravatar', ['email' => $model->email, 'size' => 120]) ?>
                </div>
            <?php
        } catch (ViewNotFoundException $e) {
            ?>
            <?php
        } ?>
            <p class="text-center">
                <span class="profile-user-role"><?= $this->title ?></span>
                <br>
                <span class="profile-user-name"><?= ClientSellerLink::widget(['model' => $model]) ?></span>
            </p>

            <div class="profile-usermenu">
                <?= RequisiteDetailMenu::widget(['model' => $model]) ?>
            </div>
        <?php Box::end() ?>
    </div>

    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6">
                <?php /***
                <?php $box = Box::begin(['renderBody' => false]) ?>
                    <?php $box->beginHeader() ?>
                        <?= $box->renderTitle(Yii::t('hipanel:client', 'Contact information')) ?>
                        <?php $box->beginTools() ?>
                            <?= Html::a(Yii::t('hipanel', 'Edit'), ['@contact/update', 'id' => $model->id], ['class' => 'btn btn-default btn-xs']) ?>
                        <?php $box->endTools() ?>
                    <?php $box->endHeader() ?>
                    <?php $box->beginBody() ?>
                        <?= RequisiteGridView::detailView([
                            'boxed'   => false,
                            'model'   => $model,
                            'columns' => [
                                'seller_id', 'client_id',
                                'name_with_verification',
                                'birth_date',
                                'email', 'abuse_email',
                                'voice_phone', 'fax_phone',
                            ],
                        ]) ?>
                    <?php $box->endBody() ?>
                <?php $box->end() ?>
                **/ ?>

                <?php $box = Box::begin([
                    'renderBody' => false,
                    'collapsed' => $model->isEmpty(['reg_data', 'vat_number', 'vat_rate', 'invoice_last_no']),
                    'collapsable' => true,
                    'title' => Yii::t('hipanel:client', 'Registration data'),
                ]) ?>
                    <?php $box->beginBody() ?>
                        <?= RequisiteGridView::detailView([
                            'boxed'   => false,
                            'model'   => $model,
                            'columns' => [
                                'reg_data', 'vat_rate',
                                'invoice_last_no', 'payment_request_last_no',
                                'sinvoice_last_no', 'spayment_request_last_no',
                                'pinvoice_last_no', 'ppayment_request_last_no',
                                'serie', 'registration_number', 'tic',
                            ],
                        ]) ?>
                    <?php $box->endBody() ?>
                <?php $box->end() ?>

                <?php $box = Box::begin([
                    'renderBody' => false,
                    'collapsed' => $model->isEmpty('bank_details'),
                    'collapsable' => true,
                    'title' => Yii::t('hipanel:client', 'Bank details'),
                    'bodyOptions' => ['class' => 'no-padding']
                ]) ?>
                    <?php $box->beginBody() ?>
                        <?= ContactGridView::detailView([
                            'boxed'   => false,
                            'model'   => $model,
                            'columns' => [
                                'requisites'
                            ],
                        ]) ?>
                    <?php $box->endBody() ?>
                <?php $box->end() ?>

                <?php $box = Box::begin(['renderBody' => false]) ?>
                    <?php $box->beginHeader() ?>
                        <?= $box->renderTitle(Yii::t('hipanel:client', 'Postal information')) ?>
                    <?php $box->endHeader() ?>
                    <?php $box->beginBody() ?>
                        <?= RequisiteGridView::detailView([
                            'boxed'   => false,
                            'model'   => $model,
                            'columns' => [
                                'first_name', 'last_name', 'organization',
                                'street', 'city', 'province', 'postal_code', 'country',
                            ],
                        ]) ?>
                    <?php $box->endBody() ?>
                <?php $box->end() ?>
            </div>

            <div class="col-md-6">
                <?php if (Yii::getAlias('@document', false) !== false && Yii::$app->user->can('document.read')) : ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-widget">
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <?= Yii::t('hipanel:finance', 'Requisite templates') ?>
                                    </h3>
                                    <div class="box-tools pull-right">
                                        <button data-toggle="modal" data-target="#set-templates-modal" type="button" class="btn btn-box-tool">
                                            <i class="fa fa-fw fa-exchange"></i>&nbsp;
                                            <?= Yii::t('hipanel:finance', 'Set templates') ?>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body no-padding">
                                    <?= RequisiteGridView::detailView([
                                        'boxed'   => false,
                                        'model'   => $model,
                                        'columns' => RequisiteGridView::getRequisiteColumns($model),
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php foreach ($model->balances ?? [] as $currency => $data) : ?>
                    <?php $box = Box::begin(['renderBody' => false]) ?>
                        <?php $box->beginHeader() ?>
                            <?= $box->renderTitle(strtoupper($currency)) ?>
                        <?php $box->endHeader() ?>
                        <?php $box->beginBody() ?>
                            <?php $model->balance = $data ?>
                            <?= RequisiteGridView::detailView([
                                'boxed'   => false,
                                'model'   => $model,
                                'columns' => [
                                    'balance',
                                    'debit',
                                    'credit',
                                ],
                            ]) ?>
                        <?php $box->endBody() ?>
                    <?php $box->end() ?>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>
