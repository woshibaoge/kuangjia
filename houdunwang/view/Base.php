<?php
namespace houdunwang\view;
//   也是先创建base类  然后保存需要的静态属性   然后定义with方法和make方法
class Base{
    //   定义需要的静态属性
    private $data = [];
    //模板路径
    private $template;
    //  分配变量
    //   定义with方法  把获得数据库给当前的数据库 并且返回
    public function with($data){
        $this->data = $data;
        return $this;
    }
    //  制作模板
    public function make(){
        $this->template = '../app/' . APP . '/view/' . CONTROLLER . '/' . ACTION . '.php';
        //1.返回当前对象，
        //(1)返给\houdunwang\view\View里面的__callStatic
        //(2)View里面的__callStatic再返回给\app\home\controller\Entry里面的index方法(View::make())
        //(3)Entry里面的index方法又返回给\houdunwang\core\Boot里面的appRun方法，在appRun方法用了echo 输出这个对象
        //2.为了触发__toString
        return $this;
    }
    //  此时需要定义__toString方法   用来载入模板
    public function __toString() {
        //把键名变为变量名，键值变为变量值 相当于 $data = ['title'=>'我是文章标题'];
        extract($this->data);
        //载入模板
        include $this->template;
        //这个方法必须返回字符串
        return '';
    }

}