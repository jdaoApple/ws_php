<?php

declare(strict_types=1);

namespace App\View\User\Theme\Xens;

use App\Consts\Render;

/**
 * Interface Config
 */
interface Config
{
    /**
     * 介绍信息
     */
    const INFO = [
        "NAME" => " Xens交互式模板",
        "AUTHOR" => "某毅",
        "VERSION" => "1.0.8",
        "WEB_SITE" => "https://o.ls",
        "DESCRIPTION" => "Xens是一个全站的模板,修改了前端较多元素且更换了前端框架进行写的bootstrapmb模板",
        "RENDER" => Render::ENGINE_SMARTY
    ];

    /**
     * 配置信息
     */
    const SUBMIT = [
        [
            "title" => "展现方式",
            "name" => "type",
            "type" => "select",
            "dict" => [
                ["id" => 0, "name" => "三格"],
                ["id" => 1, "name" => "四格"]
            ],
            "default" => 0,
            "placeholder" => "请选择"
        ],
        [
            "title" => "模板风格",
            "name" => "style",
            "type" => "radio",
            "dict" => [
                ["id" => 0, "name" => "蔚蓝视界"],
                ["id" => 1, "name" => "万顷琉璃"]
            ],
            "default" => 0
        ],
        [
            "title" => "左侧公告",
            "name" => "notice",
            "type" => "editor"
        ],
        [
            "title" => "ICP备案号",
            "name" => "icp",
            "type" => "input",
            "placeholder" => "填写后将会在店铺底部显示ICP备案号，不填写则不显示。"
        ],
        [
            "title" => "缓存",
            "name" => "cache",
            "type" => "switch",
            "text" => "开启",
            "tips" => "浏览器本地缓存，缓存时间60秒"
        ],
        [
            "title" => "缓存时间",
            "name" => "cache_expire",
            "type" => "input",
            "placeholder" => "缓存过期时间，推荐60秒",
            "default" => 60
        ]
    ];

    /**
     * 模板文件重定向，不需要修改的直接删除
     */
    const THEME = [
        //---------------- 商铺服务(开始)
        "INDEX" => "Template/Users/Paging.html", //卡网首页
        "QUERY" => "Template/Users/Query.html", //卡网查询
        "ACCOUNT_INDEX" => "Template/Users/Shop/Index.html",
        "ACCOUNT_CLOUD" => "Template/Users/Account/Cloud.html",
        "ACCOUNT_DIRECT_LOGIN" => "Template/Users/Account/DirectLogin.html",
        "ACCOUNT_CHANNEL" => "Template/Users/Account/Channel.html",
        "ACCOUNT_SIMULATOR" => "Template/Users/Account/Simulator.html",
        "ACCOUNT_SOCIALIZE" => "Template/Users/Account/Socialize.html",
        "FILTERING_WHATSAPP" => "Template/Users/Filtering/WhatsApp.html",
        "FILTERING_TELEGRAM" => "Template/Users/Filtering/Telegram.html",
        //---------------- 商铺服务(结束)

        //---------------- 账户管理(开始)
        "DASHBOARD" => "Template/Users/Dashboard/Index.html", //会员-仪表盘
        "RECHARGE" => "Template/Users/Recharge/Index.html", //会员-充值中心
        "PURCHASE_RECORD" => "Template/Users/Recharge/PurchaseRecord.html", //会员-购买记录
        "CASH" => "Template/Users/Cash/Index.html", //会员-硬币兑现
        "AGENT_MEMBER" => "Template/Users/Agent/Index.html", //推广代理-我的下级
        "BILL" => "Template/Users/Bill/Index.html", //会员-我的账单
        "PERSONAL" => "Template/Users/Personal/Index.html", //会员-个人资料
        //---------------- 账户管理(结束)
        //---------------- 店铺功能(开始)
        "BUSINESS" => "Template/Users/Business/Index.html", //会员-我的店铺
        "CATEGORY" => "Template/Users/Business/Category.html", //会员-商品分类
        "COMMODITY" => "Template/Users/Business/Commodity.html", //会员-我的商品
        "CARD" => "Template/Users/Business/Card.html", //会员-卡密管理
        "COUPON" => "Template/Users/Business/Coupon.html", //会员-优惠卷管理
        "ORDER" => "Template/Users/Business/Order.html", //会员-订单管理
        //---------------- 店铺功能(结束)
    ];

}