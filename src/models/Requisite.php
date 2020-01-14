<?php
/**
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\finance\models;

use hipanel\modules\client\models\Contact;

/**
 * Class Requisite.
 */
class Requisite extends Contact
{
    /*
     * @return array the list of attributes for this record
     */
    use \hipanel\base\ModelTrait;

    public static function tableName()
    {
        return 'requisite';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'client_id', 'recipient_id'], 'integer'],
            [['id'], 'required', 'on' => ['reserve-number', 'set-templates', 'set-serie']],
            [['client_id', 'recipient_id'], 'required', 'on' => ['reserve-number']],
            [['invoice_id', 'acceptance_id', 'contract_id', 'probation_id'], 'safe'],
            [['serie'], 'safe'],
            [['serie'], 'required', 'on' => ['set-serie', 'update']],
        ]);
    }

    public function isRequisite()
    {
        return (boolean) $this->is_requisite;
    }

    public function isEmpty($fields) : bool
    {
        $fields = is_string($fields) ? array_map(function($v) {
            return trim($v);
        },  explode(",", $fields)) : $fields;

        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                return false;
            }
        }

        return true;
    }
}
