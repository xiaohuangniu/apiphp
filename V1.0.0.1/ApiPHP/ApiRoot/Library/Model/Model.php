<?php
/*
 +--------------------------------------------------------------------------------------------------
 + Title        : Api框架 Model模型类
 + Author       : 极资源首席工程师 - 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-06-27 13:31
 + Last-time    : 2017-06-27 13:31 + 小黄牛
 + Desc         : 用于简单的操作数据库信息 - 要做预过滤处理的同学,可以自行修改
 +--------------------------------------------------------------------------------------------------
*/

class Model{
	protected $dbMs;          //数据库类型
	protected $dbHost;        //数据库主机名
    protected $dbName;        //数据库名称
	protected $dbCharset;     //数据库编码
	protected $dbPort;        //数据库端口
	protected $dbUser;        //数据库连接用户名
	protected $dbPwd;         //对应的密码
    protected $tablePrefix;   //数据表前缀
    protected $tableName;     //数据表名（不包含表前缀)
	protected $trueTableName; //数据表名(已包含表前缀)
	protected $dbSql;         //记录需要执行的SQL语句
	protected $linkOperation = array(
							   		'field' => '*',
									'where' => '',
									'order' => '',
									'limit' => '',
									'from'  => '',
							   ); //连贯操作参数
	private $instance;        //数据库PDO连接实例

	/*
	* $tableName : 表名，不带表前缀，为空时只能执行源生sql操作
	*/
	public function __construct($tableName=''){
		$people = db('DB_PUBLIC');
		
		# 公共数据库
		if (in_array(strtolower($tableName), $people)) {
			$this->dbMs         = db('DB_TYPE');
			$this->dbHost       = db('DB_HOST');
			$this->dbName       = db('DB_NAME');
			$this->dbPort       = db('DB_PORT');
			$this->dbCharset    = db('DB_CHARSET');
			$this->dbUser       = db('DB_USER');
			$this->dbPwd        = db('DB_PWD');
			$this->tablePrefix  = db('DB_PREFIX');
		# 版本数据库
		}else{
			$db = db(MODULE);
			$db = $db[API];
			$this->dbMs         = $db['DB_TYPE'];
			$this->dbHost       = $db['DB_HOST'];
			$this->dbName       = $db['DB_NAME'];
			$this->dbPort       = $db['DB_PORT'];
			$this->dbCharset    = $db['DB_CHARSET'];
			$this->dbUser       = $db['DB_USER'];
			$this->dbPwd        = $db['DB_PWD'];
			$this->tablePrefix  = $db['DB_PREFIX'];
		}
		$this->trueTableName= strtolower($this->tablePrefix.$tableName);
		$this->tableName    = $tableName;
		$this->dbPdo();//初始化PDO类	
	}

    //数据库连接
	private function dbPdo(){
		$dbn = $this->dbMs.':host='.$this->dbHost.';port='.$this->dbPort.';dbname='.$this->dbName.';charset='.$this->dbCharset;
		$dbh = new PDO($dbn,$this->dbUser,$this->dbPwd);
		$this->instance = $dbh;
	}

	//拼接Sql
	private function dbSqli(){
		$where = !empty($this->linkOperation['where'])?' where '.$this->linkOperation['where']:'';
		$order = !empty($this->linkOperation['order'])?' order by '.$this->linkOperation['order']:''; 
		$limit = !empty($this->linkOperation['limit'])?' limit '.$this->linkOperation['limit']:''; 
		$sql = 'select '.$this->linkOperation['field'].' from '.$this->trueTableName.$where.$order.$limit;
		$this->dbSql = $sql;
	}

	/*
	* 指定查询结果集中的返回字段
	* $name ： 指定返回字段名 id,name,title
	*/
	public function field($name){
		$this->linkOperation['field'] = $name;
		return $this;
	}

	/*
	* 指定字段排序
	* $name ： 排序字段 id asc
	*/
	public function order($name){
		$this->linkOperation['order'] = $name;
		return $this;
	}

