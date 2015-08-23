<?php

class Tag extends \Phalcon\Mvc\Model
{

    /**
     * 标签ID
     * 
     * @var integer
     * @Column(type="integer",nullable="true"  field="tag_id")
     */
    public $tagId;

    /**
     * 标签 
     * 
     * @var string
     * @Column(type="string",nullable="true"  field="tag_name")
     */
    public $tagName;

    /**
     * 文章量
     * 
     * @var integer
     * @Column(type="integer",nullable="true"  field="post_count")
     */
    public $postCount;

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tag[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tag
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
    public function getSource()
    {
        return 'tag';
    }

}
