# 说明
基于 [ZipStream-PHP](https://github.com/maennchen/ZipStream-PHP) 库，优化了对 Swoole 扩展的支持。

# 使用

配置 options 与原始库一样，只是需要指定 outputStream 为 Swoole Respons 对象。

\W7\ZipStream\ZipStream 对象继承自 \ZipStream\ZipStream 对象，只是重写了 Send 方法。
使用方法与原始库一致。

```
$options = new \ZipStream\Option\Archive();
$options->setSendHttpHeaders(true);
//指定 output 对象为 swoole response对象
$options->setOutputStream($this->response());

$zip = new \W7\ZipStream\ZipStream('example.zip', $options);
for ($i=0; $i<100000; $i++) {
    $zip->addFile($i, 'test');
}

$zip->finish();
```
