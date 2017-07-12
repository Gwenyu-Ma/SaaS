<?php
namespace Lib\Util;

// Example:
// Email::send(array(array('name'=>'Tom', 'email'=>'tom@gmail.com')), 'Subject', 'Content');

use Tx\Mailer\SMTP;
use Tx\Mailer\Message;

class Email{
    protected static $instance;
    protected static $config;

    private function __construct(){}

    protected static function _init(){
        if(self::$instance !== null){
            return;
        }
        $config = require(__DIR__.'/../../config/email.php');
        self::$instance = new SMTP();
        self::$instance->setServer($config['server'], $config['port']);
        self::$instance->setAuth($config['username'], $config['password']);
        self::$config = $config;
    }

    public static function send($tos, $subject, $body, $attachments=[]){
        self::_init();
        $message = new Message();
        $message->setFrom(self::$config['fromName'], self::$config['fromEmail']);
        foreach($tos as $v){
            $message->addTo($v['name'], $v['email']);
        }
        $message->setSubject($subject);
        $message->setBody($body);
        foreach($attachments as $v){
            $message->addAttachment($v['name'], $v['path']);
        }
        return self::$instance->send($message);
    }

}



