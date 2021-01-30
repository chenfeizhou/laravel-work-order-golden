# laravel-work-order-golden
Laravel「高灯工单中心」

### 使用指南

```
composer require chenfeizhou/laravel-work-order-golden
php artisan vendor:publish --provider="Chenfeizhou\WorkOrder\WorkOrderServiceProvider"
php artisan migrate
```

### 在项目根目录的 `.env` 文件中增加以下配置：

```
GOLDEN_WORK_ORDER_HOST=
GOLDEN_WORK_ORDER_APPKEY=
GOLDEN_WORK_ORDER_APPSECRET=
GOLDEN_WORK_ORDER_CALLBACK=
```

## 使用
```php
// 模型中引用
class Example extends Model
{
    use Chenfeizhou\WorkOrder\Model\Concerns\GoldenWorkOrderAuditTrait;
    
     protected $appends = [
         'work_order_status',
         'work_order_status_txt',
     ]; 
}

// 创建工单
$title='test';
$content = [
    [
      'key'   => '1', 
      'value' => 'test'
    ]
 ];
$example->createWorkOrder($title, $content);

// 工单审核回调方法
use Chenfeizhou\WorkOrder\Model\GoldenWorkOrderAudit

GoldenWorkOrderAudit::auditCallback($request->all());

```
