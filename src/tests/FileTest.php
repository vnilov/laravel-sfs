<?php

use SimpleFileStorage\Eloquent\TestModel;

class FileTest extends TestCase
{
    protected static $id = null;
    protected static $model = null;
    private $_service;

    protected function setUp()
    {
        parent::setUp();
        if (!isset(static::$model)) {
            static::$model = factory(SimpleFileStorage\Eloquent\TestModel::class)->make();
            static::$id = static::$model->picture;
        }
    }

    public function testInstance()
    {
        $this->assertInstanceOf(TestModel::class, static::$model);
    }

    public function testValue()
    {
        $this->assertGreaterThan(0, static::$id);
    }

    public function testGetUrlByID()
    {
        $this->_service = $this->createApplication()->make('SimpleFileStorage\SFSFacade');
        $url = $this->_service->getUrl(static::$id);
        $this->assertFileExists($url);
    }
}
