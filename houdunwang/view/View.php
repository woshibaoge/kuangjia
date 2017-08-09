<?php
namespace houdunwang\view;
//  此时创建一个View类 然后需要调用with方法 然后然后对象 一直返回到起点并输出
class View{
    //   同Base和model的性质一样
    public static function __callStatic( $name, $arguments ) {
        //newBase 这个类 通过base这个类找到里面的make和with方法
        return call_user_func_array([new Base(),$name],$arguments);
    }
}