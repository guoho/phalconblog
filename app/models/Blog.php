<?php

class Blog extends \Phalcon\Mvc\Model {
	
	/**
	 * 文章ID
	 *
	 * @var integer @Column(type="integer",nullable="true" field="post_id")
	 */
	public $postId;
	
	/**
	 * 标题
	 *
	 * @var string @Column(type="string",nullable="true" field="title")
	 */
	public $title;
	
	/**
	 * 作者ID
	 *
	 * @var integer @Column(type="integer",nullable="true" field="author_id")
	 */
	public $authorId;
	
	/**
	 * 博客内容
	 *
	 * @var string @Column(type="string",nullable="false" field="content")
	 */
	public $content;
	
	/**
	 * 博客状态
	 *
	 * @var string @Column(type="string",nullable="false" field="status")
	 */
	public $status;
	
	/**
	 * 创建时间
	 *
	 * @var string @Column(type="string",nullable="true" field="create_time")
	 */
	public $createTime;
	
	/**
	 * 修改时间
	 *
	 * @var string @Column(type="string",nullable="true" field="update_time")
	 */
	public $updateTime;

	/**
	 * Returns table name mapped in the model.
	 *
	 * @return string
	 */
	public function getSource() {
		return 'blog';
	}
	
	/**
	 * 判断是否变更的字段列表
	 */
	public function changeColumn() {
		return array ('title','content','status');
	}
	
	/**
	 * Independent Column Mapping.
	 * Keys are the real names in the table and the values their names in the application
	 *
	 * @return array
	 */
	public function columnMap() {
		return array (
				'id' => 'id',
				'post_id' => 'postId',
				'title' => 'title',
				'author_id' => 'authorId',
				'content' => 'content',
				'status' => 'status',
				'create_time' => 'createTime',
				'update_time' => 'updateTime'
		);
	}
	
	public function onConstruct()
	{
		// ...
		$this->createTime = date("Y-m-d H:i:s");
	}
	
	public function beforeUpdate(){
		// ...
		$this->updateTime = date("Y-m-d H:i:s");
	}

	public function initialize(){
		//保存快照数据
		$this->keepSnapshots(true);
		//只更新修改的数据
		$this->useDynamicUpdate(true);
		//更多分类
		$this->hasMany('postId', 'CategoryRelation', 'postId');
		//更多标签
		$this->hasMany('postId', 'TagRelation', 'postId');
		
		//更多标签
		$this->hasMany('postId', 'BlogHistory', 'postId');
	}
	
	/**
	 * 判断记录是否有更改过
	 * @see \Phalcon\Mvc\Model::hasChanged()
	 */
	public function hasChanged($fieldName=null){
		$columns = $this->changeColumn();
		if($fieldName && !in_array($fieldName, $columns)) 
			return true;
		if($fieldName && $this->$fieldName != $this->_snapshot[$fieldName])
			return true;
		
		foreach ($columns as $name){
			if($this->$name != $this->_snapshot[$name])
				return true;
		}
		return false;
		
	}
}
