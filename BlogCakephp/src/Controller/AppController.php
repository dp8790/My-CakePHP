<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Network\Email\Email;
use Cake\Core\App;
use Cake\Network\Exception\NotFoundException;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Xml;
use Cake\Controller\Component;
use Cake\Network\Http\Client;

class AppController extends Controller {

    public function initialize() {
        $this->loadComponent('Flash');
        $this->loadComponent('Cookie', ['expiry' => '1 day']);
        $this->Cookie->config(['expires' => '+10 days', 'httpOnly' => false]);

        if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
            $authArray = [
                'loginRedirect' => ['controller' => 'Users', 'action' => 'dashboard', 'prefix' => 'admin'],
                'logoutRedirect' => ['controller' => 'Users', 'action' => 'login', 'prefix' => 'admin']
            ];
        } else {
            $authArray = [
                'loginRedirect' => ['controller' => 'Users', 'action' => 'index'],
                'logoutRedirect' => ['controller' => 'Users', 'action' => 'login'],
                    //'authenticate' => ['Form' => ['contain' => ['Profiles']]]
            ];
        }
        $this->loadComponent('Auth', $authArray);
    }

    public function beforeFilter(Event $event) {
        $this->Cookie->key = 'qSI232qs*&sXOw!adre@34SAv!@*(XSL#$%)asGb$@11~_+!@#HKis~#^';
        $this->Cookie->httpOnly = true;

        if (!$this->Auth->User('id') && $this->Cookie->read('rememberMe')) {
            $cookie = $this->Cookie->read('rememberMe');

            $this->loadModel('Users');
            $user = $this->Auth->identify();
            $user = $this->Users->find('all', array(
                        'conditions' => array(
                            'Users.username' => $cookie['username'],
                        //  'Users.password' => $cookie['password']
                        )
                    ))->hydrate(false)->toArray();
            ;
            if ($user) {
                $this->request->data['username'] = $cookie['username'];
                $this->request->data['password'] = $cookie['password'];
                $user = $this->Auth->identify();
                if ($user) {
                    $this->Auth->setUser($user);
                    return $this->redirect(['controller' => 'users', 'action' => 'dashboard', 'prefix' => 'admin']);
                } else {
                    return $this->redirect(['controller' => 'users', 'action' => 'logout', 'prefix' => 'admin']);
                }
            } else {
                return $this->redirect(['controller' => 'users', 'action' => 'logout', 'prefix' => 'admin']);
            }
        }
        $allAllowAction = [];
        $allAllowAction[] = 'login';
        $allAllowAction[] = 'logout';

        if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
            $this->layout = 'admin';
            if ($this->Auth->User('id')) {
                if ($this->Auth->User('role_id') != 1) {
                    return $this->redirect(Router::url('/', true));
                }
            }
        } else {
            $allAllowAction[] = 'index';
            $allAllowAction[] = 'details';
            $allAllowAction = array_combine(array_values($allAllowAction), array_values($allAllowAction));
            if ($this->Auth->User('id')) {
                if ($this->Auth->User('role_id') == 1) {
                    return $this->redirect(Router::url('/admin', true));
                }
            }
        }
        $this->Auth->allow($allAllowAction);

        $this->loadModel("Blogs");
        $popularBlogs = $this->Blogs->find('all')->order('rand()')->limit('5')->hydrate(false)->toArray();
        $this->set('popularBlogs', $popularBlogs);

        $this->set('currentAction', $this->request->action);
        $this->set('currentController', $this->name);
        $this->set('currentpage', strtolower($this->name) . '_' . strtolower($this->request->action));

        if (!$this->name) {
            throw new NotFoundException('Could not find that post');
        }

        if (!defined('currentUserId')) {
            define("currentUserId", $this->Auth->User('id'));
        }
        if (!defined('currentUserRole')) {
            define("currentUserRole", $this->Auth->User('role_id'));
        }
        if (!defined('currentDateTime')) {
            define("currentDateTime", date('Y-m-d H:i:s'));
        }
        if (!defined('PROJECT_URL')) {
            define("PROJECT_URL", Router::url('/', true));
        }
        if (!defined('PROJECT_DIR')) {
            define("PROJECT_DIR", WWW_ROOT);
        }
        if (!defined('FullName')) {
            define("FullName", 'Dhruv Patel');
        }
        if (!defined('CLIENT_IP')) {
            define("CLIENT_IP", $this->request->clientIp());
        }
        if (!defined('CURRENT_PAGE_URL')) {
            define("CURRENT_PAGE_URL", Router::url(null, true));
        }

        define("EDITOR_IMG_DIR", PROJECT_DIR . "backend/img/editor_img/");
        define("EDITOR_IMG_URL", PROJECT_URL . "backend/img/editor_img/");

        define("BLOG_IMG_DIR", PROJECT_DIR . "img/blog/");
        define("BLOG_IMG_URL", PROJECT_URL . "img/blog/");

        define("DOC_DIR", PROJECT_DIR . "documents/");
        define("DOC_URL", PROJECT_URL . "documents/");
    }

    public function logout() {
        $this->Cookie->delete('rememberMe');
        return $this->redirect($this->Auth->logout());
    }

    function AjaxPaging($modelName = null, $condtions = [], $requestData = [], $order = [], $data_column = [], $containArr = null, $condtionsDefault = [], $groupbyArr = []) {
        $page_data = [
            "draw" => 1,
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data_order" => [],
            "data_condtions" => [],
            'data_condtions_default' => $condtionsDefault,
            "data" => [],
        ];

        if (empty($modelName)) {
            $modelName = $this->name;
        }
        if (empty($requestData) || !is_array($requestData)) {
            $requestData = [];
        }
        $page_data = array_merge($page_data, $requestData);

        if (empty($page_data['length'])) {
            $page_data['length'] = 10;
        }
        if (empty($page_data['start'])) {
            $page_data['start'] = 0;
        }
        $order_data = [];
        if (!empty($requestData['order'])) {
            $sort_direction = strtoupper($requestData['order'][0]['dir']);
            $sort_column = $requestData['order'][0]['column'];
            if (!empty($data_column[$sort_column])) {
                if (isset($data_column[$sort_column]['fields']) && isset($data_column[$sort_column]['table'])) {
                    $order_data = [ucfirst($data_column[$sort_column]['table']) . "." . $data_column[$sort_column]['fields'] => $sort_direction];
                } else {
                    $order_data = [ucfirst($modelName) . "." . $data_column[$sort_column] => $sort_direction];
                }
            }
        }
        if (empty($order)) {
            $order = [];
        }
        $order = array_merge($order_data, $order);
        $page_data['data_order'] = $order;
        if (!isCallByAjax()) {
            //return $page_data;
        }

        $queryObj = $this->{$modelName}->find();
        if (!empty($containArr)) {
            $queryObj->contain($containArr);
        }

        $queryObj->order($order);
        if (!empty($condtionsDefault) && is_array($condtionsDefault)) {
            $queryObj->where($condtionsDefault);
        }

        if (!empty($groupbyArr) && is_array($groupbyArr)) {
            $queryObj->group($groupbyArr);
        }

        $recordsTotal = $queryObj->count();

        $page_data["recordsTotal"] = intval($recordsTotal);
        if (!empty($condtions) && is_array($condtions)) {
            $queryObj->where($condtions);
            $page_data['data_condtions'] = $condtions;
        }

        $recordsFiltered = $queryObj->count();
        $page_data["recordsFiltered"] = intval($recordsFiltered);
        $perPage = $page_data['length'];
        $currentPage = ($page_data['start'] / $perPage) + 1;
        $queryObj->limit($perPage);
        $queryObj->page($currentPage);
        $queryObj->hydrate(false);
        $data = $queryObj->toArray();
        $page_data['data'] = $data;


        return $page_data;
    }

    function fileupload() {
        if ($_FILES['file']['name']) {
            if (!$_FILES['file']['error']) {
                $name = md5(rand(100, 200));
                $ext = explode('.', $_FILES['file']['name']);
                $filename = $name . '.' . $ext[1];
                $destination = EDITOR_IMG_DIR . $filename;
                $location = $_FILES["file"]["tmp_name"];
                move_uploaded_file($location, $destination);
                echo EDITOR_IMG_URL . $filename;
            } else {
                echo $message = 'Ooops!  Your upload triggered the following error:  ' . $_FILES['file']['error'];
            }
        }
        exit;
    }

}
