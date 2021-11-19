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
}

// 创建工单
$params = [
    'title' => 'test',
    'content'=> [[
        'key'   => 1,
         'value' => 'test',
    ]],
    'notifier' => 'jsoner.chen',
];

$example->createWorkOrder($params);

// 工单审核回调事件监听
Chenfeizhou\WorkOrder\Events\WorkOrderFinishedEvent::class => [
    WorkOrderFinishedListener::class,
],

```
