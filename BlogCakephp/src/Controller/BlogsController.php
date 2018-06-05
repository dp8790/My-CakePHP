<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Utility\Inflector;
use Cake\Datasource\ConnectionManager;

class BlogsController extends AppController {

    public $helpers = array('Paginator' => array('Paginator'));
    public $paginate = array('limit' => 3, 'order' => ['Users.id' => 'desc']);

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    public function index() {
        $query = $this->Blogs->find('all')->where(['Blogs.status' => '1', 'Blogs.is_delete !=' => '1']);
        $this->set('blogs', $this->paginate($query));
    }

    public function details($id = null) {

        $Blog = $this->Blogs->get($id);
        $NextPrev = $this->Blogs->find('nextprev', ['id' => $id]);
        $this->set('Blog', $Blog);
        $this->set('NextPrev', $NextPrev);
    }

}
