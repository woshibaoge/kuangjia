<?php
namespace app\home\controller;
use houdunwang\model\Model;
use houdunwang\core\Controller;
use houdunwang\view\View;
use system\model\Arc;
use system\model\Tag;
use Gregwar\Captcha\CaptchaBuilder;
//   因为在boot文件里面   new的是entry这个类  但是这个类不存在  所以需要创建这个类(这个类需要继承他父类)  然后再这个类里面定义一个index方法   然后把首页的模板引入
    class Entry extends Controller{
        //  调用这个类的index方法  然后载入首页
        public function index(){
            //  载入首页的内容需要从数据库遍历出来  并且执行有结果集的操作   但是Model类不存在  里面的q方法也不存在  此时需要去model文件里面创建一个Model.php文件
            //  并且执行q方法（有结果集的操作）
//            $data=Model::q("SELECT * FROM  arc");

            //  执行q方法后获得的数据内容
//            $tag=Model::q("SELECT * FROM tag");
            //  此时需要通过View类调用with方法和make方法 然后把结果返出来
            //   但是这会没有view类  和with方法和make方法  需要去核心文件houdunwang中的view 中创建
//            return View::with(compact('data','tag'))->make();

            //   获取文章的数据 因为arc这个类不存在 所以需要去seytem文件下面去找arc类  并且找到里面的get方法
            $arcData=Arc::get();
            //   添加内容
            if(IS_POST){
                //  先判断验证码是否正确
                if(strtolower($_POST['captcha']) != strtolower($_SESSION['phrase'])){
                    //   如果不正确的话就返回验证码错误
                    return $this->error('验证码错误');
                }
                //  如果正确的话 那么就调用save方法 然后并且提示添加成功 此时需要切arn中找save方法
                Arc::save($_POST);
                //返回并且提示成功然后跳到首页
                return $this->success('添加成功')->setRedirect('index.php');
            }
            return View::make()->with(compact('arcData'));
        }

        //  删除    创建一个remove方法   用来删除
        public function remove(){
            //  删除数据库中当前id的内容  但是destory方法不存在  需要去arc类中找
            Arc::where("aid={$_GET['aid']}")->destory();
            //  删除成功后返回
            return $this->success('删除成功')->setRedirect('index.php');
        }

        //   修改
        public function update(){
            $aid = $_GET['aid'];
            if(IS_POST){
                Arc::where("aid={$aid}")->update($_POST);
                return $this->success('修改成功')->setRedirect('index.php');
            }
            $oldData = Arc::find($aid);
            return View::make()->with(compact('oldData'));
        }

        public function captcha(){
            header('Content-type: image/jpeg');
            $builder = new CaptchaBuilder();
            $builder->build();
            $builder->output();
            //把值存入到session
            $_SESSION['phrase'] = $builder->getPhrase();
        }

    }