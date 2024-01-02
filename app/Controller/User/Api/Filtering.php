<?php
declare(strict_types=1);

namespace App\Controller\User\Api;


use App\Controller\Base\API\User;
use App\Entity\QueryTemplateEntity;
use App\Interceptor\UserSession;
use App\Interceptor\Waf;
use App\Model\Config;
use App\Model\Filtering as FilteringModel;
use App\Service\Query;
use GuzzleHttp\Client;
use Illuminate\Database\Capsule\Manager as DB;
use Kernel\Annotation\Inject;
use Kernel\Annotation\Interceptor;
use Kernel\Exception\JSONException;

#[Interceptor([Waf::class, UserSession::class], Interceptor::TYPE_API)]
class Filtering extends User
{

    #[Inject]
    private \App\Service\Upload $upload;

    #[Inject]
    private Query $query;

    /**
     * @return array
     */
    public function data(): array
    {
        $map = $_POST;
        $map['equal-userId'] = $this->getUser()->id;
        $queryTemplateEntity = new QueryTemplateEntity();
        $queryTemplateEntity->setModel(FilteringModel::class);
        $queryTemplateEntity->setLimit((int)$_POST['limit']);
        $queryTemplateEntity->setPage((int)$_POST['page']);
        $queryTemplateEntity->setPaginate(true);
        $queryTemplateEntity->setWhere($map);
        $data = $this->query->findTemplateAll($queryTemplateEntity)->toArray();
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

        }
        $json = $this->json(200, null, $data['data']);
        $json['count'] = $data['total'];
        return $json;
    }


    /**
     * 文件上传
     * @return array
     * @throws JSONException
     */
    public function handle(): array
    {
        $userId = $this->getUser()->id;

        if (!isset($_FILES['file'])) {
            throw new JSONException("请选择文件");
        }
        $handle = $this->upload->handle($_FILES['file'], BASE_PATH . '/assets/cache/user/' . $userId . '/txt', ['txt'], 1024000);
        if (!is_array($handle)) {
            throw new JSONException($handle);
        }
        return $this->json(200, '上传成功', ['path' => '/assets/cache/user/' . $userId . '/txt/' . $handle['new_name']]);
    }

    /**
     * @return array
     * @throws JSONException
     */
    public function save(): array
    {
        $userId = $this->getUser()->id;
        $data = $_POST;
        if (empty($data['title'])) {
            throw new JSONException("请输入任务名称");
        }
        if (empty($data['mode']) || !in_array($data['mode'], ['TG', 'WS'])) {
            throw new JSONException("请选择筛选类型");
        }


        if ($data['mode'] == 'TG') {
            if (empty($data['txt_file'])) {
                throw new JSONException("请上传文件");
            }
        } else {
            if (empty($data['type'])) {
                throw new JSONException("请选择筛选类型");
            }
            if (!in_array($data['type'], ['filterNumber', 'filterActive'])) {
                throw new JSONException("筛选类型错误");
            }
            if ($data['delivery_way'] == 1) {
                if ($data['number_start'] < 1) {
                    throw new JSONException("开始数字错误");
                }
                if ($data['number_incremental'] < 1) {
                    throw new JSONException("递增数字错误");
                }
                $numberStart = str_split($data['number_start']);
                $numberStart = array_reverse($numberStart);
                $numberStop = [];
                $isReplace = true;
                foreach ($numberStart as $v) {
                    if ($v > 0) {
                        $isReplace = false;
                    }
                    if ($isReplace && $v == 0) {
                        $numberStop[] = 9;
                    } else {
                        $numberStop[] = $v;
                    }
                }
                $numberStop = implode('', array_reverse($numberStop));
                $data['txt_file'] = "/assets/cache/user/{$userId}/txt/" . date('YmdHis') . rand() . ".txt";
                //生成txt文件
                $str = '';
                for ($i = $data['number_start']; $i < $numberStop; $i += (int)$data['number_incremental']) {
                    $str .= $i . PHP_EOL;
                }
                $open = fopen(BASE_PATH . $data['txt_file'], "w+");
                fwrite($open, $str);
                fclose($open);
            } else {
                if (empty($data['txt_file'])) {
                    throw new JSONException("请上传文件");
                }
            }
        }

        //获取文件行数
        $file_path = BASE_PATH . $data['txt_file']; //文件路径
        $line = 0; //初始化行数
        ////打开文件
        $fp = fopen($file_path, 'r') or die("open file failure!");
        if ($fp) {
            //获取文件的一行内容，注意：需要php5才支持该函数；
            while (stream_get_line($fp, 8192, "\n")) {
                $line++;
            }
            fclose($fp);//关闭文件
        }
        if ($line == 0) {
            throw new JSONException("文件没有内容");
        }
        if ($line > 100000) {
            throw new JSONException("文件不能大余10万行");
        }
        //事务
        Db::transaction(function () use ($data, $userId, $line) {

            $balance = $this->getUser()->balance;
            //获取单价
            $price = Config::get($data['mode'] == 'TG' ? 'filtering_tg' : 'filtering_ws');
            if (empty($price)) {
                throw new JSONException("系统没有设置单价");
            }
            $money = floatval(bcmul((string)$line, $price, 2));
            $min = Config::get($data['mode'] == 'TG' ? 'filtering_tg_min' : 'filtering_ws_min');
            $money = max($money, $min);
            if (empty($money)) {
                throw new JSONException("系统没有设置价格");
            }
            if ($balance < $money) {
                throw new JSONException("您的积分不足,请充值");
            } else {
                \App\Model\Bill::create($this->getUser(), floatval($money), \App\Model\Bill::TYPE_SUB, "购买筛选");
            }
            $model = new FilteringModel();
            $model->userId = $userId;
            $model->mode = $data['mode'];
            $model->title = $data['title'];
            $model->txt_file = $data['txt_file'];
            $model->create_time = time();
            if ($data['mode'] == 'WS') {
                $model->type = $data['type'];
            }
            $model->money = $money;
            if (!$model->save()) {
                throw new JSONException("记录保存失败");
            }
            $client = new Client();
            if ($data['mode'] == 'TG') {
                $res = $client->post('http://34.96.170.183/tgES/tgout/filter_d', [
                    'headers' => [
                        'tk' => '7CLhp1FRE+k2sKsQrocbjL0zJzboZnZj'
                    ],
                    'multipart' => [
                        [
                            'name' => 'file',
                            'contents' => fopen(BASE_PATH . $data['txt_file'], 'r'),
                        ], [
                            'name' => 'describe',
                            'contents' => $data['title'],
                        ]
                    ],
                ]);
            } else {
                $res = $client->post('http://34.150.31.105/wsES/e/filter_d', [
                    'headers' => [
                        'tk' => '7CLhp1FRE+k2sKsQrocbjL0zJzboZnZj'
                    ],
                    'multipart' => [
                        [
                            'name' => 'file',
                            'contents' => fopen(BASE_PATH . $data['txt_file'], 'r'),
                        ], [
                            'name' => 'describe',
                            'contents' => $data['title'],
                        ], [
                            'name' => 'platform',
                            'contents' => 'ws',
                        ], [
                            'name' => 'filter_type',
                            'contents' => $data['type'],
                        ]
                    ],
                ]);
            }
            $res = json_decode($res->getBody()->getContents(), true);
            if (!isset($res['code']) || $res['code'] != 200) {
                throw new JSONException("记录提交失败");
            }
            $res = $res['result'];
            $model->taskId = $res['taskId'];
            $model->taskState = $res['taskState'];
            $model->taskTotal = $res['taskTotal'];
            $model->taskResultUrl = $res['taskResultUrl'];
            $model->taskProgress = $res['taskProgress'];
            $model->save();
        });
        return $this->json(200, "任务新增成功");
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