	/*
	* 查询条件
	* name ：查询条件 id=>3 and title='123'
	*/
	public function where($name){
		$this->linkOperation['where'] = $name;
		return $this;
	}

	/*
	* 查询分页
	* $name ： 分段查询 1,10
	*/
	public function limit($name){
		$this->linkOperation['limit'] = $name;
		return $this;
	}

	// 执行SELECT查询获取单条记录，返回一维数组
	public function find(){
		$this->dbSqli();
		$pdo = $this->instance;
		$res = $pdo->query($this->dbSql);
		$res->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
		return $res->fetch();
	}

	// 执行SELECT获取所有记录，返回二维数组
	public function select(){
		$this->dbSqli();
		$pdo = $this->instance;
		$res = $pdo->query($this->dbSql);
		$res->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
		return $res->fetchAll();
	}

	/*
	* 删除语句
	* $name ：删除条件    id=>3
	* $limit: 条数       6
	* 模拟sql：where id=>3 limit 6
	*/
	public function delete($name='',$limit=''){
		$order = !empty($this->linkOperation['order'])?' order by '.$this->linkOperation['order']:''; 
		$limit = !empty($limit)?' limit '.$limit:''; 
		$name = !empty($name) ? ' where '.$name : '';
		$sql = 'delete from '.$this->trueTableName.$name.$order.$limit;
		$pdo = $this->instance;
		return $pdo->exec($sql);
	}

	// 收集post提交过来的数据
	public function create(){
			foreach ($_POST as $key => $value){
				if (is_array($value)) {
					foreach ($value as $K){
						$valueS = S($K);
						if ($valueS !==  $K) {
							$res = '';
						}else{
							$res = $valueS;
						}
						$array[$key][] = $res;
					}
				}else{
					$valueS = S($value);
					if ($valueS !==  $value) {
						$res = '';
					}else{
						$res = $valueS;
					}
					$array[$key] = $res;
				}
			}
		$this->linkOperation['from'] = $array;
		return $this->linkOperation['from'];
	}

	/*
	* 手动赋值更新修改的一维数组
	* $array : 指定该更新修改的一维数组 下标对应字段名 => 元素对应内容  'title'=>'3',不带ID
	*/
	public function data($array){
		$this->linkOperation['from'] = $array;
		return $this;
	}

	/*
	* 更新操作
	* $name ：更新条件     id=3
	* 模拟sql：where id=3
	*/
	public function upd($name){
		$parameter = '';
		foreach ($this->linkOperation['from'] as $key=>$value){
			$parameter .= $key."='".$value."',";
		}
		$new = rtrim($parameter,',');
		$sql = 'update '.$this->trueTableName.' set '.$new.' where '.$name;
		
		$pdo = $this->instance;
		return $pdo->exec($sql);
	}

	// 添加操作
	public function add(){
		$parameter = '';
		$parameter2 = '';
		foreach ($this->linkOperation['from'] as $key=>$value){
			$parameter .= $key.',';
			$parameter2 .= "'$value'".',';
		}
		$new = rtrim($parameter,',');
		$new2 = rtrim($parameter2,',');
		$sql = 'insert into '.$this->trueTableName.'('.$new.') values ('.$new2.')';
		$pdo = $this->instance;
		if($pdo->exec($sql)){
			return $pdo->lastInsertId();
		}else{
			return false;
		}
	}

	// 获得查询总记录数
	public function count(){
		$this->dbSqli();
		$pdo = $this->instance;
		$res = $pdo->query($this->dbSql);
		return count($res->fetchAll(PDO::FETCH_ASSOC));
	}

	/*
	* 执行源生查询语句
	* $sql ： 源生的sql
	*/
	public function query($sql,$type=false){
		$pdo = $this->instance;
		$res = $pdo->query($sql);
		if($type===true){
			return	count($res->fetchAll(PDO::FETCH_ASSOC));
		}else{
			$res->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
			return $res->fetchAll();
		}
	}

	/*
	* 执行源生增删改语句
	* $sql ： 源生的sql
	*/
	public function execute($sql){
		$pdo = $this->instance;
		return $pdo->exec($sql);
	}
}