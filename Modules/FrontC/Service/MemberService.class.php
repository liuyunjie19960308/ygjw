<?php
namespace FrontC\Service;

/**
 * Class MemberService
 * @package FrontC\Service
 * 用户相关数据 服务层
 */
class MemberService extends FrontBaseService
{

    /**
     * @param string $account
     * @return mixed|string
     * 手机账号中间四位****替换
     */
    public function accountFormat($account = '')
    {
        if (empty($account)) {
            return '';
        }
        return substr_replace($account, '****', 3, 4);
    }

    /**
     * @param int $avatar
     * @return string
     * 获取头像地址
     */
    public function getAvatar($avatar = 0)
    {
        //判断是否存在头像
        if (empty($avatar)) {
            $avatar = C('FILE_HOST') . '/Uploads/avatar/default.png';
        } else {
            $file = api('File/getFiles', array($avatar, array('abs_url')));
            $avatar = $file[0]['abs_url'];
        }
        return $avatar;
    }

    /**
     * [isSubscribe 是否关注了公众号]
     * @Author   黑暗中的武者
     * @DateTime 2019-09-21T16:37:20+0800
     * @param    integer                  $m_id [description]
     * @return   boolean                        [description]
     */
    public function isSubscribe($m_id = 0, $openid = '')
    {
        if (empty($m_id) && empty($openid)) {
            return ['subscribe' => 0,'code_path' => ''];
        }
        // 用户ID查询
        if (!empty($m_id)) {
            $where['m_id']   = $m_id;
        }
        // openid查询
        if (!empty($openid)) {
            $where['openid'] = $openid;
        }

        $oauth = M('MemberOauth')->where($where)->field('subscribe')->find();
        // 未关注
        if (empty($oauth) || $oauth['subscribe'] == 0) {
            // 获取关注二维码
            $code_path = api('WeChat/getSubscribeCode', [$m_id]);
            
            return ['subscribe' => 0,'code_path' => substr($code_path, 1)];
        }
        return ['subscribe' => 1,'code_path' => ''];
    }

    /**
     * @var array
     * 字段标识映射数组
     */
    protected $fields_map = [
        1   => 'id m_id',
        2   => 'member_sn',
        3   => 'account',
        4   => 'nickname',
        5   => 'avatar',
        6  => 'balance',
        7  => 'integral',
        8  => 'pay_pass',
    ];
    /**
     * @param string $account
     * @param int $m_id
     * @param string $fields_identify 要获取的字段标识串
     * @return bool|mixed
     * 获取用户信息
     */
    public function getInfo($account = '', $m_id = 0, $fields_identify = '')
    {
        //无查询条件 返回错误
        if(empty($account) && empty($m_id)) {
            return false;
        }
        //账号查询
        if($account) {
            $where['account']   = $account;
        }
        //ID查询
        if($m_id) {
            $where['id']        = $m_id;
        }

        if(empty($fields_identify)) { //为空 获取全部
            $fields = implode(',', $this->fields_map);
        } else {
            //字段标记转换为数组
            $fields_array = explode(',', $fields_identify);
            foreach($fields_array as $field) {
                if(!isset($this->fields_map[$field]))
                    continue;
                $fields[] = $this->fields_map[$field];
            }
            $fields = implode(',', $fields);
        }
        //获取用户信息
        $info = M('Member')->where($where)->field($fields)->find();
        if(!$info) {
            return false;
        }
        //账号格式化
        if(isset($info['account'])) {
            $info['account_format'] = $this->accountFormat($info['account']);
        }
        //获取头像地址
        if(isset($info['avatar'])) {
            $info['avatar_path'] = $this->getAvatar($info['avatar']);
        }

        //二维码
//        if(!is_file('./Uploads/Member/Code/' . $info['member_sn'] .'.png')) {
//            vendor('phpQrcode.phpqrcode');
//            \QRcode::png($info['member_sn'],'./Uploads/Member/Code/'.$info['member_sn'].'.png',QR_ECLEVEL_L,10,1,true);
//        }
//        $info['code_url'] = C('FILE_HOST') . 'Uploads/Member/Code/' . $info['member_sn'] .'.png';

        return $info;
    }

    /**
     * @return array
     * 用户等级策略
     */
    public function levelRule() {
        //取缓存
        $rules = S('MemberRule_Cache');
        //没有缓存
        if(!$rules) {
            //取数据库
            $list = M('MemberRule')->field('level,name,percent,conditions')->where(['status'=>1])->order('level ASC')->select();

            $rules = [];
            //以等级作为键值
            foreach($list as $value) {
                $rules[$value['level']] = $value;
            }
            //设置缓存
            S('MemberRule_Cache', $rules);
        }

        return $rules;
    }

