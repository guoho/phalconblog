<?php

class Category extends \Phalcon\Mvc\Model
{

    /**
     * 分类ID
     * 
     * @var integer
     * @Column(type="integer",nullable="true"  field="category_id")
     */
    public $categoryId;

    /**
     * 父级ID
     * 
     * @var integer
     * @Column(type="integer",nullable="true"  field="parent_id")
     */
    public $parentId;

    /**
     * 分类名 
     * 
     * @var string
     * @Column(type="string",nullable="true"  field="category_name")
     */
    public $categoryName;

    /**
     * 分类描述
     * 
     * @var string
     * @Column(type="string",nullable="false"  field="description")
     */
    public $description;

    /**
     * 分类文章量
     * 
     * @var integer
     * @Column(type="integer",nullable="true"  field="post_count")
     */
    public $postCount;
    
    /**
     * 排序ID
     *
     * @var integer
     * @Column(type="integer",nullable="true"  field="sort_id")
     */
    public $sortId;
    

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return array(
            'category_id' => 'categoryId',
            'parent_id' => 'parentId',
            'category_name' => 'categoryName',
            'description' => 'description',
            'post_count' => 'postCount',
        		'sort_id' => 'sortId'
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'category';
    }

    public function initialize(){
    	//保存快照数据
    	$this->keepSnapshots(true);
    	//只更新修改的数据
    	$this->useDynamicUpdate(true);
    	//更多分类
    	$this->hasMany('categoryId', 'CategoryRelation', 'categoryId');
    }
    
}
