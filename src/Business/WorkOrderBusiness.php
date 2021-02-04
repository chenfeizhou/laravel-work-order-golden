<?php
namespace Chenfeizhou\WorkOrder\Business;

use Chenfeizhou\WorkOrder\Events\WorkOrderFinishedEvent;

// 工单审核回调
class WorkOrderBusiness
{
    public static function auditCallback(array $params)
    {
        // 回调处理
        $workOrderAudit = app('golden.work-order')->auditCallback($params);

        // 触发工单审核回调事件
        event(new WorkOrderFinishedEvent($workOrderAudit));
    }
}