<?php
class SettingController extends MyController
{
    public function init()
    {
        parent::init();
    }

    public function setapiurlAction()
    {
        if(!parse_url(@$_POST['url'])){
            $this->notice('参数错误', 1);
            return;
        }
        (new DownloadModel())->setURL(@$_POST['url']);
        OplogModel::add($_SESSION['UserInfo']['EID'], $_SESSION['UserInfo']['UserName'], '配置', '更新', '更新下载平台地址');
        $this->ok();
    }

    public function getapiurlAction()
    {
        $url = (new DownloadModel())->getURL();
        $url = $url ?: '';
        $this->ok($url);
    }
}

