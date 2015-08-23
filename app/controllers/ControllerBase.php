<?php
use Phalcon\Mvc\Controller;

/**
 *
 * @author guoho
 * @property \Phalcon\Dispatcher $dispatcher;
 * @property \Phalcon\Mvc\Router\Route $router;
 * @property \Phalcon\Session $session;
 * @property \Phalcon\Db $db;
 * @property \Phalcon\Http\Request $request;
 * @property \Phalcon\Http\Cookie $cookie;
 * @property \Phalcon\Http\Response $response;
 * @property \Phalcon\Http\view $view;
 * @property \Phalcon\Http\url $url;
 */
class ControllerBase extends Controller {
	/**
	 * 用户提交过来的各种数据
	 * @var array
	 */
	protected $data = false;
	
	public function initialize() {
		
	}
	
	/**
	 *  渲染为字符串
	 * @param string $returnValue
	 */
	private function renderString($returnValue) {
		$this->view->disable ();
		$this->response->setContent( $returnValue );
		return $this->response->send ();
	}
	
	/**
	 *  渲染为JSON串
	 * @param array $returnValue
	 */
	private function renderJson($returnValue) {
		$this->view->disable ();
		$this->response->setJsonContent ( $returnValue );
		return $this->response->send ();
	}
	
	/**
	 * 自定义的 内容渲染
	 */
	private function render() {
		$returnValue = $this->dispatcher->getReturnedValue ();
		if(empty($returnValue)){
			return ;
		}
		if (is_array ( $returnValue )) {
			$this->renderJson ( $returnValue );
		}else {
			$this->renderString($returnValue);
		}			
	}
	
	/**
	 * 自定义内容render
	 * @param \Phalcon\Dispatcher $dispatcher
	 */
	public function afterExecuteRoute($dispatcher) {
		$this->render();
	}
}
