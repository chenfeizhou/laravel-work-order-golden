<?php
namespace Chenfeizhou\WorkOrder\Model\Concerns;

trait GoldenWorkOrder
{
    // 待审核
    protected $wait   = 0;
    // 通过
    protected $pass   = 1;
    // 拒绝
    protected $reject = 9;

    // 工单审核状态文案
    public function workOrderStatusTxt()
    {
        return [
            $this->wait   => '待审批',
            $this->pass   => '通过',
            $this->reject => '拒绝',
        ];
    }
}