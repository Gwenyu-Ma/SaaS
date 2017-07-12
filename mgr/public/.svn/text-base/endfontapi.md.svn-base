#后台管理接口->登录

##地址

`index/postLogin`

##参数

#后台管理接口->组织管理接口

`http://192.168.20.171:8005/org/testgetorgs`

##地址

`/org/getOrgs`

##参数

`{"eid":"D05D6DE488005623","oName":"s","uName":"153","paging":{"order":0,"limit":10,"offset":0,"sort":"UserName"}}`

##返回值

`{"r":{"code":0,"action":0,"msg":""},"data":[{"EID":"D05D6DE488005623","OName":"script","UserName":"15300318514","CreateTime":"2016-08-15 15:22:56","LastLoginTime":"2017-01-03 09:30:04","OnlineState":0,"ClientCount":0}]}`


#客户端管理页面

`http://192.168.20.171:8005/client/testgetclients`

##地址

`/client/getClients`

##参数

`{"eid":"D05D6DE488005623","sKey":"ip","sValue":"193","paging":{"order":0,"limit":10,"offset":0,"sort":"groupname"}}`

##返回值

`{"r":{"code":0,"action":0,"msg":""},"data":[{"eid":"D05D6DE488005623","sguid":"A175C88D01595CD98F6B55C91AA53D75","groupname":null,"computername":"GUODF-LENOVO","memo":null,"ip":"193.168.19.93","mac":"F0-DE-F1-B5-AC-AB","version":"3.0.0.27","systype":"windows","onlinestate":0,"activetime":0,"lastime":0}]}`

#命令

`http://192.168.20.171:8005/index/testhomepage`

#组织管理
`http://192.168.20.171:8005/group/testgetgroups`

#统计每分钟终端在线状态数量
##地址
`http://192.168.20.171:8005/client/getClientOnLineStatByTime`
##返回值
`{"r":{"code":0,"action":0,"msg":""},"data":[{"time":"2017-02-17","win":6,"android":2,"linux":1},{"time":"2017-02-18","win":8,"android":1,"linux":3},{"time":"2017-02-16","win":3,"android":4,"linux":5},{"time":"2017-02-15","win":2,"android":1,"linux":2},{"time":"2017-02-14","win":4,"android":8,"linux":7}]}`

#按操作系统统计客户端个数
##地址
`http://192.168.20.171:8005/client/getClientOSTypeStat`
##返回值
`{"r":{"code":0,"action":0,"msg":""},"data":{"win":1,"android":1,"linux":1}}`

#每天新增客户端数
##地址
`http://192.168.20.171:8005/client/getNewClientTrend`
##返回值
`{"r":{"code":0,"action":0,"msg":""},"data":[{"time":"2017-02-17","count":2},{"time":"2017-02-16","count":2},{"time":"2017-02-18","count":2},{"time":"2017-02-14","count":2},{"time":"2017-02-15","count":2},{"time":"2017-02-12","count":2}]}`

#企业大小统计接口
##地址
`http://192.168.20.171:8005/org/getOrgSizeStat`
##返回值
`{"r":{"code":0,"action":0,"msg":""},"data":[{"eid":"eid1","count":1},{"eid":"eid2","count":2}]}`

#新增企业列表
##地址
`org/getNewOrgTrend`
##返回值
{"r":{"code":0,"action":0,"msg":""},"data":[{"time":"2017-2-14","ucount":1,"ecount":1},{"time":"2017-2-15","ucount":1,"ecount":1},{"time":"2017-2-16","ucount":1,"ecount":1},{"time":"2017-2-18","ucount":1,"ecount":1},{"time":"2017-2-19","ucount":1,"ecount":1}]}