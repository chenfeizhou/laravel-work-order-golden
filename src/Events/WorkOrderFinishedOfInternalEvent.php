<?php

namespace Chenfeizhou\WorkOrder\Events;

use Chenfeizhou\WorkOrder\Model\GoldenWorkOrderAudit;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkOrderFinishedOfInternalEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $workOrderAudit;

    public function __construct(GoldenWorkOrderAudit $workOrderAudit)
    {
        $this->workOrderAudit = $workOrderAudit;
    }
}
