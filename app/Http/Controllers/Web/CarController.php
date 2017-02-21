<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Web\BaseController;
use Illuminate\Http\Request;
use DB;
use Crypt;
use App\Utilities\HelperVerify;

/*
 * Api接口--汽车
 */
class CarController extends BaseController
{
    public $smsAppkey = '1b0006c03abc2';
    public $smsVerifyUrl = 'https://webapi.sms.mob.com/sms/verify';

    //测试方法
    public function test()
    {
        $users = DB::table('users')->get();
        print_r($users);
    }

    //获取车主用户信息
    public function getUserinfo(Request $request)
    {
        $all = $request->all();
        if (!HelperVerify::signVerify($this->helperkey, $all)) {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => 'failed',
            ];

            return response()->json($array);
        }

        $platenumber = $all['platenumber'];

        $user = DB::table('users')
            ->select('mobile', 'name')
            ->where('platenumber', '=', $platenumber)
            ->first();

        if ($user) {
            $array = [
                'status' => 1001,
                'data' => [
                    'mobile' => $user->mobile,
                    'name' => $user->name,
                ],
                'info' => '获取信息成功',
            ];

            return response()->json($array);
        } else {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => '信息不存在',
            ];

            return response()->json($array);
        }
    }

    //修改个人信息
    public function saveUserinfo(Request $request)
    {
        $all = $request->all();
        if (!HelperVerify::signVerify($this->helperkey, $all)) {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => 'failed',
            ];

            return response()->json($array);
        }

        $id = $all['id'];
        $name = $all['name'];
        $platenumber = $all['platenumber'];

        $data = [
            'name' => $name,
            'platenumber' => $platenumber,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $result = DB::table('users')
            ->where('id', '=', $id)
            ->update($data);
        if ($result) {
            $user = DB::table('users')
                ->select('id', 'mobile', 'name', 'platenumber')
                ->where('id', '=', $id)
                ->first();

            $array = [
                'status' => 1001,
                'data' => [
                    'id' => $user->id,
                    'mobile' => $user->mobile,
                    'name' => $user->name,
                    'platenumber' => $user->platenumber,
                ],
                'info' => '修改成功',
            ];

            return response()->json($array);
        } else {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => '修改失败',
            ];

            return response()->json($array);
        }
    }

    //登录
    public function login(Request $request)
    {
        $all = $request->all();
        if (!HelperVerify::signVerify($this->helperkey, $all)) {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => 'failed',
            ];

            return response()->json($array);
        }

        $mobile = $all['mobile'];
        $password = $all['password'];

        $user = DB::table('users')
            ->select('id', 'mobile', 'password', 'name', 'platenumber')
            ->where('mobile', '=', $mobile)
            ->first();

        if ($user) {
            if ($password == Crypt::decrypt($user->password)) {
                $array = [
                    'status' => 1001,
                    'data' => [
                        'id' => $user->id,
                        'mobile' => $user->mobile,
                        'name' => $user->name,
                        'platenumber' => $user->platenumber,
                    ],
                    'info' => '登录成功',
                ];

                return response()->json($array);
            } else {
                $array = [
                    'status' => 1000,
                    'data' => null,
                    'info' => '手机号码或密码错误',
                ];

                return response()->json($array);
            }
        } else {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => '手机号码未注册',
            ];

            return response()->json($array);
        }
    }

    //注册
    public function register(Request $request)
    {
        $all = $request->all();
        if (!HelperVerify::signVerify($this->helperkey, $all)) {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => 'failed',
            ];

            return response()->json($array);
        }

        $mobile = $all['mobile'];
        $password = $all['password'];
        $code = $all['code'];

        $response = $this->smsVerify($mobile, $code);
        if ($response['status'] != 200) {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => $response['info'],
            ];

            return response()->json($array);
        }

        $exist = DB::table('users')
            ->where('mobile', '=', $mobile)
            ->first();
        if ($exist) {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => '手机号码已注册',
            ];

            return response()->json($array);
        }

        $data = [
            'mobile' => $mobile,
            'password' => Crypt::encrypt($password),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $insertId = DB::table('users')
            ->insertGetId($data);
        if ($insertId) {
            $array = [
                'status' => 1001,
                'data' => [
                    'id' => $insertId,
                    'mobile' => $mobile,
                    'name' => '',
                    'platenumber' => '',
                ],
                'info' => '注册成功',
            ];

            return response()->json($array);
        } else {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => '注册失败',
            ];

            return response()->json($array);
        }
    }

    //修改密码
    public function savePassword(Request $request)
    {
        $all = $request->all();
        if (!HelperVerify::signVerify($this->helperkey, $all)) {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => 'failed',
            ];

            return response()->json($array);
        }

        $id = $all['id'];
        $oldPassword = $all['oldPassword'];
        $password = $all['password'];

        $user = DB::table('users')
            ->select('password')
            ->where('id', '=', $id)
            ->first();

        if ($oldPassword == Crypt::decrypt($user->password)) {
            $data = [
                'password' => Crypt::encrypt($password),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $result = DB::table('users')
                ->where('id', '=', $id)
                ->update($data);

            if ($result) {
                $array = [
                    'status' => 1001,
                    'data' => null,
                    'info' => '修改密码成功',
                ];

                return response()->json($array);
            } else {
                $array = [
                    'status' => 1000,
                    'data' => null,
                    'info' => '修改密码失败',
                ];

                return response()->json($array);
            }
        } else {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => '旧密码错误',
            ];

            return response()->json($array);
        }
    }

    //重置密码
    public function resetPassword(Request $request)
    {
        $all = $request->all();
        if (!HelperVerify::signVerify($this->helperkey, $all)) {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => 'failed',
            ];

            return response()->json($array);
        }

        $mobile = $all['mobile'];
        $password = $all['password'];
        $code = $all['code'];

        $response = $this->smsVerify($mobile, $code);
        if ($response['status'] != 200) {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => $response['info'],
            ];

            return response()->json($array);
        }

        $user = DB::table('users')
            ->where('mobile', '=', $mobile)
            ->first();

        if ($user) {
            $data = [
                'password' => Crypt::encrypt($password),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $result = DB::table('users')
                ->where('mobile', '=', $mobile)
                ->update($data);

            if ($result) {
                $array = [
                    'status' => 1001,
                    'data' => null,
                    'info' => '重置密码成功',
                ];

                return response()->json($array);
            } else {
                $array = [
                    'status' => 1000,
                    'data' => null,
                    'info' => '重置密码失败',
                ];

                return response()->json($array);
            }
        } else {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => '手机号码未注册',
            ];

            return response()->json($array);
        }
    }

    //版本检查
    public function checkVersion(Request $request)
    {
        $all = $request->all();
        if (!HelperVerify::signVerify($this->helperkey, $all)) {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => 'failed',
            ];

            return response()->json($array);
        }

        $version = $all['version'];
        if ($version == $this->helperversion) {
            //没有新版本

            $array = [
                'status' => 1000,
                'data' => null,
                'info' => '没有新版本',
            ];

            return response()->json($array);
        } else {
            //有新版本

            $array = [
                'status' => 1001,
                'data' => null,
                'info' => '有新版本',
            ];

            return response()->json($array);
        }
    }


    //短信码验证
    public function smsVerify($mobile, $code)
    {
        $sms = [
            'appkey' => $this->smsAppkey,
            'phone' => $mobile,
            'zone' => '86',
            'code' => $code,
        ];
        $response = $this->postRequest($this->smsVerifyUrl, $sms);
        $response = json_decode($response, true);

        $array = [
            'status' => $response['status'],
        ];

        switch ($response['status']) {
            case 200 :
                $array['info'] = '验证成功';
                break;
            case 405 :
                $array['info'] = 'AppKey为空';
                break;
            case 406 :
                $array['info'] = 'AppKey无效';
                break;
            case 456 :
                $array['info'] = '国家代码或手机号码为空';
                break;
            case 457 :
                $array['info'] = '手机号码格式错误';
                break;
            case 466 :
                $array['info'] = '验证码为空';
                break;
            case 467 :
                $array['info'] = '校验验证码频繁';
                break;
            case 468 :
                $array['info'] = '验证码错误';
                break;
            case 474 :
                $array['info'] = '没有打开服务端验证';
                break;
        }

        return $array;
    }

    //短信码验证
    function postRequest($api, $params = [], $timeout = 30)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
            'Accept: application/json',
        ));
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }



}
