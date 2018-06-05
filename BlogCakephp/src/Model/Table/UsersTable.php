<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Validation\Validator;

class UsersTable extends AppTable {
	public function validationPassword(Validator $validator) {
        $validator->notEmpty('old_password', 'Password is required')->add('old_password', 'custom', ['rule' => function($value, $context) {
                $user = $this->get($context['data']['id']);
                if ($user) {                    
                    if ((new DefaultPasswordHasher)->check($value, $user->password)) {
                        return true;
                    }
                } return false;
            }, 'message' => 'The old password does not match the current password!',])->notEmpty('old_password');
        $validator->notEmpty('password1', 'New password is required')->add('password1', ['match' => ['rule' => ['compareWith', 'password2'], 'message' => 'The passwords does not match!',]])->notEmpty('password1');
        $validator->notEmpty('password2', 'Confirm password is required')->add('password2', ['match' => ['rule' => ['compareWith', 'password1'], 'message' => 'The passwords does not match!',]])->notEmpty('password2');
        return $validator;
    }
}
