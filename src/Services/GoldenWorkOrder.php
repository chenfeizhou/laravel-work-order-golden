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

    // 工单审核回调
    public function auditCallback(
        callable $callback,
        array $params
    ) {
        // 参数验证
        \Validator::make($params, [
            'nonce_str'     => 'required|string',
            'timestamp'     => 'required|string',
            'work_order_id' => 'required|integer',
            'status'        => 'required|integer',
            'remark'        => 'nullable|string',
            'sign'          => 'required|string',
        ])->validate();

        $sign = $params['sign'];

        // sign不参与签名
        unset($params['sign']);

        // 签名验证
        if ($sign != ApiHelper::buildSign($params, $this->config['work_order_appsecret'])) {
            throws('Signature verification failed');
        }

        // 执行回调
        call_user_func($callback, $params);
    }

    protected function request(string $uri, array $params, string $method = 'POST')
    {
        $url = $this->config['work_order_host'] . $uri;

        $params += [
            'cx_app_key'   => $this->config['work_order_appkey'],
            'cx_timestamp' => time(),
            'cx_nonce_str' => uniqid(),
        ];

        $params['cx_signature'] = ApiHelper::buildSign($params, $this->config['work_order_appsecret']);

        [$response] = ApiHelper::guzHttpRequest($url, $params, $method);

        if (isset($response['code']) && $response['code'] == -1) {
            throw new \Exception($response['message'] . '(code:' . $response['code'] . ')');
        }

        return $response;
    }
}
