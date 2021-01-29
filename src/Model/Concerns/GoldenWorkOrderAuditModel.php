<?php
namespace Chenfeizhou\WorkOrder\Model\Concerns;
use Silverd\OhMyLaravel\Models\AbstractModel as BaseAbstractModel;

abstract class GoldenWorkOrderAuditModel extends BaseAbstractModel
{
    // 工单审批状态
    const WORK_ORDER_STATUS_WAIT   = 0,
        WORK_ORDER_STATUS_PASS     = 1,
        WORK_ORDER_STATUS_REJECT   = 9;

    const WORK_ORDER_STATUS_TEXT = [
        self::WORK_ORDER_STATUS_WAIT   => '待审批',
        self::WORK_ORDER_STATUS_PASS   => '通过',
        self::WORK_ORDER_STATUS_REJECT => '拒绝',
    ];

    const WORK_ORDER_STATUS_COLORS = [
        self::WORK_ORDER_STATUS_WAIT   => 'default',
        self::WORK_ORDER_STATUS_REJECT => 'danger',
        self::WORK_ORDER_STATUS_PASS   => 'success',
    ];

    const WORK_ORDER_STATUS_MAP = [
        0 => self::WORK_ORDER_STATUS_WAIT ,
        1 => self::WORK_ORDER_STATUS_PASS ,
        9 => self::WORK_ORDER_STATUS_REJECT ,
    ];

    // 绑定的业务
    public function bindModel()
    {
        return static::class;
    }

    // 工单回调处理
    public function workOrderCallback(int $status)
    {
        // 审核状态
        $this->update([
            'work_order_status' => static::WORK_ORDER_STATUS_MAP[$status] ?? static::WORK_ORDER_STATUS_WAIT
        ]);
    }
}
