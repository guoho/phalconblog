<?php

class BlogHistory extends \Phalcon\Mvc\Model
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
     * 标题 
     * 
     * @var string
     * @Column(type="string",nullable="true"  field="title")
     */
    public $title;

    /**
     * 博客内容
     * 
     * @var string
     * @Column(type="string",nullable="false"  field="content")
     */
    public $content;

    /**
     * 创建时间
     * 
     * @var string
     * @Column(type="string",nullable="true"  field="create_time")
     */
    public $createTime;

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BlogHistory[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BlogHistory
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
            'title' => 'title',
            'content' => 'content',
            'create_time' => 'createTime'
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'blog_history';
    }

}
