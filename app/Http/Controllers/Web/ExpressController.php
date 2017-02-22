<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Web\BaseController;
use Illuminate\Http\Request;

/*
 * Api接口--快递
 */
class ExpressController extends BaseController
{
    public $EBusinessID = '1275211';
    public $AppKey = 'f3e2189c-8207-4ff6-8518-d62b9ef7546a';
    public $ReqURL = 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx';

    //单号轨迹查询
    public function index(Request $request)
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

        $logisticCode = $all['logisticCode'];

        $logisticResult = $this->numberRecognitionByJson($logisticCode);

        $resultArray = json_decode($logisticResult, true);

        $shipperCode = $resultArray['Shippers'][0]['ShipperCode'];
        //$logisticCode = $resultArray['LogisticCode'];

        $trace = $this->instantQueryByJson($shipperCode, $logisticCode);
        $traceArray = json_decode($trace, true);

	    $array = [
            'status' => 1001,
            'data' => $traceArray,
            'info' => '查询成功'
        ];

	    return response()->json($array);
    }

    //即时查询
    public function instantQuery()
    {
        $shipperCode = 'YZPY';
        $logisticCode = '9630066489578';

        $logisticResult = $this->instantQueryByJson($shipperCode, $logisticCode);
        echo $logisticResult;
    }

    //单号识别
    public function numberRecognition()
    {
        $logisticCode = '9630066489578';

        $logisticResult = $this->numberRecognitionByJson($logisticCode);
        echo $logisticResult;
    }


    /**
     * Json方式 查询订单物流轨迹
     */
    function instantQueryByJson($shipperCode, $logisticCode)
    {
        $json = "{'OrderCode':'','ShipperCode':'" . $shipperCode . "','LogisticCode':'" . $logisticCode . "'}";

        $requestData= $json;
        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
        $result = $this->sendPost($this->ReqURL, $datas);

        //根据公司业务处理返回的信息......

        return $result;
    }

    /**
     * Json方式 单号识别
     */
    public function numberRecognitionByJson($logisticCode)
    {
        $json = "{'LogisticCode':'" . $logisticCode . "'}";

        $requestData = $json;
        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '2002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
        $result = $this->sendPost($this->ReqURL, $datas);

        //根据公司业务处理返回的信息......

        return $result;
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    public function sendPost($url, $datas)
    {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    public function encrypt($data, $appkey)
    {
        return urlencode(base64_encode(md5($data.$appkey)));
    }

}

?>
