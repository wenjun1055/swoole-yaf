<?php

/**
 * @Author: WenJun
 * @Date  :   15/10/26 09:41
 * @Email :  wenjun01@baidu.com
 * @File  :   Controllers.php
 * @Desc  :   ...
 */
class Base_Controllers extends Yaf_Controller_Abstract
{

    /**
     * Method  getServer
     * @desc   _SERVER
     *         http://wiki.swoole.com/wiki/page/341.html
     * @author WenJun <wenjun01@baidu.com>
     * @param null $param
     * @return bool|mixed
     */
    public function getServer($param = null)
    {
        $server = Yaf_Registry::get('REQUEST_SERVER');

        if (null === $param) {
            return $server;
        }

        if (isset($server[$param])) {
            return $server[$param];
        }

        return false;
    }

    /**
     * Method  getHeader
     * @desc   _HEADER
     *         http://wiki.swoole.com/wiki/page/332.html
     * @author WenJun <wenjun01@baidu.com>
     * @param null $param
     * @return bool|mixed
     */
    public function getHeader($param = null)
    {
        $header = Yaf_Registry::get('REQUEST_HEADER');

        if (null === $param) {
            return $header;
        }

        if (isset($header[$param])) {
            return $header[$param];
        }

        return false;
    }

    /**
     * Method  getQuery
     * @desc   _GET
     *         http://wiki.swoole.com/wiki/page/333.html
     * @author WenJun <wenjun01@baidu.com>
     * @param null $param
     * @return bool|mixed
     */
    public function getQuery($param = null)
    {
        $get = Yaf_Registry::get('REQUEST_GET');

        if (null === $param) {
            return $get;
        }

        if (isset($get[$param])) {
            return $get[$param];
        }

        return false;
    }

    /**
     * Method  getPost
     * @desc    _POST
     *          http://wiki.swoole.com/wiki/page/334.html
     * @author  WenJun <wenjun01@baidu.com>
     * @param null $param
     * @return bool|mixed
     */
    public function getPost($param = null)
    {
        $post = Yaf_Registry::get('REQUEST_POST');

        if (null === $param) {
            return $post;
        }

        if (isset($post[$param])) {
            return $post[$param];
        }

        return false;
    }

    /**
     * Method  getCookie
     * @desc   _COOKIE
     *         http://wiki.swoole.com/wiki/page/335.html
     * @author WenJun <wenjun01@baidu.com>
     * @param null $param
     * @return bool|mixed
     */
    public function getCookie($param = null)
    {
        $cookie = Yaf_Registry::get('REQUEST_COOKIE');

        if (null === $param) {
            return $cookie;
        }

        if (isset($cookie[$param])) {
            return $cookie[$param];
        }

        return false;
    }

    /**
     * Method  getFiles
     * @desc   _FILES
     *         http://wiki.swoole.com/wiki/page/428.html
     * @author WenJun <wenjun01@baidu.com>
     * @param null $param
     * @return bool|mixed
     */
    public function getFiles($param = null)
    {
        $files = Yaf_Registry::get('REQUEST_FILES');

        if (null === $param) {
            return $files;
        }

        if (isset($files[$param])) {
            return $files[$param];
        }

        return false;
    }

    /**
     * Method  getRawContent
     * @desc   raw_content
     *         http://wiki.swoole.com/wiki/page/375.html
     * @author WenJun <wenjun01@baidu.com>
     * @return mixed
     */
    public function getRawContent()
    {
        return Yaf_Registry::get('REQUEST_RAW_CONTENT');
    }

    /**
     * Method  setHeader
     * @desc   设置header
     *         http://wiki.swoole.com/wiki/page/336.html
     *
     * @author WenJun <wenjun01@baidu.com>
     * @param $key
     * @param $value
     *
     * @return bool
     */
    public function setHeader($key, $value)
    {
        if (empty($key) && empty($value)) {
            return false;
        }

        $responseObj = Yaf_Registry::get('SWOOLE_HTTP_RESPONSE');
        return $responseObj->header($key, $value);
    }

    /**
     * Method  setCookie
     * @desc   设置cookie
     *         http://wiki.swoole.com/wiki/page/337.html
     *
     * @author WenJun <wenjun01@baidu.com>
     * @param        $key
     * @param string $value
     * @param int    $expire
     * @param string $path
     * @param string $domain
     * @param bool   $secure
     * @param bool   $httponly
     *
     * @return bool
     */
    public function setCookie($key, $value = '', $expire = 0, $path = '/',
                              $domain = '', $secure = false, $httponly = false)
    {
        if (empty($key)) {
            return false;
        }

        $responseObj = Yaf_Registry::get('SWOOLE_HTTP_RESPONSE');
        return $responseObj->cookie($key, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * Method  setHttpCode
     * @desc   设置http_code
     *         http://wiki.swoole.com/wiki/page/338.html
     *
     * @author WenJun <wenjun01@baidu.com>
     * @param $code
     *
     * @return bool
     */
    public function setHttpCode($code)
    {
        if (empty($code) || $code < 0) {
            return false;
        }

        $responseObj = Yaf_Registry::get('SWOOLE_HTTP_RESPONSE');
        return $responseObj->status($code);
    }

    /**
     * Method  setGzip
     * @desc   设置压缩比例
     *         http://wiki.swoole.com/wiki/page/410.html
     *
     * @author WenJun <wenjun01@baidu.com>
     * @param int $level
     *
     * @return bool
     */
    public function setGzip($level = 1)
    {
        $level = intval($level);
        if ($level < 1 || $level > 9) {
            return false;
        }

        $responseObj = Yaf_Registry::get('SWOOLE_HTTP_RESPONSE');
        return $responseObj->gzip($level);
    }
}