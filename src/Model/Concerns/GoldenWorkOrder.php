<?php
namespace Chenfeizhou\WorkOrder\Model\Concerns;

trait GoldenWorkOrder
{
    // 待审核
    protected static $wait   = 0;
    // 通过
    protected static $pass   = 1;
    // 拒绝
    protected static $reject = 9;
}
