<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model;
class BlogController extends ControllerBase {
	
	/**
	 * Index action
	 */
	public function indexAction($numberPage = 1, $categoryId = 0, $keywords = '') {
		$blog = Blog::find ();
		$paginator = new Paginator ( array (
				"data" => $blog,
				"limit" => 10,
				"page" => $numberPage 
		) );
		
		$this->view->page = $paginator->getPaginate ();
	}
	
	/**
	 * Edits a blog
	 *
	 * @param string $id        	
	 */
	public function editAction($postId = 0) {
		$blog = $postId > 0 ? Blog::findFirst ( $postId ) : new Blog ();
		if ($blog === false) {
			$this->flash->error ( "博客文章不存在" );
			return $this->dispatcher->forward ( array (
					"controller" => "blog",
					"action" => "index" 
			) );
		}
		$this->view->blog = $blog->toArray ();
		
		// 博客关联分类
		$categoryIds = [ ];
		$cateogryRelations = $blog->getCategoryRelation ();
		$cateogryRelations = $cateogryRelations->toArray ();
		if ($cateogryRelations) {
			foreach ( $cateogryRelations as $relation ) {
				$categoryIds [] = $relation ['categoryId'];
			}
		}
		$this->view->categoryIds = $categoryIds;
		
		// 所有分类
		$categoryList = Category::find ();
		if (! empty ( $categoryList )) {
			$tree = new Tree ( $categoryList->toArray () );
			$categoryList = $tree->getTree ();
		}
		$this->view->categoryList = $categoryList;
		
		// 博客关联的标签
		$tags = $blog->getTagRelation ();
		$tagNames = [];
		if($tags->toArray()){
			foreach ($tags as $tag){
				$tagNames[] = $tag->tag->tagName;
			}
		}	
		$this->view->tags =  implode(",", $tagNames);
	}
	
	/**
	 * Creates a new blog
	 */
	public function createAction($title = '', $content = '', $authorId = 0, array $categoryIds = NULL, array $tags = NULL) {
		$title = $this->request->getPost ( 'title', 'string' );
		$content = $this->request->getPost ( 'content' );
		
		$blog = new Blog ();
		$blog->title = $title;
		$blog->authorId = $authorId;
		$blog->content = $content;
		
		if (! $blog->save ()) {
			foreach ( $blog->getMessages () as $message ) {
				$this->flash->error ( $message );
			}
			return $this->dispatcher->forward ( array (
					"controller" => "blog",
					"action" => "edit" 
			) );
		}
		
		$this->flash->success ( "文章创建成功" );
		
		return $this->dispatcher->forward ( array (
				"controller" => "blog",
				"action" => "index" 
		) );
	}
	
	/**
	 * Saves a blog edited
	 */
	public function updateAction($postId = 0, $title = '', $content = '', $authorId = 0, array $categoryIds = NULL, array $tags = NULL) {
		$postId = $this->request->getPost ( 'postId', 'int' );
		$categoryIds = $this->request->getPost ( 'categoryIds' );
		$tags = $this->request->getPost ( 'tags', 'string' );
		
		if (! $postId || empty ( $categoryIds )) {
			$this->flash->error ( "请选择分类" );
			
			return $this->dispatcher->forward ( array (
					"controller" => "blog",
					"action" => "index" 
			) );
		}
		$blog = Blog::findFirst ( $postId );
		if ($blog === false) {
			$this->flash->error ( "文章不存在 " );
			return $this->dispatcher->forward ( array (
					"controller" => "blog",
					"action" => "index" 
			) );
		}
		$title = $this->request->getPost ( 'title', 'string' );
		$content = $this->request->getPost ( 'content' );
		
		$blog->title = $title;
		$blog->authorId = $authorId;
		$blog->content = $content;
		if ($blog->save () === false) {
			foreach ( $blog->getMessages () as $message ) {
				$this->flash->error ( $message );
			}
			return $this->dispatcher->forward ( array (
					"controller" => "blog",
					"action" => "edit",
					"params" => array (
							$blog->id 
					) 
			) );
		}
		/**
		 * 分类处理
		 */
		// 原关联分类，未选中的分类删除掉
		$categoryRelations = $blog->getCategoryRelation ();
		//已存在的分类ID
		$exists = [];
		if ($categoryRelations->toArray ()) {
			foreach ( $categoryRelations as $relation ) {
				if (! in_array ( $relation->categoryId, $categoryIds ))
					$relation->delete ();
				else 
					$exists[] = $relation->categoryId;
			}
		}
		// 关联新的分类
		foreach ( $categoryIds as $categoryId ) {
			//标签之前已添加，不必再添加
			if($exists && in_array($categoryId, $exists))
				continue;
			
			$categoryRelation = new CategoryRelation ();
			$categoryRelation->categoryId = $categoryId;
			$categoryRelation->postId = $postId;
			$categoryRelation->save ();
		}
		
		/**
		 * 标签处理
		 */
		// 原关联标签，未选的标签删除掉
		$tags = !empty($tags) ? explode( ",", $tags ) : NULL;
		$tagRelations = $blog->getTagRelation ();
		//已存在的标签
		$exists = [];
		if ($tagRelations->toArray () && $tags) {
			foreach ( $tagRelations as $relation ) {
				$tag = $relation->getTag ();
				if (! in_array ( $tag->tagName, $tags ))
					$relation->delete ();
				else 
					$exists[] = $tag->tagName;
			}
		}
		// 关联新的标签
		if($tags){
			foreach ( $tags as $tagName ) {
				//标签之前已添加，不必再添加
				if($exists && in_array($tagName, $exists))
					continue;
				$tag = $this->getTagByName($tagName);
				$tagRelation = new TagRelation();
				$tagRelation->tagId = $tag['tagId'];
				$tagRelation->postId = $postId;
				$tagRelation->save ();
			}
		}

		
		// 保存记录版本
		if ($blog->hasChanged ()) {
			$blogHistory = new BlogHistory ();
			$blogHistory->assign ( $blog->getSnapshotData () );
			$blogHistory->save ();
		}
		$this->flash->success ( "文章修改成功" );
		return $this->dispatcher->forward ( array (
				"controller" => "blog",
				"action" => "index" 
		) );
	}
	
	
	private function getTagByName($tagName){
		$tag = Tag::findFirst ( [
				'conditions' => 'tagName=:tagName:',
				'bind' => [
						'tagName' => $tagName
				]
		] );
		if($tag===false){
			$tag = new Tag();
			$tag->tagName = $tagName;
			$tag->save();
		}
		return $tag->toArray();
	}
	/**
	 * Deletes a blog
	 *
	 * @param string $id        	
	 */
	public function deleteAction($postId) {
		$blog = Blog::findFirstByid ( $postId );
		if (! $blog) {
			$this->flash->error ( "blog was not found" );
			
			return $this->dispatcher->forward ( array (
					"controller" => "blog",
					"action" => "index" 
			) );
		}
		
		if (! $blog->delete ()) {
			
			foreach ( $blog->getMessages () as $message ) {
				$this->flash->error ( $message );
			}
			
			return $this->dispatcher->forward ( array (
					"controller" => "blog",
					"action" => "search" 
			) );
		}
		
		$this->flash->success ( "blog was deleted successfully" );
		
		return $this->dispatcher->forward ( array (
				"controller" => "blog",
				"action" => "index" 
		) );
	}
}
