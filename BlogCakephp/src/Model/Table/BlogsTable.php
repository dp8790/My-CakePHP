<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Validation\Validator;

class BlogsTable extends Table {

    protected $_accessible = [
        'image_path' => true,
    ];

    public function validationDefault(Validator $validator) {
        $validator
                ->notEmpty('title', 'Title is required')
                ->notEmpty('description', 'Description is required');
        return $validator;
    }

    function add_image($data, $id) {
        $data['photo'] = $data['image_path'];
        unset($data['image_path']);

        $upData = array();
        if ($this->fnIsNotNull($data['photo']) && $data['photo']['error'] == 0) {
            $allowedExts = array("gif", "jpeg", "jpg", "png");
            //$cropImageOption = array();
            //if (isset($data['photo_crop'])) {
            //$cropImageOption = explode("_", $data['photo_crop']);
            //}
            //$optionsUpload = array('org' => true, "thumbs" => array("width" => 100, "height" => 100), 'cropImageOption' => $cropImageOption);

            $optionsUpload = array('org' => true, "thumbs" => array("width" => 270, "height" => 180));
            $fileUploaded = fileUpload($data['photo'], $data['id'] . "_blog", BLOG_IMG_DIR, $allowedExts, $optionsUpload);
            if (isset($fileUploaded['success']) && fnIsNotNull($fileUploaded['success'])) {
                $upData = $fileUploaded['success'][0]['filename'];
            }
        }
        if (isset($id) && count($upData)) {
            $oldData = $this->get($id);
            $remvoeImg = $oldData->photo;
            $oldData->photo = $upData;
            $res = $this->save($oldData);
            if ($res && !empty($upData) && !empty($remvoeImg)) {
                @unlink(BLOG_IMG_DIR . $remvoeImg);
                @unlink(BLOG_IMG_DIR . "thumbs/" . $remvoeImg);
            }
            return $res;
        }
        return false;
    }

    function fnIsNotNull($data) {
        return (@trim($data) === "" or $data === null or ! isset($data) or ! count($data) ) ? false : true;
    }

    public function findNextPrev(Query $query, array $options) {
        $id = $options['id'];
        $previous = $this->find()->select(['id', 'title'])->order(['id' => 'DESC'])->where(['id <' => $id, 'status' => '1', 'is_delete !=' => '1'])->first();
        $next = $this->find()->select(['id', 'title'])->order(['id' => 'ASC'])->where(['id >' => $id, 'status' => '1', 'is_delete !=' => '1'])->first();
        return ['prev' => $previous, 'next' => $next];
    }

}
