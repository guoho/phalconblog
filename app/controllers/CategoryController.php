<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
class CategoryController extends ControllerBase {
	
	/**
	 * Index action
	 */
	public function indexAction() {
		$category = Category::find ();
		if(!empty($category)){
			$tree = new Tree($category->toArray());
			$category = $tree->getTree();
		}
		
		$this->view->categoryList = $category;
	}
	
	/**
	 * Edits a category
	 *
	 * @param string $caterogry_id        	
	 */
	public function editAction($caterogryId = 0) {
		$category = $caterogryId > 0 ? Category::findFirst ( $caterogryId ) : new Category ();
		if ($category === false) {
			$this->flash->error ( "分类不存在" );
			
			return $this->dispatcher->forward ( array (
					"controller" => "category",
					"action" => "index" 
			) );
		}
		$this->view->category = $category->toArray ();
		$categoryList = Category::find ();
		if(!empty($categoryList)){
			$tree = new Tree($categoryList->toArray());
			$categoryList = $tree->getTree(0, $caterogryId);
		}
		$this->view->categoryList = $categoryList;
		
	}
	
	/**
	 * Creates a new category
	 */
	public function createAction() {		
		$category = new Category ();		
		$category->parentId = $this->request->getPost ( "parentId" );
		$category->categoryName = $this->request->getPost ( "categoryName" );
		$category->description = $this->request->getPost ( "description" );		
		if (! $category->save ()) {
			foreach ( $category->getMessages () as $message ) {
				$this->flash->error ( $message );
			}
			
			return $this->dispatcher->forward ( array (
					"controller" => "category",
					"action" => "edit" 
			) );
		}		
		$this->flash->success ( "分类创建成功" );		
		return $this->dispatcher->forward ( array (
				"controller" => "category",
				"action" => "index" 
		) );
	}
	
	/**
	 * Saves a category edited
	 */
	public function updateAction($categoryId=0) {		
		$categoryId = $this->request->getPost ( "categoryId", 'int' );	
		if($categoryId<1){
			$this->flash->error ( "分类ID有误");
			return $this->dispatcher->forward ( array (
					"controller" => "category",
					"action" => "index"
			) );
		}
		
		$category = Category::findFirst( $categoryId );

		if (! $category) {
			$this->flash->error ( "分类不存在");			
			return $this->dispatcher->forward ( array (
					"controller" => "category",
					"action" => "index" 
			) );
		}	
		$category->parentId = $this->request->getPost ( "parentId" );
		$category->categoryName = $this->request->getPost ( "categoryName" );
		$category->description = $this->request->getPost ( "description" );

		if (! $category->save ()) {
			
			foreach ( $category->getMessages () as $message ) {
				$this->flash->error ( $message );
			}			
			return $this->dispatcher->forward ( array (
					"controller" => "category",
					"action" => "edit",
					"params" => array (
							$category->categoryId 
					) 
			) );
		}		
		$this->flash->success ( "分类修改成功" );		
		return $this->dispatcher->forward ( array (
				"controller" => "category",
				"action" => "index" 
		) );
	}
	
	/**
	 * Deletes a category
	 *
	 * @param string $caterogry_id        	
	 */
	public function deleteAction($caterogry_id) {
		$category = Category::findFirstBycaterogry_id ( $caterogry_id );
		if (! $category) {
			$this->flash->error ( "category was not found" );
			
			return $this->dispatcher->forward ( array (
					"controller" => "category",
					"action" => "index" 
			) );
		}
		
		if (! $category->delete ()) {
			
			foreach ( $category->getMessages () as $message ) {
				$this->flash->error ( $message );
			}
			
			return $this->dispatcher->forward ( array (
					"controller" => "category",
					"action" => "search" 
			) );
		}
		
		$this->flash->success ( "category was deleted successfully" );
		
		return $this->dispatcher->forward ( array (
				"controller" => "category",
				"action" => "index" 
		) );
	}
}
