<?php
/**
 * @Author: WenJun
 * @Date:   15/10/26 11:20
 * @Email:  wenjun01@baidu.com
 * @File:   Index.php
 * @Desc:   ...
 */
class IndexController extends Base_Controllers
{
    /**
     * Method  indexAction
     * @desc   ......
     *
     * @author WenJun <wenjun01@baidu.com>
     *
     * @return void
     */
    public function indexAction()
    {
        var_dump($this->getServer());
        var_dump($this->getHeader());
        var_dump($this->getQuery());
        var_dump($this->getPost());
        var_dump($this->getCookie());
        var_dump($this->getFiles());
        var_dump($this->getRawContent());

        $this->getView()->display('index.phtml');
    }
}