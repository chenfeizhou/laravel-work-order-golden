# laravel-work-order-golden
Laravel「高灯工单中心」

### 使用指南

```
composer require chenfeizhou/laravel-work-order-golden
php artisan vendor:publish --provider="Chenfeizhou\WorkOrder\WorkOrderServiceProvider"
```

### 在项目根目录的 `.env` 文件中增加以下配置：

```
GOLDEN_WORK_ORDER_HOST=
GOLDEN_WORK_ORDER_APPKEY=
GOLDEN_WORK_ORDER_APPSECRET=
GOLDEN_WORK_ORDER_CALLBACK=
```

### 使用

```php
	
$title = '工单标题';

$content = [
	[
	 'key' 	=> 'key1',
	 'value' => 'value1',
	], [
	 'key' 	 => 'key2',
	 'value' => 'value2',
	],
];

$workOrder = app('golden.work-order')->createWorkOrder($title, $content);
	
```

