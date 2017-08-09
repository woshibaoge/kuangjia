<?php
namespace houdunwang\model;
//  显示首页需要执行数据库有结果集的q方法  所以此时需要创建Model 类
  class Model{
      //  因为q方法是静态方法   所以 使用下面这个函数    当Model类中不存在q方法时候   就自动触发次函数  执行下面的代码
      public static function __callStatic($name, $arguments){
          //   先获得arc的文件路径   获得后的结果是system\model\Arc
          $className=get_called_class();
          //  newBase的时候给它传给参数  但是此时传的参数的值是arc
          $table=strtolower(ltrim(strrchr($className,'\\'),'\\'));
          //strrchr字符串截取 变成 \Arc
          //ltrim 去除左边的\ 变成 Arc
          //strtolower 变成 arc
          //   此时又newBase这个类   此时需要去model 文件夹里面创建Base文件里面创建个Base这个类
          return call_user_func_array([new Base($table),$name], $arguments);
      }
  }