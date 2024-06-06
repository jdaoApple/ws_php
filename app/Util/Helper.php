<?php
declare(strict_types=1);

namespace App\Util;

use Kernel\Exception\JSONException;
use App\Util\Context;
use GuzzleHttp\Client;

/**
 * 助手
 */
class Helper
{
    /**
     * 通用插件
     */
    const TYPE_GENERAL = 0;

    /**
     * 支付扩展
     */
    const TYPE_PAY = 1;

    /**
     * 网站模板
     */
    const TYPE_THEME = 2;
    
    /**
     * 获取分类 
     */
    public static function getTypeArr($mode = 0,$new = false){
        //获取任务类型
        $cache = Context::get('_FILTERING_CONFIG_TYPE');
        if (!$cache) {
            $client = new Client();
            $res = $client->post('https://stq.shuju678.com/api/merchant/api/type.php', [
                'multipart' => [
                    [
                        'name' => 'api_key',
                        'contents' => '6593fd43783f0f0f8b34cc4d',
                    ]
                ],
            ]);
            $cache = json_decode($res->getBody()->getContents(), true);
            if (!empty($cache['data'])) {
                Context::set('_FILTERING_CONFIG_TYPE', json_encode($cache));
            }
        }else{
            $cache = json_decode($cache,true);
        }
        $res = [];
        if(!empty($cache['data'])){
            foreach ($cache['data'] as $v){
                if($mode == 0){
                    $res[$v['code']] =  $v['name'];
                }else{
                    $v['is_maintain'] == 0 && $res[] = ['id' => $v['code'],'name' => $v['name']];
                }
            }
        }
        return $res;
    }
    /**
     * 获取国家
     */
    public static function getCountryArr($mode = 0){
        $cache = Context::get('_FILTERING_CONFIG_COUNTRY');
        if (!$cache) {
            $client = new Client();
            $res = $client->post('https://stq.shuju678.com/api/merchant/api/country.php', [
                'multipart' => [
                    [
                        'name' => 'api_key',
                        'contents' => '6593fd43783f0f0f8b34cc4d',
                    ]
                ],
            ]);
            $cache = json_decode($res->getBody()->getContents(), true);
            if (!empty($cache['data'])) {
                Context::set('_FILTERING_CONFIG_COUNTRY', json_encode($cache));
            }
        }else{
            $cache = json_decode($cache,true);
        }
        $res = [];
        if(!empty($cache['data'])){
            foreach ($cache['data'] as $v){
                if($mode == 0){
                    $res[$v['code']] =  $v['name'];
                }else{
                    $v['is_maintain'] == 0 && $res[] = ['id' => $v['code'],'name' => $v['name']];
                }
            }
        }
        return $res;
    }


    /**
     * 获取主题目录所在的URL地址
     * @throws \ReflectionException
     * @throws JSONException
     */
    public static function themeUrl(string $path, bool $debug = false): string
    {
        $mobile = \App\Model\Config::get("user_mobile_theme");
        $pc = \App\Model\Config::get("user_theme");
        $theme = Client::isMobile() ? $mobile : $pc;
        if ($theme == "0") {
            $theme = $pc;
        }
        return "/app/View/User/Theme/" . $theme . "/{$path}?v=" . Theme::getConfig($theme)["info"]["VERSION"] . (!$debug ? "" : "&debug=" . Str::generateRandStr(16));
    }

    /**
     * @param string $key
     * @param int $type
     * @return bool|array
     */
    public static function isInstall(string $key, int $type): bool|array
    {

        $path = match ($type) {
            self::TYPE_GENERAL => BASE_PATH . "/app/Plugin/{$key}",
            self::TYPE_PAY => BASE_PATH . "/app/Pay/{$key}",
            self::TYPE_THEME => BASE_PATH . "/app/View/User/Theme/{$key}",
        };

        if (!is_dir($path)) {
            return false;
        }

        switch ($type) {
            case self::TYPE_GENERAL:
                if (!file_exists($path . "/Config/Info.php")) {
                    return false;
                }
                $config = require($path . "/Config/Info.php");
                if (!is_array($config)) {
                    return false;
                }
                if (!array_key_exists(\App\Consts\Plugin::VERSION, $config)) {
                    return false;
                }
                return $config;
                break;
            case self::TYPE_PAY:
                if (!file_exists($path . "/Config/Info.php")) {
                    return false;
                }
                $config = require($path . "/Config/Info.php");
                if (!is_array($config)) {
                    return false;
                }
                if (!array_key_exists("version", $config)) {
                    return false;
                }
                return $config;
                break;
            case self::TYPE_THEME:
                if (!file_exists($path . "/Config.php")) {
                    return false;
                }
                $namespace = "App\\View\\User\\Theme\\{$key}\\Config";
                if (!interface_exists($namespace)) {
                    return false;
                }
                return $namespace::INFO;
                break;
        }

        return false;
    }
}