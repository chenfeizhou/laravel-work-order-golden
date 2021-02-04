<?php
/**
 * 高灯工单中心
 */
namespace Chenfeizhou\WorkOrder\Services;

use Chenfeizhou\WorkOrder\Helpers\ApiHelper;
use Chenfeizhou\WorkOrder\Model\GoldenWorkOrderAudit;

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
    public function auditCallback(array $params)
    {
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
            throw new \Exception('Signature verification failed');
        }

        // 执行审核
        $workOrderAudit = GoldenWorkOrderAudit::where('work_order_id', $params['work_order_id'])->firstOrFail();

        // 更新工单审核状态
        $workOrderAudit->update([
            'work_order_status' => $params['status']
        ]);

        return $workOrderAudit;
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

        $response = ApiHelper::guzHttpRequest($url, $params, $method);

        if (isset($response['code']) && $response['code'] == -1) {
            throw new \Exception($response['message'] . '(code:' . $response['code'] . ')');
        }

        return $response;
    }
}
