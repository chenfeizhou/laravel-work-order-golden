<?php
namespace Chenfeizhou\WorkOrder\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GoldenWorkOrderAudit extends Pivot
{
    protected $table = 'golden_work_order_audits';

    // 工单审批状态
    const WORK_ORDER_STATUS_WAIT = 0,
        WORK_ORDER_STATUS_PASS   = 1,
        WORK_ORDER_STATUS_REJECT = 9;

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

    // 获取拥有此工单的模型
    public function target()
    {
        return $this->morphTo();
    }

    // 工单审核回调
    public static function auditCallback(array $params, callable $callable)
    {
        $audit = app('golden.work-order')->auditCallback($params);

        // 执行回调
        call_user_func($callable, $audit->target);
    }
}
