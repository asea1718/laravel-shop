<?php
/**
 * Created by PhpStorm.
 * User: coffee
 * Date: 17-1-12
 * Time: 下午6:06
 */

namespace  SimpleShop\Commodity\Controllers\Traits;

use Validator;

trait ValidateHandler
{
    /**
     * 验证字段
     *
     * @param array $requestData
     * @param array $rule
     * @param array $msg
     * @param bool $isFirst
     *
     * @return array
     */
    public function validateField(array $requestData, array $rule, array $msg = [], $isFirst = false)
    {
        $validator = Validator::make($requestData, $rule, $msg);

        if ($validator->fails()) {
            $messages = $validator->messages()->toArray();
            $errorMsg = [];
            if (count($messages) > 0) {
                foreach ($messages as $key => $value) {
                    $errorMsg[$key] = [
                        '$valid'      => false,
                        '$invalid'    => true,
                        '$srverror'   => true,
                        '$srvmessage' => $value[0],
                    ];
                    if ($isFirst) {
                        break;
                    }
                }

                return $errorMsg;
            }
        }

        return [];
    }
}