<?php

namespace jumdata;

use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;

use function GuzzleHttp\Psr7\build_query;

/**
 * 短信SDK
 */
class Sms {

    protected $app_id = null;
    protected $app_secret = null;
    // 调试模式
    protected $debug = false;


    public function __construct($app_id='', $app_secret='', $debug=false)
    {
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
        $this->debug = $debug;
    }


    /**
     * 发送短信通知
     *
     * @param string $mobile 手机号
     * @param string $template_id 短信模板id
     * @param array $tag 参数，用做替代模板中的@1@变量
     * @return array
     */
    public function send($mobile, $template_id, $tag=[]) {
        $client = new \GuzzleHttp\Client([
            'base_uri'        => 'https://api.jumdata.com/',
            'timeout'         => 0,
            'debug'           => $this->debug
        ]);
        $data = [
            ['name' => 'appId', 'contents'=>$this->app_id],
            ['name' => 'timestamp', 'contents'=>time()*1000],
            ['name' => 'receive', 'contents'=>$mobile],
            ['name' => 'templateId', 'contents'=>$template_id],
            ['name' => 'tag', 'contents'=>implode('|', $tag)],
            ['name' => 'sign', 'contents'=>hash('sha256', $this->app_id . $this->app_secret . time()*1000)],
        ];

        $response = $client->post('/sms/send', ['multipart' => $data]);
        if ($response->getStatusCode() != 200) {
            return null;
        }
        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * 查询短信发送详情
     *
     * @param string $taskid
     * @return array
     */
    public function detail($taskid) {
        $client = new \GuzzleHttp\Client([
            'base_uri'        => 'https://api.jumdata.com/',
            'timeout'         => 0,
            'debug'           => $this->debug
        ]);
        
        $query = http_build_query([
            'appId'     => $this->app_id,
            'timestamp' => time()*1000,
            'sign'      => hash('sha256', $this->app_id . $this->app_secret . time()*1000),
            'taskId'    => $taskid,
        ]);

        $response = $client->get('/sms/detail?' . $query);
        if ($response->getStatusCode() != 200) {
            return null;
        }
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 查询短信签名列表
     *
     * @param string $signId 可空，指定短信签名id，留空查询所有
     * @return void
     */
    public function getSignList($sign_id='') {
        $client = new \GuzzleHttp\Client([
            'base_uri'        => 'https://api.jumdata.com/',
            'timeout'         => 0,
            'debug'           => $this->debug
        ]);
        
        $query = http_build_query([
            'appId'     => $this->app_id,
            'timestamp' => time()*1000,
            'sign'      => hash('sha256', $this->app_id . $this->app_secret . time()*1000),
            'signId'    => $sign_id,
        ]);

        $response = $client->get('/sms/sign/list?' . $query);
        if ($response->getStatusCode() != 200) {
            return null;
        }
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 查询短信模板列表
     *
     * @param string $template_id 可空，指定查询短信模板，留空查询所有
     * @return array
     */
    public function getTemplateList($template_id='') {
        $client = new \GuzzleHttp\Client([
            'base_uri'        => 'https://api.jumdata.com/',
            'timeout'         => 0,
            'debug'           => $this->debug
        ]);
        $data = [
            [
                'name'      => 'appId',
                'contents'  => $this->app_id,
            ],
            [
                'name'      => 'timestamp',
                'contents'  => time()*1000,
            ],
            [
                'name'      => 'templateId',
                'contents'  => $template_id,
            ],
            [
                'name'      => 'sign',
                'contents'  => hash('sha256', $this->app_id . $this->app_secret . time()*1000)
            ]
        ];
        $response = $client->post('/sms/template/list', ['multipart' => $data]);

        if ($response->getStatusCode() != 200) {
            return null;
        }
        return json_decode($response->getBody()->getContents(), true);
    }
}