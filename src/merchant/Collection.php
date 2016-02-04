<?php

/*
 * Finance Plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\finance\merchant;

use hipanel\modules\finance\models\Merchant;
use hiqdev\hiart\ErrorResponseException;
use Yii;

class Collection extends \hiqdev\yii2\merchant\Collection
{
    public function init()
    {
        parent::init();
        $this->loadMerchants($this->params);
    }

    public function loadMerchants(array $params = null)
    {
        $this->addItems($this->fetchMerchants($params));
    }

    public function fetchMerchants(array $params = [])
    {
        $merchants = [];
        $params = array_merge([
            'sum'      => $params['amount'] ?: 1,
            'site'     => Yii::$app->request->getHostInfo(),
            'username' => Yii::$app->user->identity->username,
        ], (array) $params);

        try {
            $merchants = Merchant::perform('PrepareInfo', $params, true);
        } catch (ErrorResponseException $e) {
            if ($e->response === null) {
                Yii::info('No available payment methods found', 'hipanel/finance');
                $merchants = [];
            } else {
                throw $e;
            }
        }

        foreach ($merchants as $name => $merchant) {
            if ($merchant['system'] === 'wmdirect') {
                unset($merchants[$name]);
                continue; // WebMoney Direct is not a merchant indeed. TODO: remove
            }
            $merchants[$name] = $this->convertMerchant($merchant);
        }

        return $merchants;
    }

    public function convertMerchant($data)
    {
        return [
            'gateway'   => $data['label'],
            'data'      => [
                'purse'     => $data['purse'],
                'amount'    => $data['sum'],
                'fee'       => $data['fee'],
                'currency'  => strtoupper($data['currency']),
            ],
        ];
    }
}
