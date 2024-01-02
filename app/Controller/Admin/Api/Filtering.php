<?php
declare(strict_types=1);

namespace App\Controller\Admin\Api;

use App\Controller\Base\API\Manage;
use App\Entity\QueryTemplateEntity;
use App\Interceptor\ManageSession;
use App\Model\Filtering as FilteringModel;
use App\Service\Query;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Relations\Relation;
use Kernel\Annotation\Inject;
use Kernel\Annotation\Interceptor;
use Kernel\Exception\JSONException;

#[Interceptor(ManageSession::class, Interceptor::TYPE_API)]
class Filtering extends Manage
{
    #[Inject]
    private Query $query;

    /**
     * @return array
     */
    public function data(): array
    {
        $map = $_POST;
        $queryTemplateEntity = new QueryTemplateEntity();
        $queryTemplateEntity->setModel(FilteringModel::class);
        $queryTemplateEntity->setLimit((int)$_POST['limit']);
        $queryTemplateEntity->setPage((int)$_POST['page']);
        $queryTemplateEntity->setPaginate(true);
        $queryTemplateEntity->setWith([
            'user' => function (Relation $relation) {
                $relation->select(["id", "username", "avatar"]);
            },
        ]);
        $queryTemplateEntity->setWhere($map);
        $query = FilteringModel::query();
        $data = $this->query->findTemplateAll($queryTemplateEntity, $query)->toArray();
        foreach ($data['data'] as &$item) {
            $taskState = [
                'Close' => '关闭',
                'Finish' => '完成',
                'Ongoing' => '正在进行',
                'Waiting' => '等待'
            ];
            $type = [
                'filterNumber' => '筛选开通',
                'filterActive' => '筛选活跃'
            ];
            $item['create_time'] = date('Y/m/d H:i:s', $item['create_time']);
            $item['update_time'] = !empty($item['update_time']) ? date('Y/m/d H:i:s', $item['update_time']) : '-';
            $item['taskState'] = $taskState[$item['taskState']] ?? '未知';
            $item['type'] = $type[$item['type']] ?? '未知';
            $item['money'] = floatval($item['money']);

        }
        $json = $this->json(200, null, $data['data']);
        $json['count'] = $data['total'];

        //获取订单数量
        $json['order_count'] = (clone $query)->count();
        $json['order_amount'] = floatval((clone $query)->sum("money"));
        return $json;
    }


    public function refresh()
    {

        $list = \App\Model\Filtering::query()->whereIn('taskState', ['Ongoing','Waiting'])->get();
        $client = new Client();
        foreach ($list as $filtering)
        {
            if ($filtering->mode == 'TG') {
                $res = $client->get('http://34.96.170.183/tgES/tgout/filter_p', [
                    'headers' => [
                        'tk' => '7CLhp1FRE+k2sKsQrocbjL0zJzboZnZj'
                    ],
                    'query' => [
                        'task_id' => $filtering->taskId
                    ],
                ]);
            } else {
                $res = $client->get('http://34.150.31.105/wsES/e/filter_p', [
                    'headers' => [
                        'tk' => '7CLhp1FRE+k2sKsQrocbjL0zJzboZnZj'
                    ],
                    'query' => [
                        'task_id' => $filtering->taskId
                    ],
                ]);
            }
            $res = json_decode($res->getBody()->getContents(), true);
            if (isset($res['code']) && $res['code'] == 200) {
                $res = $res['result'];
                $filtering->taskId = $res['taskId'];
                $filtering->taskState = $res['taskState'];
                $filtering->taskTotal = $res['taskTotal'];
                $filtering->taskResultUrl = $res['taskResultUrl'];
                $filtering->taskProgress = $res['taskProgress'];
                $filtering->update_time = time();
                $filtering->save();
            }
        }
        return $this->json(200, '更新成功');

    }


    /**
     * @return array
     * @throws JSONException
     */
    public function restart()
    {
        $data = $_POST;
        if (empty($data['id'])) {
            throw new JSONException("请上传ID");
        }
        /** @var \App\Model\Filtering $filtering */
        $filtering = \App\Model\Filtering::query()->where('id', $data['id'])->first();
        if (!$filtering) {
            throw new JSONException("任务不存在");
        }
        $client = new Client();
        if ($filtering->mode == 'TG') {
            $res = $client->get('http://34.96.170.183/tgES/tgout/filter_p', [
                'headers' => [
                    'tk' => '7CLhp1FRE+k2sKsQrocbjL0zJzboZnZj'
                ],
                'query' => [
                    'task_id' => $filtering->taskId
                ],
            ]);
        } else {
            $res = $client->get('http://34.150.31.105/wsES/e/filter_p', [
                'headers' => [
                    'tk' => '7CLhp1FRE+k2sKsQrocbjL0zJzboZnZj'
                ],
                'query' => [
                    'task_id' => $filtering->taskId
                ],
            ]);
        }
        $res = json_decode($res->getBody()->getContents(), true);
        if (!isset($res['code']) || $res['code'] != 200) {
            throw new JSONException("记录更新失败");
        }
        $res = $res['result'];
        $filtering->taskId = $res['taskId'];
        $filtering->taskState = $res['taskState'];
        $filtering->taskTotal = $res['taskTotal'];
        $filtering->taskResultUrl = $res['taskResultUrl'];
        $filtering->taskProgress = $res['taskProgress'];
        $filtering->update_time = time();
        if (!$filtering->save()) {
            throw new JSONException("更新失败");
        }
        return $this->json(200, '更新成功');
    }

}