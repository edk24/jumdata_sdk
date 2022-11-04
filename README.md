
# 聚美智数SDK

[官网](https://my.jumdata.com/home)

**安装**

```
composer require yuxiaobo/jumdata
```

## 单元测试

```
./vendor/bin/phpunit test/SmsTest.php
```

## 短信SDK
> 已完成

- send 发送短信
- detail 查询短信发送详情
- getSignList 查询短信签名列表
- getTemplateList 查询短信模板列表


#### 发送短信

```php
$sms = new Sms($_ENV['APPID'], $_ENV['APPSECRET']);
list($success, $response) = $sms->send('183****014', 'MB7**3B8', ['5678']);

if ($success == false) {
    // 短信发送失败
}
```


## 更新记录

> 1.0.1

- [新增] 初始化实例时参数 `baseUrl`, 可自行选择用的短信通道(携号转网需要另一个通道)