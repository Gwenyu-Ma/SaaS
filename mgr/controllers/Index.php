<?php
class IndexController extends MyController
{
    //跳转到登陆页面
    public function indexAction()
    {      
       $this->disply('Index/index', '.php');
    }

    //提交登陆
    public function postLoginAction(){
        $str_user_name = $this->param('username', '');
        $str_user_pwd = $this->param('password', '');
        $objUser = new UsersModel();
        $aUser = $objUser->getUserInfo( $str_user_name );
        if(empty($aUser)){
            $this->notice("用户名或密码错误!", 3);
            return;
        }
        $str_pwd_md5 = Common::passMd5($str_user_pwd, $aUser['salt'], $encrypt = 'md5');

        if( $str_pwd_md5 != $aUser['password']){
            $this->notice("用户名或密码错误!", 3);
            return;
        }
        $_SESSION['manager'] = $aUser['username'];
        $data = array('location'=>array('c'=>'Home','a'=>'index'));
        $this->ok($data);
        return;
    }



    public function testHomePageAction()
    {
        echo '<table>';
        echo '<thead>组织管理页面命令</thead>';
        echo '<tbody>';
        echo '<tr><td>以下接口只有一个参数:eid</td></tr>';
        echo '<tr><td><a target="_blank" href="/client/initClientW">威胁终端:/client/initClientW</a></td></tr>';
        echo '<tr><td><a target="_blank" href="/xav/initXav">病毒数量:/xav/initXav</a></td></tr>';
        echo '<tr><td><a target="_blank" href="/xav/initRfwBNS">违规联网:/xav/initRfwBNS</a></td></tr>';
        echo '<tr><td><a target="_blank" href="/xav/initPhoneSpam">骚扰拦截:/xav/initPhoneSpam</a></td></tr>';
        echo '<tr><td><a target="_blank" href="/xav/initRfwTFA">当日流量排行:/xav/initRfwTFA</a></td></tr>';
        echo '<tr><td><a target="_blank" href="/xav/initRfwUrlByResult">恶意网址拦截:/xav/initRfwUrlByResult</a></td></tr>';
        echo '<tr><td><a target="_blank" href="/client/initClientOS">操作系统分布:/client/initClientOS</a></td></tr>';
        echo '</tbody>';
        echo '</table>';
    }

  public function  loginOutAction(){
      $_SESSION['manager'] = null;
      header('Location: /');
  }
}