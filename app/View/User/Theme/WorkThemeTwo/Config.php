<?php
declare(strict_types=1);

namespace App\View\User\Theme\WorkThemeTwo;

use App\Consts\Render;

interface Config
{

    const INFO = [
        "NAME" => "主题2 - 简洁风",
        "AUTHOR" => "祥拓软件",
        "VERSION" => "1.0.0",
        "WEB_SITE" => "https://card.work100.com.cn",
        "DESCRIPTION" => "简洁风，方便、快捷",
        "RENDER" => Render::ENGINE_SMARTY
    ];


    /**
     * 配置信息
     */
    const SUBMIT = [
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
        ],
        [
            "title" => "ICP备案号",
            "name" => "icp",
            "type" => "input",
            "placeholder" => "填写后将会在店铺底部显示ICP备案号，不填写则不显示。"
        ],
        [
            "title" => "公安备案号",
            "name" => "gaicp",
            "type" => "input",
            "placeholder" => "填写后将会在店铺底部显示ICP备案号，不填写则不显示。"
        ],
        [
            "title" => "版权",
            "name" => "copyright",
            "type" => "input",
            "placeholder" => "底部版权"
        ],
    ];

    /**
     * 模板文件重定向，不需要修改的直接删除
     */
    const THEME = [
        "INDEX" => "Index/Index.html", //卡网首页s
        "CLOSED" => "Index/Closed.html", //维护页面
        "QUERY" => "Index/Query.html", //订单查询
        "LOGIN" => "Authentication/Login.html", //用户登录
        "REGISTER" => "Authentication/Register.html", //用户注册
        "FORGET_EMAIL" => "Authentication/ForgetEmail.html", //用户找回密码-邮箱
        "FORGET_PHONE" => "Authentication/ForgetPhone.html", //用户找回密码-手机
    ];

}