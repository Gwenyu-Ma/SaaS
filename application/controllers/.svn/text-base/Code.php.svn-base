<?php
use Gregwar\Captcha\CaptchaBuilder;

class CodeController extends Yaf_Controller_Abstract
{
    public function init()
    {
        Yaf_Dispatcher::getInstance()->disableView();
    }

    /**
     * 生成验证码
     */
    public function verifyAction()
    {
        if (!session_id()) session_start();

        $builder = new CaptchaBuilder;
        $builder->setDistortion(false);
        $builder->setIgnoreAllEffects(true);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);

        $builder->build();
        //$builder->build($width = 160, $height = 60, $font = null);

        //验证码字符串放入session
        $_SESSION['captcha_keystring'] = $builder->getPhrase();
        header('Content-Type: image/jpeg');
        $builder->output();
    }

//    /**
//     * @return bool|void
//     */
//
//    public function smsRegVerifyAction()
//    {
//        $result = 1;//0成功 1失败
//        $phoneNo = $_REQUEST['phone'];
//
//        $user = new UserModel();
//
//        echo $user->smsVerify(1,$phoneNo);
//    }
}


