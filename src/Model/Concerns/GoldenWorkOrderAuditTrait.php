<?php
namespace Chenfeizhou\WorkOrder\Model\Concerns;

use Chenfeizhou\WorkOrder\Model\GoldenWorkOrderAudit;

trait GoldenWorkOrderAuditTrait
{
    public function initializeGoldenWorkOrderAuditTrait()
    {
        $this->append([
            'golden_work_order_status',
            'golden_work_order_status_txt',
            'golden_work_order_is_wait',
            'golden_work_order_is_pass',
            'golden_work_order_is_reject',
        ]);
    }

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
    public function getGoldenWorkOrderStatusAttribute()
    {
        return $this->goldenWorkOrderAudit()->work_order_status ?? GoldenWorkOrderAudit::WORK_ORDER_STATUS_WAIT;
    }

    // 是否待审核
    public function getGoldenWorkOrderIsWaitAttribute()
    {
        return $this->golden_work_order_status == GoldenWorkOrderAudit::WORK_ORDER_STATUS_WAIT ? true : false;
    }

    // 是否审核通过
    public function getGoldenWorkOrderIsPassAttribute()
    {
        return $this->golden_work_order_status == GoldenWorkOrderAudit::WORK_ORDER_STATUS_PASS ? true : false;
    }

    // 是否审核拒绝
    public function getGoldenWorkOrderIsRejectAttribute()
    {
        return $this->golden_work_order_status == GoldenWorkOrderAudit::WORK_ORDER_STATUS_REJECT ? true : false;
    }

    // 获取最近审核记录工单状态文案
    public function getGoldenWorkOrderStatusTxtAttribute()
    {
        $color = GoldenWorkOrderAudit::WORK_ORDER_STATUS_COLORS[$this->golden_work_order_status];
        $text  = GoldenWorkOrderAudit::WORK_ORDER_STATUS_TEXT[$this->golden_work_order_status];

        return '<span class="label label-' . $color . '">' . $text . '</span>';
    }

    // 创建工单
    public function createWorkOrder(
        string $title,
        array $content,
        string $notifier = null
    ) {
        // 远程调用创建工单
        $workOrder = app('golden.work-order')->createWorkOrder(
            $title,
            $content,
            $notifier
        );

        // 本地工单系统绑定
        $this->goldenWorkOrderAudits()->create([
            'work_order_id'     => $workOrder['id'],
            'work_order_status' => GoldenWorkOrderAudit::WORK_ORDER_STATUS_WAIT,
        ]);
    }
}
