<?php
namespace Chenfeizhou\WorkOrder\Business;

use Chenfeizhou\WorkOrder\Events\WorkOrderFinishedEvent;
use Chenfeizhou\WorkOrder\Model\GoldenWorkOrderAudit;

// 工单审核回调
class WorkOrderBusiness
{
    public static function auditCallback(array $params)
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
        if ($sign != buildSignature($params, config('golden-work-order.work_order_appsecret'))) {
            throw new \Exception('Signature verification failed');
        }

        // 执行审核
        $workOrderAudit = GoldenWorkOrderAudit::where('work_order_id', $params['work_order_id'])->firstOrFail();

        // 更新工单审核状态
        $workOrderAudit->update([
            'work_order_status' => $params['status']
        ]);

        // 触发工单审核回调事件
        event(new WorkOrderFinishedEvent($workOrderAudit));
    }
}