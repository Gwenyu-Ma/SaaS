<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-1-6
 * Time: 下午1:21
 */
use Lib\Util\Log;
class ErrorController extends MyController {

    public function errorAction($exception) {

        if(!empty($exception->xdebug_message)){
            $exception->xdebug_message = null;
        }
        Log::error("ERROR_CONTROLLER", [
            "request_ip" => Common::getIP(),
            "exception" => $exception,
        ]);
        if($this->request->isXmlHttpRequest()){
            $this->alert($exception->getMessage(), 1);
            return;
        }
        //echo $exception->getMessage();
        header('location: /404.html');
        return;
    }
}

