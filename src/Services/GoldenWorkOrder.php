<?php
/**
 * 高灯工单中心
 */
namespace Chenfeizhou\WorkOrder\Services;

class GoldenWorkOrder
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    // 创建工单
    public function createWorkOrder(
        string $pk,
        string $title,
        array $content,
        string $notifier = null
    ) {
        $params = [
            'pk'        => $pk,
            'notifier'  => $notifier,
            'title'     => $title,
            'content'   => $content,
        ];

        $result = $this->request('/api/open-api/work-orders', $params);

        return $result['data']['workOrder'];
    }

    protected function request(string $uri, array $params, string $method = 'POST')
    {
        $url = $this->config['work_order_host'] . $uri;

        $params += [
            'cx_app_key'   => $this->config['work_order_appkey'],
            'cx_timestamp' => time(),
            'cx_nonce_str' => uniqid(),
        ];

        $params['cx_signature'] = buildSignature($params, $this->config['work_order_appsecret']);

        [$response] = guzHttpRequest($url, $params, $method);

        if (isset($response['code']) && $response['code'] == -1) {
            throw new \Exception($response['message'] . '(code:' . $response['code'] . ')');
        }

        return $response;
    }
}
