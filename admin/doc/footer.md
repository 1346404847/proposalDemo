##异常说明

 错误码  | 描述
 ------ | -----------
 -32700 | Parse Error
 -32600 | Invalid Request
 -32601 | Method Not Found
 -32602 | Invalid Params
 -32603 | Internal Error
 -32001 | Invalid Signature Format
 -32002 | Signature Error
 -32003 | Invalid Uid
 -32004 | The method does not allow access
 -32005 | Invalid Username Password
 
 错误码  | 描述
  ------ | -----------
  -31005 | 未配置答卷
  -31006 | 答题未开始
  -31007 | 答题已结束

##签名算法
```php
    //按顺序拼接参数,秘钥,日期, 采用MD5算法生成TOKEN
    md5('SERVICE' .  $key . $headers['Date'])
```

##接口地址

正式地址：http://apicustomer.topjoy.com