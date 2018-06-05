<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Controller\Component\CookieComponent;

class BlogsController extends AppController {

    public $helpers = array('Paginator' => array('Paginator'));
    public $paginate = array('limit' => 10, 'order' => ['Blogs.id' => 'desc']);

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    public function add_edit($id = null) {
        if (!empty($id)) {
            $Blog = $this->Blogs->get($id);
            $this->set('title_for_layout', 'Edit Blog');
        } else {
            $Blog = $this->Blogs->newEntity();
            $this->set('title_for_layout', 'Create Blog');
        }
        if ($this->request->is('post') || $this->request->is('PUT')) {
            $Blog = $this->Blogs->patchEntity($Blog, $this->request->data);
            if (!empty($id)) {
                $msgSuccess = 'Blog has been updated successfully!';
                $msgError = 'Error while updating Blog. Please try again.';
            } else {
                $msgSuccess = 'Blog has been added successfully!';
                $msgError = 'Error while adding Blog. Please try again.';
            }
            $remvoeImg = '';
            if ($Blog['image_remove'] == 1 && !empty($id)) {
                $remvoeImg = $Blog->photo;
                $Blog->photo = '';
            }
            $res = $this->Blogs->save($Blog);
            if ($res) {
                if (!empty($remvoeImg)) {
                    @unlink(BLOG_IMG_DIR . $remvoeImg);
                    @unlink(BLOG_IMG_DIR . "thumbs/" . $remvoeImg);
                }
                $this->Blogs->add_image($Blog, $res['id']);
                if (isCallByAjax()) {
                    $returnData = array();
                    $returnData['status'] = 1;
                    $returnData['success'] = $msgSuccess;
                    sendJsonEncode($returnData, 1);
                } else {
                    $this->Flash->success($msgSuccess);
                    return $this->redirect(['action' => 'index']);
                }
            }
            if (isCallByAjax()) {
                $returnData = array();
                $returnData['status'] = 0;
                $returnData['error'] = $msgError;
                sendJsonEncode($returnData, 1);
            } else {
                $this->Flash->error($msgError);
            }
        }
        $this->set('Blog', $Blog);
    }
    public function index() {
        $this->set('title_for_layout', 'List of blogs with ajax pagination');
        $modelName = 'Blogs';
        $conditions = [];
        $condtionsDefault = ['Blogs.is_delete !=' => '1'];
        $requestData = (!empty($this->request->data)) ? $this->request->data : [];
        $order = ['Blogs.id' => 'DESC'];
        $data_column = ['photo', 'title', 'description'];

        if (!empty($requestData['columns'][0]['search']['value'])) {
            $search_val = $requestData['columns'][0]['search']['value'];
            $conditions['Blogs.title LIKE'] = '%' . $search_val . '%';
        }
        $containArr = [];
        $page_data = $this->getDataPaging($modelName, $conditions, $requestData, $order, $data_column, $containArr, $condtionsDefault);
        $this->set('page_data', $page_data);
    }

    public function generate_pdf() {
        $this->layout = '';
        $Blogs = $this->Blogs->find('all')->hydrate(false)->toArray();
        $this->set('data', $Blogs);
    }

    public function generate_word_document() {
        $this->layout = '';
        $Blogs = $this->Blogs->find('all')->hydrate(false)->toArray();
        $this->set('data', $Blogs);
    }

    function delete($id = null) {
        if ($this->request->is('post') && !empty($id)) {
            $this->Blogs->updateAll(['Blogs.is_delete' => '1'], ['Blogs.id' => $id]);
            $msg = "Blog has been deleted successfully!";
        } else {
            $msg = "Error while deleting Blog. Please try again.";
        }
        $this->Flash->success($msg);
        return $this->redirect(['action' => 'index']);
    }

    function active_inactive($id = null) {
        $this->layout = '';
        if (!empty($this->request->data)) {
            $this->Blogs->updateAll(['Blogs.status' => $this->request->data['Status']], ['Blogs.id' => $this->request->data['BlogId']]);
            $msg = 'Blog has been deactivated successfully!';
            if ($this->request->data['Status'] == '1') {
                $msg = "Blog has been activated successfully!";
            }
            $result = json_encode(array('status' => 'success', 'msg' => $msg));
        } else {
            $msg = "There was an error. Please try again!";
            $result = json_encode(array('status' => 'error', 'msg' => $msg));
        }
        echo $result;
        exit;
    }

}
