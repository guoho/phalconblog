<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class CategoryController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for category
     */
    public function searchAction()
    {
    	 
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Category", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "caterogry_id";

        $category = Category::find($parameters);
        if (count($category) == 0) {
            $this->flash->notice("The search did not find any category");

            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $category,
            "limit"=> 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a category
     *
     * @param string $caterogry_id
     */
    public function editAction($caterogry_id)
    {

        if (!$this->request->isPost()) {

            $category = Category::findFirstBycaterogry_id($caterogry_id);
            if (!$category) {
                $this->flash->error("category was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "category",
                    "action" => "index"
                ));
            }

            $this->view->caterogry_id = $category->caterogry_id;

            $this->tag->setDefault("caterogry_id", $category->caterogry_id);
            $this->tag->setDefault("parent_id", $category->parent_id);
            $this->tag->setDefault("cat_name", $category->cat_name);
            $this->tag->setDefault("description", $category->description);
            $this->tag->setDefault("post_count", $category->post_count);
            
        }
    }

    /**
     * Creates a new category
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "index"
            ));
        }

        $category = new Category();

        $category->parent_id = $this->request->getPost("parent_id");
        $category->cat_name = $this->request->getPost("cat_name");
        $category->description = $this->request->getPost("description");
        $category->post_count = $this->request->getPost("post_count");
        

        if (!$category->save()) {
            foreach ($category->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "new"
            ));
        }

        $this->flash->success("category was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "category",
            "action" => "index"
        ));

    }

    /**
     * Saves a category edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "index"
            ));
        }

        $caterogry_id = $this->request->getPost("caterogry_id");

        $category = Category::findFirstBycaterogry_id($caterogry_id);
        if (!$category) {
            $this->flash->error("category does not exist " . $caterogry_id);

            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "index"
            ));
        }

        $category->parent_id = $this->request->getPost("parent_id");
        $category->cat_name = $this->request->getPost("cat_name");
        $category->description = $this->request->getPost("description");
        $category->post_count = $this->request->getPost("post_count");
        

        if (!$category->save()) {

            foreach ($category->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "edit",
                "params" => array($category->caterogry_id)
            ));
        }

        $this->flash->success("category was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "category",
            "action" => "index"
        ));

    }

    /**
     * Deletes a category
     *
     * @param string $caterogry_id
     */
    public function deleteAction($caterogry_id)
    {

        $category = Category::findFirstBycaterogry_id($caterogry_id);
        if (!$category) {
            $this->flash->error("category was not found");

            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "index"
            ));
        }

        if (!$category->delete()) {

            foreach ($category->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "search"
            ));
        }

        $this->flash->success("category was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "category",
            "action" => "index"
        ));
    }

}
