<?php
/**
 * @Author: WenJun
 * @Date:   15/10/26 11:16
 * @Email:  wenjun01@baidu.com
 * @File:   Error.php
 * @Desc:   ...
 */
class ErrorController extends Base_Controllers
{
    /**
     * Method  errorAction
     * @desc   ......
     *
     * @author WenJun <wenjun01@baidu.com>
     * @param null $exception
     *
     * @return void
     */
    public function errorAction($exception = null)
    {
        header("HTTP/1.1 404 Not Found");
        //3秒后跳去首页
        header("refresh:3;url=/");

        $this->getView()->display('error.phtml');
    }
}