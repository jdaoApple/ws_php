<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $mode
 * @property string $userId
 * @property string $title
 * @property string $type
 * @property string $txt_file
 * @property string $taskId
 * @property string $taskState
 * @property string $taskTotal
 * @property string $taskProgress
 * @property string $taskResultUrl
 * @property string $create_time
 * @property string $update_time
 * @property string $money
 */
class Filtering extends Model
{
    /**
     * @var string
     */
    protected $table = 'filtering';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $casts = ['mode' => 'string'];

    public function user(): ?\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, "id", "userId");
    }
}