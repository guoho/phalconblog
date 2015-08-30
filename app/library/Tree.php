<?php
/**
 * 
 * Purpose:   用于对目录或分类进行无限分类操作。
 * Version:   Category class version 1.0
 * Date:      2008年3月15日 
 * Author:    guoho@foxmail.com
 * CopyRight: 2007-2008 Sues Writer
 * Describe:  详细描述
*/
/*
 * //Example
 * $Tree = new Tree($categoryList);
 * $tree = $Tree->getTree();
 */
class Tree {
	/**
	 * 分组目录树
	 * @var array
	 */
	private  $tree = [];
	/**
	 * 原始的分类目录列表
	 * @var array
	 */
	private $category = [];
	
	/**
	 * 用来进行中转处理的
	 * @var array
	 */
	private $_category = [];
	
	/**
	 * 所有子级分类
	 * @var array
	 */
	private $childs = [];
	

	/**
	 * 分类深度等级
	 * @var array
	 */
	private $level = 0;
	
	
	
	/**
	 * 最高深度等级
	 * @var array
	 */
	private $maxLevel = 10;
	
	/**
	 * 无级分类树图标
	 * @var unknown
	 */
	private $icon = array (
			'│',
			' |--',
	);
	/**
	 * 构造函数，初始化类
	 * 
	 * @param
	 *        	array 2维数组，数组要求，其键名与其对应一维数组键id对对应的值相同.例如：
	 *        	array(
	 *        		array('categoryId'=>'1','parentId'=>0,'categoryName'=>'一级栏目一'),
	 *        	   array('categoryId'=>'2','parentId'=>0,'categoryName'=>'一级栏目二'),
	 *        	   array('categoryId'=>'3','parentId'=>1,'categoryName'=>'二级栏目一'),
	 *        		array('categoryId'=>'4','parentId'=>1,'categoryName'=>'二级栏目二'),
	 *        		array('categoryId'=>'5','parentId'=>2,'categoryName'=>'二级栏目三'),
	 *        		array('categoryId'=>'6','parentId'=>3,'categoryName'=>'三级栏目一'),
	 *        		array('categoryId'=>'7','parentId'=>3,'categoryName'=>'三级栏目二')
	 *        	)
	 */
	/**
	 * 构造函数，将分类传递给类
	 * @param array $category
	 */
	function __construct(array $category) {
		$this->_category = $this->category = $category;
	}
	
	/**
	 * 获取子级分类,i 
	 * @param integer $currentId
	 * @return array 子级分类
	 */
	function getChilds($currentId=0) {
		$childs = [];
		foreach ( $this->category as $row ) {
			if ($row ['parentId'] == $currentId) {
				$childs [$row ['categoryId']] = $row;
			}
		}
		return $childs;
	}
	
	/**
	 * 以递归的形式获取所有子级分类ID
	 * @param int $currentId
	 * @return array 所有子级分类
	 */
	function getSubChilds($currentId) {
		//$i = 0;
		if(empty($this->_category)){
			return $this->childs;
		}
		foreach ( $this->_category as $key => $row ) {
			if ($row ['parentId'] == $currentId) {
				$this->childs [$row ['categoryId']] = $key;
				unset($this->_category[$key]);
				//$i ++;
				//array_merge ( array_slice ( $this->_category, 0, $i - 1 ), array_slice ( $this->_category, $i ) );
				$this->getSubChilds ( $row ['categoryId'] );
			}
		}
		return $this->childs;
	}
	
	
	/**
	 * 获取父级分类所在级别的所有分类
	 * @param interger $currentId
	 * @return array 子级分类
	 */
	function getParents($currentId=0) {
		$tmpCategory = $parents = [];
		foreach ($this->category as $row){
			$tmpCategory[$row['categoryId']] = $row;
		}
		
		if (! isset ( $this->category [$currentId] ))
			return false;
		$parentId = $this->category [$currentId] ['parentId'];
		$grandParentId = $this->category [$parentId] ['parentId'];
		foreach ( $this->category as $row) {
			if ($row['parentId'] == $grandParentId)
				$parents [$row['categoryId']] = $row;
		}
		return $parents;
	}
	
	
	
	
	/**
	 * 获取当前分类下的无限子分类树结构
	 * @param number $currentId  当前分类的ID
	 * @param number $exceptId  排除ID及其子分类ID
	 * @param string $prefixString  子级分类树前缀
	 * @param string $parents 
	 */
	function getTree($parentId=0, $exceptId = 0, $prefixString = '', $parents = '') {
		$childs = $this->getChilds ( $parentId );
		if ($childs ) {
			$this->level ++;
			foreach ( $childs as $row) {
				//排除的ID
				if($row['categoryId']== $exceptId) continue;
				
				$row ['layer'] = $prefixString ? $prefixString . $this->icon [1] : '';
				$row ['parents'] = $parents;
				$row ['level'] = $this->level;
				$this->tree [$row['categoryId']] = $row;
				$_parents = $parents ? $parents . ' ' . $row['categoryId'] : $row['categoryId'];				
				$this->getTree ( $row['categoryId'], $exceptId,  $prefixString . '&nbsp;&nbsp;&nbsp;&nbsp;', $_parents );
			}
		}
		return $this->tree;
	}
	
	/**
	 * 析构函数
	 */
	function __destruct() {
		$this->tree = $this->category = $this->_category = $this->childs = $this->icon = null;
	}
}
?>