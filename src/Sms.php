<?php

namespace jumdata;


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
     * @return array [bool:success, array:responseArray]
     * @throws \GuzzleHttp\Exception\*
     */
    public function send(string $mobile, string $template_id, array $tag=[]):array {
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
        $responseArr = json_decode($response->getBody()->getContents(), true);

        $success = false;
        if (isset($responseArr['success']) && $responseArr['success'] == true) {
            $success = true;
        }
        
        return [$success, $responseArr];
    }




    
    /**
     * 发送原始文本短信
     *
     * @param string $mobile
     * @param string $content
     * @return array [bool:success, array:responseArr]
     * @deprecated 
     * @throws \GuzzleHttp\Exception\*
     */
    public function sendRawContent(string $mobile, string $content):array 
    {
        throw new \RuntimeException('不支持');
    }





    /**
     * 查询短信发送详情
     *
     * @param string $taskid
     * @return array [bool:success, array:responseArr]
     * @throws \GuzzleHttp\Exception\*
     */
    public function detail(string $taskid, string $mobile):array {
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
            'mobile'    => $mobile
        ]);

        $response = $client->get('/sms/detail?' . $query);
        $responseArr = json_decode($response->getBody()->getContents(), true);
        
        $success = false;
        if (isset($responseArr['success']) && $responseArr['success'] == true) {
            $success = true;
        }

        return [$success, $responseArr];
    }





    /**
     * 查询短信签名列表
     *
     * @param string $signId 可空，指定短信签名id，留空查询所有
     * @return array [bool:success, array:responseArr]
     * @throws \GuzzleHttp\Exception\*
     */
    public function getSignList(string $sign_id=''):array {
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
        $responseArr = json_decode($response->getBody()->getContents(), true);

        $success = false;
        if (isset($responseArr['success']) && $responseArr['success'] == true) {
            $success = true;
        }

        return [$success, $responseArr];
    }






    /**
     * 查询短信模板列表
     *
     * @param string $template_id 可空，指定查询短信模板，留空查询所有
     * @return array [bool:success, array:responseArr]
     * @throws \GuzzleHttp\Exception\*
     */
    public function getTemplateList(string $template_id=''):array {
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

        $responseArr = json_decode($response->getBody()->getContents(), true);

        $success = false;
        if (isset($responseArr['success']) && $responseArr['success'] == true) {
            $success = true;
        }

        return [$success, $responseArr];
    }
}