<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class AppTable extends Table {

    public function initialize(array $config) {
        $this->table($this->table());
    }

    public function table($table = null) {
        $config = self::connection()->config();
        if ($table !== null && isset($config['prefix']['app'])) {
            $table = $config['prefix']['app'] . $table;
        }
        return parent::table($table);
    }

    public function beforeFind($event, $query, $options, $primary) {
        $notArray = [];
        if (array_search($this->alias(), $notArray) === FALSE) {
            $query->where([$this->alias() . '.is_delete' => 0]);
        }
    }

    public function beforeSave($event, $entity) {
        $entity->updated_by = currentUserId;
        $entity->updated_date = currentDateTime;
        if (!isset($entity->is_delete)) {
            $entity->is_delete = 0;
        }
        if ($entity->isNew()) {
            $entity->created_by = currentUserId;
            $entity->created_date = currentDateTime;
            //on create
        } else {
            //on update
        }
    }

}
