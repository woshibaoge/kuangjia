<?php
namespace houdunwang\model;
use PDO;
use PDOException;
//   因为触发自动执行不存在的静态方法而执行newBase这个类  所以就去model这个文件下面创键  这个里面需要q方法
class Base{
//  把$pdo  定义为静态属性  并且给它个默认的值 为空
    private static $pdo=null;

    //  保存表名
    private $table;
    //  保存where
    private $where;

    // 利用__construct 方法 自动载入q方法
    public function __construct($table)
    {
        // 调用connect这个方法 此时需要在这个方法的外部定义此方法  然后再添加q方法
        $this->connect();
        //  调用table方法
        $this->table = $table;
    }
    //  触发__constrct 后执行的方法
    public function connect(){
        // 此时需要定义q方法   但是 在执行q方法之前需要先利用PDO方法链接数据库  然后才可以执行q方法（有结果集的操作）
        //  此时会容易出现一个问题就是$pdo  为空的时候可以正常连接数据库 但是每次更新数据的时候为了避免再次连接数据库而丢失数据
        //  此时需要给它做条件判断  如果他为空  那么就链接   不为空就不链接  那么需要给它定义个属性
        if(is_null(self::$pdo)){
            //  此时需要连接数据库
            try{
                //  使用pdo方法连接数据库
                $dsn = 'mysql:host='.c('database.db_host').';dbname=' . c('database.db_name');
                $pdo = new PDO( $dsn, c('database.db_user'), c('database.db_password') );
                //  设置错误属性
                $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                //  设置字符编码
                $pdo->exec( "SET NAMES " . c('database.db_charset') );
                //把PDO对象放入到静态属性中
                self::$pdo = $pdo;
            }catch(PDOException $e){
                //捕获PDO异常错误 $e 是异常对象
                exit($e->getMessage());
            }

        }
    }
    //   链接完数据库后  再执行q方法之前需要先获得数据库中的内容才可以  所以此时需要利用get方法来获得
    public function get(){
        //  利用sql语句来找到表单中全部数据
        $sql="SELECT * FROM {$this->table}";
        //  把查询到的结果返回来   用$result  接收它
        $result=self::$pdo->query( $sql );
        //   获得关联数组
        $data =$result->fetchAll( PDO::FETCH_ASSOC );
        //  然后再返回数据库
        return $data;
    }
//   查询单个数据  定义一个find方法
    public function find($id){
        //  获得当前的主键名
        $priKey = $this->getPriKey();
        //  找到当前的主键对应的id
        $sql = "SELECT * FROM {$this->table} WHERE {$priKey}={$id}";
        //  执行没有结果集的操作
        $data = $this->q($sql);
        //   把查询到的结果返回
        return current($data);
    }

    //   定义一个save方法  用来
    public function save($post){
        //查询当前表信息
        $tableInfo = $this->q("DESC {$this->table}");
        //  定义一个字段的空数组变量
        $tableFields = [];
        //获取当前表的字段 [title,click]
        foreach ($tableInfo as $info){
            //  把当前字段的内容赋值给表格中的字段
            $tableFields[] = $info['Field'];
        }
        //循环post提交过来的数据
        //Array
//		(
//			[title] => 标题,
//			[click] => 100,
//			[captcha] => abc,
//		)
        $filterData = [];
        foreach ($post as $f => $v){
            //如果属于当前表的字段，那么保留，否则就过滤
            if(in_array($f,$tableFields)){
                $filterData[$f] = $v;
            }
        }
//      Array
//		  (
//			[title] => 标题,
//			[click] => 100,
//		)

        //字段
        $field = array_keys($filterData);
        //  吧数组格式的字段转化为字符串
        $field = implode(',',$field);
        //值
        $values = array_values($filterData);
        //  吧字段对应的值转化成字符串
        $values = '"' . implode('","',$values)  . '"';

        $sql = "INSERT INTO {$this->table} ({$field}) VALUES ({$values})";
        return $this->e($sql);
    }
    //   修改  功能
    //  创建一个update方法   用来操作修改的功能
    public function update($data){
        //  如果没有where条件 的话会提示你  因为会有全部修改
        if(!$this->where){
            ///  提示需要有where条件
            exit('update必须有where条件');
        }
        //Array
//		(
//			[title] => 标题,
//			[click] => 100,
//		)
        //  定义一个空字符串的变量   用来接收遍历后的value值
        $set = '';
        //   遍历数据库中键
        foreach ( $data as $field => $value ) {
            //  吧遍历后的结果赋值给之前定义的空字符串的变量
            $set .= "{$field}='{$value}',";
        }
        //   去除末尾的  点
        $set = rtrim($set,',');
        /// 吧修改后的内容添加到数据库
        $sql = "UPDATE {$this->table} SET {$set} WHERE {$this->where}";
        //  返回数据库
        return $this->e($sql);
    }

    //  where 条件  通过调用where方法来获得where条件
    public function where($where){
        //  把传进来的where条件赋值给当前的where
        $this->where = $where;
        //  返回当前对象
        return $this;
    }

    //  删除数据
    public function destory(){
        //  判断是否有where条件没有条件的话会把全部删掉
        if(!$this->where){
            exit('delete必须有where条件');
        }
        //  有where条件的话就删除数据库中的当前的内容
        $sql = "DELETE FROM {$this->table} WHERE {$this->where}";
        //  返回删除后的数据库
        return $this->e($sql);
    }
      //  获得主键
    private function getPriKey(){
        //  查看数据库中的表的结构
        $sql = "DESC {$this->table}";
        //  获得当前的数据库内容
        $data = $this->q($sql);
        //定义一个空字符的主键
        $primaryKey = '';
        //  遍历数据库中的键值
        foreach ($data as $v){
            //  如过键值里面的键名对应的键值为PRI   那么就把它赋值给主键$primaryKey
            if($v['Key'] == 'PRI'){
                $primaryKey = $v['Field'];
                //  跳出循环
                break;
            }
        }
        //   吧循环出来的主键返回
        return $primaryKey;
    }
    //  获得内容后此时需要使用q方法 来执行有结果集的操作
    public function q($sql){
        try {
            //   执行有结果集的操作  并且把它的结果值给变量$result
            $result = self::$pdo->query( $sql );
            //  获取数据库的全部内容并且把它给返回回来
            return $result->fetchAll( PDO::FETCH_ASSOC );
        } catch ( PDOException $e ) {
            //捕获PDO异常错误 $e 是异常对象
            exit( "SQL错误：" . $e->getMessage() );
        }
    }
    //   当点击添加的时候需要执行没有结果集的操作
    public  function e($sql){
        try {
            //  执行没有结果集的操作 并且把它返回给变量$afRows
            $afRows = self::$pdo->exec( $sql );
            return $afRows;
        } catch ( PDOException $e ) {
            //捕获PDO异常错误 $e 是异常对象
            exit( "SQL错误：" . $e->getMessage() );
        }
    }

    //   注意 此时 执行完这些代码后需要  是继续走Entry里面代码
}
