<?php
//  载入自动载入函数
include '../vendor/autoload.php';
//    单一入口   所以此时需要执行run方法  然后运行起来   此时进入后盾网核心文件下面的core文件下面的Boot文件   然后寻找里面的run方法
\houdunwang\core\Boot::run();
