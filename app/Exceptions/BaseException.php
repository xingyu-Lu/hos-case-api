<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    protected $status = 400;

    protected $code = 10000;

    protected $message = '参数错误';

    protected $success = false;

    public function __construct($params=[])
    {
        if(!is_array($params)){
            return;
        }
        if(array_key_exists('status',$params)){
            $this->status = $params['status'];
        }
        if(array_key_exists('msg',$params)){
            $this->message = is_array($params['msg']) ? json_encode($params['msg']) : $params['msg'];
        }
        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getSuccess()
    {
        return $this->success;
    }
}
