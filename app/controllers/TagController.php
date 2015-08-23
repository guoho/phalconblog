<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class TagController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for tag
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Tag", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "tag_id";

        $tag = Tag::find($parameters);
        if (count($tag) == 0) {
            $this->flash->notice("The search did not find any tag");

            return $this->dispatcher->forward(array(
                "controller" => "tag",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $tag,
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
     * Edits a tag
     *
     * @param string $tag_id
     */
    public function editAction($tag_id)
    {

        if (!$this->request->isPost()) {

            $tag = Tag::findFirstBytag_id($tag_id);
            if (!$tag) {
                $this->flash->error("tag was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "tag",
                    "action" => "index"
                ));
            }

            $this->view->tag_id = $tag->tag_id;

            $this->tag->setDefault("tag_id", $tag->tag_id);
            $this->tag->setDefault("tag_name", $tag->tag_name);
            $this->tag->setDefault("post_count", $tag->post_count);
            
        }
    }

    /**
     * Creates a new tag
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "tag",
                "action" => "index"
            ));
        }

        $tag = new Tag();

        $tag->tag_name = $this->request->getPost("tag_name");
        $tag->post_count = $this->request->getPost("post_count");
        

        if (!$tag->save()) {
            foreach ($tag->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "tag",
                "action" => "new"
            ));
        }

        $this->flash->success("tag was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "tag",
            "action" => "index"
        ));

    }

    /**
     * Saves a tag edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "tag",
                "action" => "index"
            ));
        }

        $tag_id = $this->request->getPost("tag_id");

        $tag = Tag::findFirstBytag_id($tag_id);
        if (!$tag) {
            $this->flash->error("tag does not exist " . $tag_id);

            return $this->dispatcher->forward(array(
                "controller" => "tag",
                "action" => "index"
            ));
        }

        $tag->tag_name = $this->request->getPost("tag_name");
        $tag->post_count = $this->request->getPost("post_count");
        

        if (!$tag->save()) {

            foreach ($tag->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "tag",
                "action" => "edit",
                "params" => array($tag->tag_id)
            ));
        }

        $this->flash->success("tag was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "tag",
            "action" => "index"
        ));

    }

    /**
     * Deletes a tag
     *
     * @param string $tag_id
     */
    public function deleteAction($tag_id)
    {

        $tag = Tag::findFirstBytag_id($tag_id);
        if (!$tag) {
            $this->flash->error("tag was not found");

            return $this->dispatcher->forward(array(
                "controller" => "tag",
                "action" => "index"
            ));
        }

        if (!$tag->delete()) {

            foreach ($tag->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "tag",
                "action" => "search"
            ));
        }

        $this->flash->success("tag was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "tag",
            "action" => "index"
        ));
    }

}
