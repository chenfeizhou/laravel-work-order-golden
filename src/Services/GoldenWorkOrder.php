<?php
/**
 * 高灯工单中心
 */
namespace Chenfeizhou\WorkOrder\Services;

use Chenfeizhou\WorkOrder\Helpers\ApiHelper;

class GoldenWorkOrder
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    // 创建工单
    public function createWorkOrder(
        string $title,
        array $content
    ) {
        $params = [
            'title'   => $title,
            'content' => $content,
        ];

        $result = $this->request('/api/open-api/work-orders', $params);

        return $result['data']['workOrder'];
    }

    protected function request(string $uri, array $params, $method = 'POST')
    {
        $url = $this->config['work_order_host'] . $uri;

        $params += [
            'cx_app_key'   => $this->config['work_order_appkey'],
            'cx_timestamp' => time(),
            'cx_nonce_str' => uniqid(),
        ];

        $params['cx_signature'] = $this->buildSign($params, $this->config['work_order_appsecret']);

        [$response] = ApiHelper::guzHttpRequest($url, $params, $method);

        if (isset($response['code']) && $response['code'] == -1) {
            throw new \Exception($response['message'] . '(code:' . $response['code'] . ')');
        }

        return $response;
    }

    // 构建签名
    public function buildSign(array $params, string $secretKey)
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
