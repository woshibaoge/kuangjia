<?php
namespace houdunwang\core;
//   创建boot类  然后定义run方法   这个run方法 是用来初始化结构并且执行应用的
class Boot{
    //   创建静态run方法
    public static function run(){
        //  初始化结构  但是init方法不存在  所以需要先定义一个init方法
        self::init();
        //  执行应用 调用apprun方法   但是此方法也没有  需要定义一个APPrun方法
        self::appRun();
    }
    //  创建init方法  用来初始化结构  因为是在类Boot里面  所以使用private声明 （代表私人的属性）
    private static function init(){
        //  开始session属性
        session_id()||session_start();
        //  设置时区
        date_default_timezone_set('PRC');
        //  定义是否是post变量
        define('IS_POST',$_SERVER['REQUEST_METHOD'] == 'POST' ? true : false);
    }
    //   创建一个APPrun方法   用来执行应用
   private static function apprun(){
        //  获得get参数
       $s=isset($_GET['s']) ?strtolower($_GET['s']) : 'home/controller/index';
       //  默认是home/controller/index   所以$s的值是home/controller/index文件
       //  利用explode函数转化为数组   将home/controller/index
       $arr=explode('/',$s);
       //    转化数组后  需要组合类名
       //        p($arr);
       //		Array
//		(
//			[0] => home
//			[1] => controller
//			[2] => index
//		)
       //home是默认应用，有可能为admin后台应用，所以不能写死home   所以此时需要定义一个常量
       define('APP',$arr[0]);
       define('CONTROLLER',$arr[1]);
       define('ACTION',$arr[2]);
       //  现在开始组合路径
       $className="\app\\{$arr[0]}\controller\\".ucfirst($arr[1]);
       //   现在开始初始化对象并且执行里面的 index方法  并且需要输出
       //  new   $className  这个类并且调用里面的index方法  此时进入home下面的entry文件里面的index.php文件  然后调用index方法
       echo call_user_func_array([new $className,$arr[2]],[]);

    }
}