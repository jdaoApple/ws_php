<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\Base\View\User;
use App\Interceptor\UserSession;
use App\Interceptor\Waf;
use Kernel\Annotation\Interceptor;
use GuzzleHttp\Client;
use App\Util\Helper;

#[Interceptor([Waf::class, UserSession::class])]
class Filtering extends User
{
    /**
     * @throws \ReflectionException
     * @throws \Kernel\Exception\ViewException
     */
    public function whatsApp(): string
    {
        //获取任务类型
        $type = json_encode(Helper::getTypeArr(1));
        //国家
        $country = json_encode(Helper::getCountryArr(1));
        
        return $this->theme("账号筛选", "FILTERING_WHATSAPP", "Filtering/WhatsApp.html",compact('type','country'));
    }

    /**
     * @throws \ReflectionException
     * @throws \Kernel\Exception\ViewException
     */
    public function telegram(): string
    {
        return $this->theme("Telegram筛选", "FILTERING_TELEGRAM", "Filtering/Telegram.html");
    }

}