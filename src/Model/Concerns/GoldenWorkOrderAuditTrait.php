<?php
namespace Chenfeizhou\WorkOrder\Model\Concerns;

use Chenfeizhou\WorkOrder\Model\GoldenWorkOrderAudit;

trait GoldenWorkOrderAuditTrait
{
    // 模型下的工单审核列表
    public function goldenWorkOrderAudits()
    {
        return $this->morphMany(GoldenWorkOrderAudit::class, 'target');
    }

    // 模型下的最近一条工单审核记录
    public function goldenWorkOrderAudit()
    {
        return $this->goldenWorkOrderAudits()->orderBy('id', 'desc')->first();
    }

    // 获取最近审核记录工单状态
    public function getWorkOrderStatusAttribute()
    {
        return $this->goldenWorkOrderAudit()->work_order_status ?? GoldenWorkOrderAudit::WORK_ORDER_STATUS_WAIT;
    }

    // 是否审核通过
    public function isPassed()
    {
        return $this->work_order_status == GoldenWorkOrderAudit::WORK_ORDER_STATUS_PASS ? true : false;
    }

    // 获取最近审核记录工单状态文案
    public function getWorkOrderStatusTxtAttribute()
    {
        $color = GoldenWorkOrderAudit::WORK_ORDER_STATUS_COLORS[$this->work_order_status];
        $text  = GoldenWorkOrderAudit::WORK_ORDER_STATUS_TEXT[$this->work_order_status];

        return '<span class="label label-' . $color . '">' . $text . '</span>';
    }

    // 创建工单
    public function createWorkOrder(
        int $workOrderId,
        int $workOrderStatus = 0
    ) {
        $this->goldenWorkOrderAudits()->create([
            'work_order_id'     => $workOrderId,
            'work_order_status' => $workOrderStatus,
        ]);
    }
}
