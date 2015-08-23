<?php

class TagRelation extends \Phalcon\Mvc\Model
{

    /**
     * 自增长ID
     * 
     * @var integer
     * @Column(type="integer",nullable="true"  field="id")
     */
    public $id;

    /**
     * 文章ID
     * 
     * @var integer
     * @Column(type="integer",nullable="true"  field="post_id")
     */
    public $postId;

    /**
     * 分类ID
     * 
     * @var integer
     * @Column(type="integer",nullable="true"  field="tag_id")
     */
    public $tagId;

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TagRelation[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TagRelation
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
            'id' => 'id',
            'post_id' => 'postId',
            'tag_id' => 'tagId'
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tag_relation';
    }

}
