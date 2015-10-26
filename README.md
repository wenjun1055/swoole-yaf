# **Swoole-Yaf** #
---
将Yaf框架和Swoole扩展提供的HttpServer结合在一起，server和框架高度结合形成超高性能的组合

## **修改说明** ##
---
Yaf经过二次开发得以与Swoole完美融合,具体修改和添加包括两部分

1. Yaf源码修改：请查看patch文件夹中的patch文件
2. 二次封装
		
		application/library/HttpServer.php
		application/Base/Controllers.php



##**作者**##
---

花生[wenjun1055@gmail.com]

##**使用**##
---

打开终端
cd swoole-yaf
php server.php

打开浏览器，输入http://127.0.0.1:8080


##**版本要求**##
---

[Swoole](https://github.com/swoole/swoole-src) 1.7.8+

[Yaf](https://github.com/laruence/yaf) 任意stable版本


##**注意**##
---

各位Star、Fork的时候，麻烦Follow一下，谢谢 [https://github.com/wenjun1055](https://github.com/wenjun1055)