    /**
     * @param string $member_sn
     */
    public function createCode($member_sn = ''){
        //二维码
        if(!is_file('./Uploads/Member/Code/' . $member_sn .'.png')) {
            vendor('phpQrcode.phpqrcode');
            \QRcode::png('/Share/index/code/' . $member_sn, './Uploads/Member/Code/' . $member_sn . '.png', QR_ECLEVEL_L, 8, 2, true);
        }
        //$info['code_url'] = C('FILE_HOST') . 'Uploads/Member/Code/' . $member_sn .'.png';
        //分享图
        if(is_file('./Uploads/Member/rule/' . $member_sn .'.png')) {
            return C('FILE_HOST') . './Uploads/Member/rule/' . $member_sn .'.png';
        }
        //读取邀请背景图片
        $im_bg = imagecreatefrompng ('./Uploads/rule-bg.png');
        //读取二维码图片
        $im_code = imagecreatefrompng ('./Uploads/Member/Code/' . $member_sn . '.png');
        //创建一个和背景图片一样大小的真彩色画布（ps：只有这样才能保证后面copy二维码图片的时候不会失真）
        $im = imageCreatetruecolor(imagesx($im_bg), imagesy($im_bg));
        //为真彩色画布创建白色背景，再设置为透明
        $color = imagecolorallocate($im, 255, 255, 255);
        //imagefill($im, 0, 0, $color);
        //imageColorTransparent($im, $color);
        //首先将背景画布采样copy到真彩色画布中，不会失真
        imagecopyresampled($im,$im_bg,0,0,0,0,imagesx($im_bg),imagesy($im_bg),imagesx($im_bg),imagesy($im_bg));
        //再将二维码图片copy到已经具有背景图像的真彩色画布中，同样也不会失真
        //imagecopymerge($im,$im_code, 245,650,0,0,imagesx($im_code),imagesy($im_code), 100);
        imagecopymerge($im,$im_code, 245,650,0,0,264,264, 100);
        //字体颜色
        //$tc = imagecolorallocate($im, 0, 0, 0);
        //设置字符字体 颜色 位置
        //imagettftext($im, 17, 0, 310, 975, $tc, './Public/Static/ttfs/7.ttf', '【'.$member_sn.'】');
        //将画布保存到指定的gif文件
        //imagejpeg($image_3, "./images/update/hero_gam.jpg");
        //输出类型
        header("Content-type: image/png");
        //保存图片
        //unlink($file_url);
        imagepng($im, './Uploads/Member/rule/' . $member_sn .'.png');
        //销毁内存
        imagedestroy($im);

        return C('FILE_HOST') . '/Uploads/Member/rule/' . $member_sn .'.png';

//        //合并邀请背景图和二维码和字符串为一张图片
//        //创建画布
//        $im      = imagecreate(750, 1126);
//        //读取邀请背景图片
//        $im_bg = imagecreatefrompng ('./Uploads/distribution-bg.png');
//        //读取二维码图片
//        $im_code = imagecreatefrompng ('./Uploads/Member/Code/' . $member_sn . '.png');
//        //设置背景色
//        $bc      = imagecolorallocate($im, 255, 255, 255);
//        //字体颜色
//        $tc      = imagecolorallocate($im, 0, 0, 0);
//        //设置字符字体 颜色 位置
//        imagettftext($im, 17, 0, 10, 40, $tc, './Public/ttfs/7.ttf', $name);
//        //imagettftext($im,17,0,10,90,$tc,'./Public/ttfs/7.ttf',$address);
//        //复制分销背景图图片到新画布
//        imagecopy($im, $im_bg, 0, 0, 0, 0, 750, 1126);
//        //复制二维码图片到新画布 650  245
//        imagecopy($im, $im_code, 245, 650, 0, 0, 260, 260);
//        //输出类型
//        header("Content-type: image/png");
//        //保存图片
//        //unlink($file_url);
//        imagepng($im, './Uploads/Member/Share/' . $member_sn .'.png');
//        //销毁内存
//        imagedestroy($im);
//        //返回短路径
//        //return date('Ym').'/'.$file_name;
    }

    /**
     * @param int $m_id
     * @param $pay_pass
     * @return array|bool|string
     * 验证支付密码是否正确
     */
    public function checkPayPass($m_id = 0, $pay_pass = '')
    {
        //参数判空
        if (empty($m_id) || empty($pay_pass)) {
            return $this->setServiceInfo('参数错误！', false);
        }
        //获取用户信息
        $user_info = M('Member')->where(['id'=>$m_id])->field('pay_pass')->find();
        //判断是否已设置支付密码
        if (empty($user_info['pay_pass'])) {
            return $this->setServiceInfo('未设置支付密码，为保证安全请先去个人信息中设置支付密码！', false);
        }
        //判断支付密码
        if (MD5($pay_pass) != $user_info['pay_pass']) {
            return $this->setServiceInfo('支付密码不正确！', false);
        }
        return $this->setServiceInfo('通过！', true);
    }

    /**
     * [getChannel 获取用户来源渠道]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-09T11:38:10+0800
     * @return   [type]                   [description]
     */
    public function getChannel()
    {
        $channel = 0;

        if (!empty($_REQUEST['sharecode'])) {
            $channel = 1;
        }
        if (!empty($_REQUEST['sn'])) {
            $channel = 2;
        }
        return $channel;
    }
}
