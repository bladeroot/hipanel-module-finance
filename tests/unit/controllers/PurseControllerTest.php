<?php

/*
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\finance\tests\unit\controllers;

use hipanel\modules\finance\controllers\PurseController;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-04-27 at 13:36:04.
 */
class PurseControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PurseController
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new PurseController('test', null);
    }

    protected function tearDown()
    {
    }

    public function testActions()
    {
        $this->assertInternalType('array', $this->object->actions());
    }
}
