<?php
namespace app\admin\controller;

use app\admin\model\Config as Co;
use app\admin\model\Admin;
use think\Controller;
use think\facade\Env;
use think\facade\Request;

use Qiniu\Storage\UploadManager;
use Qiniu\Auth;

/**
 * Class Login
 * @package app\admin\controller
 * @author:Pendant 861618191@qq.com
 */
class Login extends Controller
{

    /**
     * 登陆页面
     * @return mixed
     */
    public function index(){
        if (session('id')) {
             $this->redirect('/admin/index/index.html');
             return null;
        }
        return $this->fetch();
    }

    /**
     * 登陆确认
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkLogin(){
        if (!$this->request->isPost()) {
            return null;
        }
        
        $data['username'] = $this->request->post('username', '', 'trim');
        $data['password'] = $this->request->post('password', '', 'trim');
        $code = $this->request->post('code', '');

        if ($code != date('md')) {
        	$this->error('验证码错误');
        }

        if (empty($data['username']) || empty($data['password'])) {
            $this->error('请输入用户名和密码');
        }
        
        $data['last_ip'] = $this->request->ip();
        $data['last_time'] = date('Y-m-d H:i:s', time());

        $res = Admin::loginIn($data);
        
        if ($res['code']<0) {
            $this->error($res['msg']);
        } else {
            $this->success($res['msg'], '/admin/index/index.html', '', 1); //跳转时间1秒
        }
    }

    public function qiniu(){
        if (!$this->request->isPost()) {
            return api_return('Error', '0');
        }
        
        $res = Co::list();
        $accessKey = $res[1]['content'];
        $secretKey = $res[2]['content'];

        $image = \think\Image::open(request()->file('editormd-image-file'));
        
        //获取文件后缀
        $ext = pathinfo($_FILES["editormd-image-file"]['name'], PATHINFO_EXTENSION); 
        //分配随机文件名
        $filename = md5(time().rand(1111,9999)).'.'.$ext;
        //缩略图等比例缩放 原图尺寸小于缩略图尺寸则不进行缩略
        $image->thumb(900, 900,\think\Image::THUMB_SCALING)->save("../runtime/$filename");                              //修改路径到 缓存目录
        //获取文件路径
        $filePath = Env::get('root_path').'runtime/'.$filename;
        //初始化UploadManager
        $upManager = new UploadManager();
        //实例化鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        //要上传的空间
        $token = $auth->uploadToken('static');
        $resukt = list($ret, $error) = $upManager->putFile($token, $filename, $filePath);
        //删除临时文件
        $del = unlink($filePath);
        if ($resukt) {
            if ($error !== null) {
                // dump(['err'=> '1' ,'msg'=> $error ,'data' =>'']);
                return api_return('Error', '0','',$error);
            }else{
                $url ="http://qiniu.19aq.com/".$ret['key']; 
                $data = [
                    'success' => 1,
                    'message' => '上传图片至七牛成功！',
                    'url'     => $url
                ];
                return json($data);
                // return api_return('', '1','',$url);
            }
        }
        return api_return('Error', '0');
    }

    public function pull(){
        $dangqian = dirname(__FILE__);
        $dir = dirname(dirname(dirname($dangqian)));
        error_reporting ( E_ALL );
        $handle = popen('cd '.$dir.' && git pull 2>&1','r');
        $read = stream_get_contents($handle);
        printf($read."</br>");
        pclose($handle);
    }
}
