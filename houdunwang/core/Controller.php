<?php
namespace houdunwang\core;
//  创建一个conroller类
class controller{
    //  定义一个静态的属性   当添加内容成功后需要跳转的地址
    private $url="window.history.back()";
    // 定义模板路径这个属性  当方法需要载入的模板时候使用
    private  $template;
    //  定义一个属性  当成功或者失败的时候  可以把它当做参数传给error和success方法中使用
    private $msg;
    //   创建一个setRedirect方法  它的子类Entry方法中使用中  因为子类没有 所以就去父类来寻找
    //  这个方法时用来实现跳转的
    public function setRedirect($url){
        //  跳转后的地址
        $this->url= "location.href='{$url}'";
        //  并且把它返回.
        return $this;
    }
    //   成功的时候  调用success方法
    public function success($msg){
        //   吧$msg 的值给$this->msg
        $this->msg = $msg;
        //  成功后进入view里面的sucess.php文件
        $this->template = './view/success.php';
        //  然后把它返回
        return $this;
    }
    // 当失败的时候
    public function error($msg){
        $this->msg = $msg;
        //  当失败的时候需要进入的文件
        $this->template = './view/error.php';
        //   同样  执行后也需要返回  返回到当前
        return $this;
    }
    //   利用__toString方法把获得的对象转化成字符串  当echo对象的时候触发此函数
    public function __toString() {
        //  载入当前的模板
        include $this->template;
        // __tostring 是把对象转化成字符串   所以返回一个空字符串
        return '';
    }
}