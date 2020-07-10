<?php
namespace SeniorProgramming\Fancourier\Services;

use SeniorProgramming\FanCourier\Core\Base;
use SeniorProgramming\FanCourier\Helpers\Hints;
use SeniorProgramming\FanCourier\Exceptions\FanCourierInstanceException;
use SeniorProgramming\FanCourier\Exceptions\FanCourierUnknownModelException;

class ApiService extends Base {
    
    
    private $credentials;

    /**
     *
     * @param string $username
     * @param string $password
     * @param string $clientId
     */
    public function __construct(string $username, string $password, string $clientId) 
    {
        $this->credentials = (object) [
            'username'  => $username,
            'user_pass'  => $password,
            'client_id' => $clientId,
        ];
    }
    
    /**
     * 
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws FanCourierUnknownModelException
     * @throws FanCourierInstanceException
     */
    public function __call($method, $args = array()) {
        
        $instance = parent::instantiate(ucfirst($method));
        
        if(!is_callable([$instance, 'set'])) {
            throw new FanCourierUnknownModelException("Method $method does not exist");
        }
        
        try {
            return parent::makeRequest($this->credentials, call_user_func_array([$instance, 'set'], $args));
        } catch (Exception $ex) {
            throw new FanCourierInstanceException("Invalid request");
        }
    }
    
    public function csvImportHelper() {
        return Hints::importCsvKeys();
    }
}


