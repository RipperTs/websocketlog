## PHP与websocket结合实时输出日志

## 解决Nginx的wss连接问题
#### 伪静态方式处理
```
location /socket/ {
    # 这里写服务器的ip,也可写127.0.0.1
    proxy_pass http://xxx.xxx.xxx:8936;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "Upgrade";
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Real-IP $remote_addr;
}
```