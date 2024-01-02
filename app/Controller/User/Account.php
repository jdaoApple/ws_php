<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\Base\View\User;
use App\Interceptor\UserSession;
use App\Interceptor\Waf;
use Illuminate\Database\Eloquent\Builder;
use Kernel\Annotation\Interceptor;

#[Interceptor([Waf::class, UserSession::class])]
class Account extends User
{

    /**
     * @throws \ReflectionException
     * @throws \Kernel\Exception\ViewException
     */
    public function index(): string
    {
        return $this->theme("推特号/FB号/谷歌号/小火箭", "ACCOUNT_INDEX", "Account/Index.html");
    }

    /**
     * @throws \ReflectionException
     * @throws \Kernel\Exception\ViewException
     */
    public function cloud(): string
    {
        return $this->theme("WhatsApp直登号", "ACCOUNT_CLOUD", "Account/Cloud.html");
    }

    public function simulator(): string
    {
        return $this->theme("WS模拟器直登号", "ACCOUNT_SIMULATOR", "Account/Simulator.html");
    }

    /**
     * @throws \ReflectionException
     * @throws \Kernel\Exception\ViewException
     */
    public function directLogin(): string
    {
        return $this->theme("海外实卡/飞机直登号", "ACCOUNT_DIRECT_LOGIN", "Account/DirectLogin.html");
    }

    /**
     * @throws \ReflectionException
     * @throws \Kernel\Exception\ViewException
     */
    public function channel(): string
    {
        return $this->theme("WS频道协议号", "ACCOUNT_CHANNEL", "Account/Channel.html");
    }

    /**
     * @throws \ReflectionException
     * @throws \Kernel\Exception\ViewException
     */
    public function socialize(): string
    {
        $list = \App\Model\Commodity::query()->select(['commodity.*','category.is_socialize'])->where("category.is_socialize", 1)
            ->join('category','commodity.category_id','=','category.id')
            ->orderBy("commodity.sort", "asc")
            ->withCount(['card as card_count' => function (Builder $builder) {
                $builder->where("status", 0);
            }])->get();
        $result = [];
        $isFirst = true;
        foreach ($list as $item) {
            if (empty($result[$item['category_id']])){
                $result[$item['category_id']] = [
                    'id' => $item['category_id'],
                    'class' => $isFirst ? 'show' : '',
                    'name' => \App\Model\Category::query()->where('id', $item['category_id'])->value('name'),
                    'icon' => \App\Model\Category::query()->where('id', $item['category_id'])->value('icon'),
                    'count' => \App\Model\Commodity::query()->where('category_id', $item['category_id'])->count('id'),
                ];
                $isFirst = false;
            }
            $result[$item['category_id']]['list'][] = $item;
        }


        return $this->theme("海外社交软件", "ACCOUNT_SOCIALIZE", "Account/Socialize.html", compact('result'));
    }


}