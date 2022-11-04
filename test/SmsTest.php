<?php
namespace jumdata\test;

// require_once '../src/Sms.php';
use jumdata\Sms;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

$env = new Dotenv();
$env->load(dirname(__DIR__) . '/.env');

class SmsTest extends TestCase  {



    /**
     * 测试发送短信
     * @test
     * @return void
     */
    public function testSend() {
        $sms = new Sms($_ENV['APPID'], $_ENV['APPSECRET'], false);
        list($success, $response) = $sms->send('18311548014', 'MB717503B8', ['5678']);
        $this->assertTrue($success);
        $this->assertIsArray($response);
        var_dump($response);
    }

    /**
     * 查询模板列表
     * @test
     */
    public function testGetTemplateList() {
        $sms = new Sms($_ENV['APPID'], $_ENV['APPSECRET']);
        list($success, $response) = $sms->getTemplateList();
        $this->assertTrue($success);
    }

    /**
     * 获取短信发送状态
     * @test
     */
    public function testDetail() {
        $sms = new Sms($_ENV['APPID'], $_ENV['APPSECRET']);
        list($success, $response) = $sms->detail('JS6656739414542289', '18311548014');
        $this->assertTrue($success);
    }

    /**
     * 查询短信签名
     * @test
     */
    public function testGetSignList() {
        $sms = new Sms($_ENV['APPID'], $_ENV['APPSECRET']);
        list($success, $response) = $sms->getSignList();
        $this->assertTrue($success);
    }
}