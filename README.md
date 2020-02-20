# 说明
基于 [ZipStream-PHP](https://github.com/maennchen/ZipStream-PHP) 库，优化了对 Swoole 扩展的支持。

# 使用

```
//配置options与原始库配置一样
//需要指定 outputStream 为 Swoole Response对象
$options = new \ZipStream\Option\Archive();
$options->setSendHttpHeaders(true);
//指定 output 对象为 swoole response对象
$options->setOutputStream($this->response());

//新建zip对象，动态的写入数据，将会同时下载
$zip = new \W7\ZipStream\ZipStream('example.zip', $options);
for ($i=0; $i<100000; $i++) {
    $zip->addFile($i, 'test');
}

$zip->finish();
```
