<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Controller\Component\CookieComponent;

class UsersController extends AppController {

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
    }

    public function index($roleId = null) {
        
    }

    public function login() {
        $this->layout = 'login';
        if ($this->Auth->user('id')) {
            return $this->redirect(['action' => 'dashboard']);
        }
        $this->set('title_for_layout', 'Login');
        $this->set('title_for_layout', 'Login');
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if (isset($this->request->data['remember_me']) && $this->request->data['remember_me'] == "on") { //User has checked Remember me                
                $cookie = array();
                $cookie['username'] = $this->request->data['username'];
                $cookie['password'] = $this->request->data['password'];
                $this->Cookie->write('rememberMe', $cookie, true, "2 weeks");
            }
            if ($user) {
                $this->Auth->setUser($user);
				$this->Flash->success('Welcome back '.$user['first_name'].'!');
                return $this->redirect(['action' => 'dashboard']);
            }
            $this->Flash->error('Invalid username or password. Please try again.');
        }
    }

    public function dashboard() {
        $this->set('title_for_layout', 'Dashboard');
        $UserCount = $this->Users->find("all")->where(['Users.status !=' => '0'])->count();
        $this->set('UserCount', $UserCount);
    }
	
	public function change_pwd() {
        $this->set('title_for_layout', 'Change PassWord');
        $user = $this->Users->get($this->Auth->user('id'));

        if (!empty($this->request->data)) {
            $user = $this->Users->patchEntity($user, ['old_password' => $this->request->data['old_password'], 'password' => $this->request->data['password1'], 'password1' => $this->request->data['password1'], 'password2' => $this->request->data['password2']], ['validate' => 'password']);

            if ($this->Users->save($user)) {
                $this->Flash->success('The password is successfully changed');
                return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
            } else {
                $this->Flash->error('There was an error during the save!');
            }
        }
        $this->set('user', $user);
    }

}
