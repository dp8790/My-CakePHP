<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Network\Http\Client;

class ServiceComponent extends Component {

    public $SERVICE_BASE_URL = 'https://exmaple.com/';
    public $components = ['Auth'];

    public function getCustomerToken($data = null) {
        $http = new Client();
        $response = $http->post('https://www.eaglesaver.com/OrderedItems/search_for_item/' . $data, json_encode($data), ['type' => 'json']);
        return $response;
    }

}

//$this->Item->amazonLookup ( $query, 1, 'New', $type, $query_type );