<?php
$route['wlr/wl'] = 'CustomeInterface/wechat_login';//客户小程序登陆
$route['wps/reg'] = 'CustomeInterface/wechat_custome_regist';//客户小程序注册
$route['wps/getHl'] = 'CustomeInterface/ControlHomeProductList';//获取首页列表
$route['wps/getCl'] = 'CustomeInterface/CompetitionlList';//获取比赛列表
$route['wps/getOl'] = 'CustomeInterface/ControlOrderList';//获取订单列表
$route['wps/getsing'] = 'CustomeInterface/getSignData';//获取报名列表
$route['wps/sfl'] = 'CustomeInterface/sendFinl';//发送总决赛
$route['wps/getsfl'] = 'CustomeInterface/getSignfinl';//获取预赛报名表无进入总决赛名单
$route['wps/excel'] = 'CustomeInterface/ControlExcel';//获取预赛报名表无进入总决赛名单
$route['wps/zip'] = 'CustomeInterface/Controlzip';//获取报名照片数据
$route['wps/express'] = 'CustomeInterface/getExpress';// 获取物流信息
$route['wps/AdvertImg'] = 'CustomeInterface/AdvertImageUpload';// 廣告圖片上傳
$route['wps/AdvertNew'] = 'CustomeInterface/newAdvert';// 新增廣告
$route['wps/AdvertModify'] = 'CustomeInterface/updateAdvert';// 修改廣告
$route['wps/getAdvert'] = 'CustomeInterface/getAdvertData';// 获取廣告
$route['wps/AdvertDel'] = 'CustomeInterface/delAdvert';// 删除廣告
$route['wps/getSkip'] = 'CustomeInterface/getTypeInfo';// 获取跳转