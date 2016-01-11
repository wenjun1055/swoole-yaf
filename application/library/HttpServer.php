<?php

/**
 * @Author: WenJun
 * @Date  :   15/10/23 15:24
 * @Email :  wenjun01@baidu.com
 * @File  :   HttpServer.php
 * @Desc  :   ...
 */
class HttpServer
{
    /**
     * Variable  defaultIp
     * 默认监听绑定IP
     * @author   WenJun <wenjun01@baidu.com>
     * @var      string
     */
    private $defaultIp = '0.0.0.0';

    /**
     * Variable  defaultPort
     * 默认监听绑定端口
     * @author   WenJun <wenjun01@baidu.com>
     * @var      int
     */
    private $defaultPort = 8080;

    /**
     * Variable  serverConfig
     * @author   WenJun <wenjun01@baidu.com>
     * @var
     */
    private $serverConfig;

    /**
     * Variable  appConfigFile
     * @author   WenJun <wenjun01@baidu.com>
     * @var
     */
    private $appConfigFile;

    /**
     * Variable  serverObj
     * @author   WenJun <wenjun01@baidu.com>
     * @var
     */
    private $serverObj;

    /**
     * Variable  yafAppObj
     * @author   WenJun <wenjun01@baidu.com>
     * @var
     */
    private $yafAppObj;

    /**
     * Variable  instance
     * @author   WenJun <wenjun01@baidu.com>
     * @static
     * @var      null
     */
    protected static $instance = null;

