<?php

class CategoryRelation extends \Phalcon\Mvc\Model {
	
	/**
	 * 自增长ID
	 *
	 * @var integer @Column(type="integer",nullable="true" field="id")
	 */
	public $id;
	
	/**
	 * 文章ID
	 *
	 * @var integer @Column(type="integer",nullable="true" field="post_id")
	 */
	public $postId;
	
	/**
	 * 分类ID
	 *
	 * @var integer @Column(type="integer",nullable="true" field="category_id")
	 */
	public $categoryId;
	
	
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
				'caterogry_id' => 'caterogryId' 
		);
	}
	
	/**
	 * Returns table name mapped in the model.
	 *
	 * @return string
	 */
	public function getSource() {
		return 'category_relation';
	}
}
