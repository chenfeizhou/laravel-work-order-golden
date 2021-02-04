<?php
namespace Chenfeizhou\WorkOrder\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chenfeizhou\WorkOrder\Business\WorkOrderBusiness;

// 工单审核回调
class WorkOrderController extends Controller
{
    public function auditCallback(Request $request)
    {
        WorkOrderBusiness::auditCallback($request->all());

        return ok(__FUNCTION__);
    }
}
