<?php
declare(strict_types=1);

namespace App\Controller\User\Api;


use App\Controller\Base\API\User;
use App\Interceptor\Waf;
use App\Util\PayConfig;
use Kernel\Annotation\Inject;
use Kernel\Annotation\Interceptor;

#[Interceptor(Waf::class, Interceptor::TYPE_API)]
class RechargeNotification extends User
{
    #[Inject]
    private \App\Service\Recharge $recharge;

    /**
     * @return string
     */
    public function callback(): string
    {
        $handle = $_GET['_PARAMETER'][0];
        $data = $_POST;
        if (empty($data)) {
            $data = file_get_contents("php://input");
            if (!is_array($data)) {
                $data = json_decode($data, true);
            }
        }
        if (empty($data)) {
            $data = $_REQUEST;
            unset($data['s']);
        }
        PayConfig::log($handle, "CALLBACK", "接受数据1：" . (is_array($data) ? json_encode($data) : $data));
        return $this->recharge->callback($handle, $data);
    }
}