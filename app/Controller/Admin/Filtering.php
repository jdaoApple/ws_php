<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Base\View\User;
use App\Interceptor\ManageSession;
use App\Interceptor\UserSession;
use App\Interceptor\Waf;
use Kernel\Annotation\Interceptor;

#[Interceptor(ManageSession::class)]
class Filtering extends Manage
{
    /**
     * @throws \ReflectionException
     * @throws \Kernel\Exception\ViewException
     */
    public function whatsApp(): string
    {
        return $this->render("WhatsApp筛选", "Filtering/WhatsApp.html");
    }

    /**
     * @throws \ReflectionException
     * @throws \Kernel\Exception\ViewException
     */
    public function telegram(): string
    {
        return $this->render("Telegram筛选", "Filtering/Telegram.html");
    }

}