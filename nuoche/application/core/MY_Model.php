<?php

/**
 * 自定义模型类
 * @date	2016-06-21
 * @author	huangshiwei
 */

class MY_Model extends CI_Model {

	/**
	 * 构造函数
	 * @param string $dbgroup 配置文件的数据库组名
	 */
	public $table;
	public $where;
	public $limit;
	public $order;
	public $field;

	public function __construct($dbgroup = 'default')
	{
		parent::__construct();
		$this->db = $this->load->database($dbgroup, TRUE);
	}

	/**
	 * 插入
	 * @param type $table
	 * @param type $data
	 * @return type
	 */
	public function insert($table, $data) {
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	/**
	 * 更新
	 * @param type $table
	 * @param type $set
	 * @param type $where
	 * @return type
	 */
	public function update($table, $set, $where) {
		$this->db->update($table, $set, $where);
		return $this->db->affected_rows();
	}

	/**
	 * 删除
	 * @param type $table
	 * @param type $where
	 * @param type $limit
	 * @return type
	 */
	public function del($table, $where, $limit) {
		$this->db->delete($table, $where, $limit);
		return $this->db->affected_rows();
	}

	/**
	 * 执行sql，返回结果集
	 * @param string $sql
	 * @return mixed 成功返回资源型，失败返回false
	 */
	public function query($sql) {
		return $this->db->query($sql);
	}

	/**
	 * 查询1条数据，返回数组
	 * @param string $sql
	 * @return array 成功返回一位数组，失败返回空数组
	 */
	public function query_one($sql) {
		return $this->db->query($sql)->row_array();
	}

	/**
	 * 查询list data
	 * @param string $sql
	 * @return array 成功返回二维数组，失败返回空数组
	 */
	public function query_list($sql) {
		$result = array();
		$query = $this->db->query($sql);
		if($query){
			foreach($query->result_array() as $row) {
				$result[] = $row;
			}
		}
		return $result;
	}

	public function query_count($sql){
		$query = $this->db->query($sql);
		return $query->num_rows();
	}


	/**
	 * transaction
	 */
	public function begin(){
		$this->db->trans_begin();
	}
	public function commit(){
		$this->db->trans_commit();
	}
	public function collback(){
		$this->db->trans_rollback();
	}

	// /**
	//  * 查询条件
	//  */
	// public function table($table=''){
	// 	$this->table = $table ;
	// 	return $this;
	// }
	// public function byId($id=0){
	// 	$this->where = ['id'=>$id] ;
	// 	return $this;
	// }
	// public function setWhere($where=array()){
	// 	if(is_array($where)){
	// 		$this->where = array_merge( $this->where, $where) ;
	// 	}else{
	// 		$this->where = $where ;
	// 	}
	// 	return $this;
	// }
	// public function setOrder($order=''){
	// 	$this->order = $order ;
	// 	return $this;
	// }
	// public function setLimit($limit=''){
	// 	$this->limit = $limit ;
	// 	return $this;
	// }
	// public function setField($field=''){
	// 	$this->field = $field ;
	// 	return $this;
	// }

	// /**
	//  * 变更操作
	//  */
	// public function add($data=array()){
	// 	self::insert($this->table, $data);
	// 	return $this->db->insert_id();
	// }
	// public function edit($set=array(),$id=0){
	// 	self::byId($id);
	// 	self::update($this->table, $set, $this->where);
	// 	return $this->db->affected_rows();
	// }
	// public function editN( $set=array(),$where=array() ){
	// 	($where) 	&& 		self::setWhere($where);
	// 	self::update($this->table, $set, $this->where);
	// 	return $this->db->affected_rows();
	// }
	// public function delOne( $id=0) {
	// 	self::byId($id);
	// 	return $this->db->delete($this->table, $this->where);
	// }
	// public function delN( $where=array()) {
	// 	self::setWhere($where);
	// 	return $this->db->delete($this->table, $this->where);
	// }


	// /**
	//  * 查询数据
	//  */
	// public function getOneById($id=0,$field='*'  ) {
	// 	($field) 	&& 		self::setField($field);
	// 	self::byId($id);
	// 	return $this->db->select($this->field)->where($this->where)->get($this->table)->first_row('array');
	// }

	// public function getOne($where=array(),$field='*',$order='id desc' ) {
	// 	($where) 	&& 		self::setWhere($where);
	// 	($field) 	&& 		self::setField($field);
	// 	($order) 	&& 		self::setOrder($order);
	// 	return $this->db->where($this->where)->order_by($this->order)->get($this->table)->first_row('array');
	// }
	// public function findOrCreate( $where=array()) {
	// 	($where) 	&& 		self::setWhere($where);

	// 	$res = self::getOne($where);
	// 	if( $res ){
	// 		return $res;
	// 	}
	// 	self::insert($this->table, $where);
	// 	$add_res = $this->db->insert_id();
	// 	return self::getOneById($add_res);
	// }
	// public function getList( $where='', $order='id desc', $limit='') {
	// 	($where)       &&     self::setWhere($where);
	// 	($limit)       &&     self::setLimit($limit);
	// 	($order)       &&     self::setOrder($order);
	// 	return $this->db->where($this->where)->order_by($this->order)->limit($this->limit)->get($this->table)->result_array('array');
	// }


}
