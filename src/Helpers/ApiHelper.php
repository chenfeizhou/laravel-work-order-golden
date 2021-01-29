<?php
namespace Chenfeizhou\WorkOrder\Helpers;

class ApiHelper
{
    // 发起 HTTP 请求
    public static function guzHttpRequest(
        string $url,
        array $params,
        $method = 'POST',
        string $format = null,
        array $headers = []
    ) {
        // @see http://guzzle-cn.readthedocs.io/zh_CN/latest
        $http = new \GuzzleHttp\Client(['verify' => false, 'headers' => $headers]);

        $data = [$method == 'POST' ? 'form_params' : 'query' => $params];

        if ($format == 'JSON') {
            $data = ['json' => $params];
        }

        $response = $http->request($method, $url, $data);

        $body = $response->getBody(true);
        $body = json_decode($body, true);

        return $body;
    }

    // 构建签名
    public static function buildSign(array $params, string $secretKey)
    {
        // 键名升序
        ksort($params);

        $strs = [];

        foreach ($params as $key => $value) {
            $strs[] = $key . '=' . (is_array($value) ? json_encode($value) : $value);
        }

        // 拼接待签名字符串
        $paramStr = implode('&', $strs);

        // 构造签名
        $signStr = base64_encode(hash_hmac('sha256', $paramStr, $secretKey, true));

        return urlencode($signStr);
    }
}