    /**
     * Method  getInstance
     * @desc   获取对象
     * @author WenJun <wenjun01@baidu.com>
     * @static
     * @return HttpServer|null
     */
    public static function getInstance()
    {
        if (empty(self::$instance) || !(self::$instance instanceof HttpServer)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * __construct
     */
    private function __construct()
    {
    }

    /**
     * Method  setServerConfigIni
     * @desc   设置server_config
     * @author WenJun <wenjun01@baidu.com>
     * @param $serverConfigIni
     * @return void
     */
    public function setServerConfigIni($serverConfigIni)
    {
        if (!is_file($serverConfigIni)) {
            trigger_error('Server Config File Not Exist!', E_USER_ERROR);
        }

        $serverConfig = parse_ini_file($serverConfigIni, true);
        if (empty($serverConfig)) {
            trigger_error('Server Config Content Empty!', E_USER_ERROR);
        }

        $this->serverConfig = $serverConfig;
    }

    /**
     * Method  setAppConfigIni
     * @desc   ......
     * @author WenJun <wenjun01@baidu.com>
     * @param $appConfigIni
     * @return void
     */
    public function setAppConfigIni($appConfigIni)
    {
        if (!is_file($appConfigIni)) {
            trigger_error('Server Config File Not Exist!', E_USER_ERROR);
        }

        $this->appConfigFile = $appConfigIni;
    }

    /**
     * Method  start
     * @desc   启动server
     * @author WenJun <wenjun01@baidu.com>
     * @return void
     */
    public function start()
    {
        $ip   = isset($this->serverConfig['server']['ip']) ? $this->serverConfig['server']['ip'] : $this->defaultIp;
        $port = isset($this->serverConfig['server']['port']) ? $this->serverConfig['server']['port'] : $this->defaultPort;

        $this->serverObj = new swoole_http_server($ip, $port);
        $this->serverObj->set($this->serverConfig['swoole']);
        $this->serverObj->on('Start', array($this, 'onStart'));
        $this->serverObj->on('ManagerStart', array($this, 'onManagerStart'));
        $this->serverObj->on('WorkerStart', array($this, 'onWorkerStart'));
        $this->serverObj->on('WorkerStop', array($this, 'onWorkerStop'));
        $this->serverObj->on('request', array($this, 'onRequest'));
        $this->serverObj->start();
    }

    /**
     * Method  onStart
     * @desc   master start
     *
     * @author WenJun <wenjun01@baidu.com>
     * @param swoole_http_server $serverObj
     *
     * @return bool
     */
    public function onStart(swoole_http_server $serverObj)
    {
        //rename
        swoole_set_process_name($this->serverConfig['server']['master_process_name']);

        return true;
    }

    /**
     * Method  onManagerStart
     * @desc   manager process start
     *
     * @author WenJun <wenjun01@baidu.com>
     * @param swoole_http_server $serverObj
     *
     * @return bool
     */
    public function onManagerStart(swoole_http_server $serverObj)
    {
        //rename
        swoole_set_process_name($this->serverConfig['server']['manager_process_name']);

        return true;
    }

    /**
     * Method  onWorkerStart
     * @desc   worker process start
     * @author WenJun <wenjun01@baidu.com>
     * @param swoole_http_server $server
     * @param                    $workerId
     * @return bool
     */
    public function onWorkerStart(swoole_http_server $serverObj, $workerId)
    {
        //rename
        $processName = sprintf($this->serverConfig['server']['event_worker_process_name'], $workerId);
        swoole_set_process_name($processName);

        //实例化yaf
        $this->yafAppObj = new Yaf_Application($this->appConfigFile);

        return true;
    }

    /**
     * Method  onWorkerStop
     * @desc   worker process stop
     *
     * @author WenJun <wenjun01@baidu.com>
     * @param swoole_http_server $serverObj
     * @param                    $workerId
     *
     * @return bool
     */
    public function onWorkerStop(swoole_http_server $serverObj, $workerId)
    {
        return true;
    }

    /**
     * Method  onRequest
     * @desc   http 请求部分
     * @author WenJun <wenjun01@baidu.com>
     * @param swoole_http_request  $request
     * @param swoole_http_response $response
     * @return void
     */
    public function onRequest(swoole_http_request $request, swoole_http_response $response)
    {
        //清理环境
        Yaf_Registry::flush();
        Yaf_Dispatcher::destoryInstance();

        //注册全局信息
        $this->initRequestParam($request);
        Yaf_Registry::set('SWOOLE_HTTP_REQUEST', $request);
        Yaf_Registry::set('SWOOLE_HTTP_RESPONSE', $response);

        //执行
        ob_start();
        try {
            $requestObj = new Yaf_Request_Http($request->server['request_uri']);

            $configArr  = Yaf_Application::app()->getConfig()->toArray();
            if (!empty($configArr['application']['baseUri'])) { //set base_uri
                $requestObj->setBaseUri($configArr['application']['baseUri']);
            }

            $this->yafAppObj->bootstrap()->getDispatcher()->dispatch($requestObj);
        } catch (Yaf_Exception $e) {
            var_dump($e);
        }

        $result = ob_get_contents();
        ob_end_clean();

        $response->end($result);
    }

    /**
     * Method  initRequestParam
     * @desc   将请求信息放入全局注册器中
     * @author WenJun <wenjun01@baidu.com>
     * @param swoole_http_request $request
     * @return bool
     */
    private function initRequestParam(swoole_http_request $request)
    {
        //将请求的一些环境参数放入全局变量桶中
        $server = isset($request->server) ? $request->server : array();
        $header = isset($request->header) ? $request->header : array();
        $get    = isset($request->get) ? $request->get : array();
        $post   = isset($request->post) ? $request->post : array();
        $cookie = isset($request->cookie) ? $request->cookie : array();
        $files  = isset($request->files) ? $request->files : array();

        Yaf_Registry::set('REQUEST_SERVER', $server);
        Yaf_Registry::set('REQUEST_HEADER', $header);
        Yaf_Registry::set('REQUEST_GET', $get);
        Yaf_Registry::set('REQUEST_POST', $post);
        Yaf_Registry::set('REQUEST_COOKIE', $cookie);
        Yaf_Registry::set('REQUEST_FILES', $files);
        Yaf_Registry::set('REQUEST_RAW_CONTENT', $request->rawContent());

        return true;
    }
}