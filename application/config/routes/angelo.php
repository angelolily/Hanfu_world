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
$route['wps/image'] = 'CustomeInterface/image3';//获取报名照片数据