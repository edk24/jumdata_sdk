<?php
namespace jumdata\test;

use jumdata\Sms;
use PHPUnit\Framework\TestCase;
require_once './src/Sms.php';

class SmsTest extends TestCase  {

    /**
     * 测试发送短信
     * @test
     * @return void
     */
    public function testSend() {
        $this->assertIsObject($obj = new Sms('', ''));
        $this->assertIsArray($result = $obj->send('18311548014', 'MB717503B8', ['5678']));
        echo json_encode($result);
    }

    /**
     * 查询模板列表
     * @test
     */
    public function testGetTemplateList() {
        $this->assertIsObject($obj = new Sms('', ''));
        $this->assertIsArray($result = $obj->getTemplateList());
        echo json_encode($result);
    }

    /**
     * 获取短信发送状态
     * @test
     */
    public function testDetail() {
        $this->assertIsObject($obj = new Sms('', ''));
        $this->assertIsArray($result = $obj->detail('JS6656739414542289'));
        echo json_encode($result);
    }

    /**
     * 查询短信签名
     * @test
     */
    public function testGetSignList() {
        $this->assertIsObject($obj = new Sms('', ''));
        $this->assertIsArray($result = $obj->getSignList());
        var_dump($result);
    }
}