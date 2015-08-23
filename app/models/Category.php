<?php

class Category extends \Phalcon\Mvc\Model
{

    /**
     * 分类ID
     * 
     * @var integer
     * @Column(type="integer",nullable="true"  field="caterogry_id")
     */
    public $caterogryId;

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
     * @Column(type="string",nullable="true"  field="cat_name")
     */
    public $catName;

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
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Category[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Category
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return array(
            'caterogry_id' => 'caterogryId',
            'parent_id' => 'parentId',
            'cat_name' => 'catName',
            'description' => 'description',
            'post_count' => 'postCount'
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

}
