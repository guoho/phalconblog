<?php
class Tag extends \Phalcon\Mvc\Model {
	
	/**
	 * 标签ID
	 *
	 * @var integer @Column(type="integer",nullable="true" field="tag_id")
	 */
	public $tagId;
	
	/**
	 * 标签
	 *
	 * @var string @Column(type="string",nullable="true" field="tag_name")
	 */
	public $tagName;
	
	/**
	 * 文章量
	 *
	 * @var integer @Column(type="integer",nullable="true" field="post_count")
	 */
	public $postCount;
	
	/**
	 * Independent Column Mapping.
	 * Keys are the real names in the table and the values their names in the application
	 *
	 * @return array
	 */
	public function columnMap() {
		return array (
				'tag_id' => 'tagId',
				'tag_name' => 'tagName',
				'post_count' => 'postCount' 
		);
	}
	
	/**
	 * Returns table name mapped in the model.
	 *
	 * @return string
	 */
	public function getSource() {
		return 'tag';
	}
	
	public function initialize() {
		// 保存快照数据
		$this->keepSnapshots ( true );
		// 只更新修改的数据
		$this->useDynamicUpdate ( true );
		// 更多分类
		$this->hasMany ( 'categoryId', 'TagRelation', 'categoryId' );
	}
}
