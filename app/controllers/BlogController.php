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
	public function editAction($id=0) {
		$blog = $id>0 ? Blog::findFirst( $id ) : new Blog();
		if ($blog===false) {
			$this->flash->error ( "blog does not exist " . $id );
			
			return $this->dispatcher->forward ( array (
					"controller" => "blog",
					"action" => "index" 
			) );
		}
		
		$this->view->blog = $blog = $blog->toArray();
	}
	
	/**
	 * Creates a new blog
	 */
	public function createAction($title='', $content='', $authorId=0, array $categoryIds=NULL, array $tags=NULL) {		
		$title = $this->request->getPost('title', 'string');
		$content = $this->request->getPost('content');
		
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
	public function updateAction($postId=0, $title='', $content='', $authorId=0, array $categoryIds=NULL, array $tags=NULL) {
		$postId = $this->request->getPost('postId', 'int');
		$blog = Blog::findFirst ( $postId );
	
		if (! $blog) {
			$this->flash->error ( "文章不存在 " );
			
			return $this->dispatcher->forward ( array (
					"controller" => "blog",
					"action" => "index" 
			) );
		}
		$title = $this->request->getPost('title', 'string');
		$content = $this->request->getPost('content');
		
		$blog->title = $title;
		$blog->authorId = $authorId;
		$blog->content = $content;	

		if (! $blog->save ()) {			
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
		

		if($blog->hasChanged()){
			$blogHistory = new BlogHistory();
			$blogHistory->assign($blog->getSnapshotData());
			$blogHistory->save();
		}
		$this->flash->success ( "文章修改成功" );
		
		return $this->dispatcher->forward ( array (
				"controller" => "blog",
				"action" => "index" 
		) );
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
