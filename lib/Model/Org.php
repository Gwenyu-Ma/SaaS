<?php
namespace Lib\Model;

use Intervention\Image\ImageManagerStatic as Image;
use Lib\Store\Mysql as MC;
use \Lib\Store\MysqlCluster as MMC;

class Org
{
    public static function getLogo($eid)
    {
        $result=MC::getRow('select imageinfo from esm_organization where EID=:EID',[':EID'=>$eid]);
        if(!empty($result)&&!empty($result['imageinfo'])){
            return Image::make($result['imageinfo'])->response();
        }
        $logoPath= __dir__.'/../public/img/logon.png';

        return Image::make($logoPath)->response();
    }

    //云存储空间
    public static function usedSpace($eid)
    {
        MMC::clean();
        MMC::$eid = $eid;
        MMC::getRow('CALL fn_get_all_size(\'' . $eid . '\',@virsize,@vircount,@defsize,@defcount,
    @ndefsize,@ndefcount,@nmgrsize,@nmgrcount,@avirsize,
    @avircount,@aspamsize,@aspamcount,@apointsize,@apointcount
)');
        $result = MC::getRow('select @virsize virsize,
                                    @vircount vircount,
                                    @defsize defsize,
                                    @defcount defcount,
                                    @ndefsize ndefsize,
                                    @ndefcount ndefcount,
                                    @nmgrsize nmgrsize,
                                    @nmgrcount nmgrcount,
                                    @avirsize avirsize,
                                    @avircount avircount,
                                    @aspamsize aspamsize,
                                    @aspamcount aspamcount,
                                    @apointsize apointsize,
                                    @apointcount apointcount');

        $result['virsize']=$result['vircount']==0? 0:$result['virsize'];
        $result['defsize']=$result['defcount']==0? 0:$result['defsize'];
        $result['ndefsize']=$result['ndefcount']==0? 0:$result['ndefsize'];
        $result['nmgrsize']=$result['nmgrcount']==0? 0:$result['nmgrsize'];
        $result['avirsize']=$result['avircount']==0? 0:$result['avirsize'];
        $result['aspamsize']=$result['aspamcount']==0? 0:$result['aspamsize'];
        $result['apointsize']=$result['apointcount']==0? 0:$result['apointsize'];
        return $result;
    }
}
