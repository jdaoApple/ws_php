<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\Base\View\User;
use App\Interceptor\UserSession;
use App\Interceptor\Waf;
use Kernel\Annotation\Interceptor;

#[Interceptor([Waf::class, UserSession::class])]
class Filtering extends User
{
    /**
     * @throws \ReflectionException
     * @throws \Kernel\Exception\ViewException
     */
    public function whatsApp(): string
    {
        return $this->theme("WhatsApp筛选", "FILTERING_WHATSAPP", "Filtering/WhatsApp.html");
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