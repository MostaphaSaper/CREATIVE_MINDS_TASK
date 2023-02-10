<?php

namespace App\Traits;

trait UserTrait
{
    public function returnError($errorNum,$msg){
        return response()->json([
            'status' => false,
            'errNum' => $errorNum,
            'msg' => $msg
        ]);
    }

    public function returnSuccessMessage($errorNum="S000",$msg=""){
        return [
            'status' => true,
            'errNum' => $errorNum,
            'msg' => $msg,
        ];
    }

    public function returnData($key,$value,$token="",$msg=""){
        return response()->json([
            'status' => true,
            'errNum' => 'S000',
            'msg' => $msg,
            $key => $value,
            'token' => $token,
        ]);
    }
    public function returnCodeAccordingToInput($validator){
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }
    public function getErrorCode($input){
        if($input == 'email'){
            return 'E0001';
        }
        else if($input == 'phone'){
            return 'E0002';
        }
        else if($input == 'name'){
            return 'E0003';
        }
        else if($input == 'password'){
            return 'E0004';
        }
    }
    public function returnValidationError($code='E0001',$validator){
        return $this->returnError($code,$validator->errors()->first());
    }
}
