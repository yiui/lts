<?php
namespace common\components\Aliyun\MNS;

use common\components\Aliyun\MNS\Exception\MnsException;
use common\components\Aliyun\MNS\Responses\BaseResponse;

class AsyncCallback
{
    protected $succeedCallback;
    protected $failedCallback;

    public function __construct(callable $succeedCallback, callable $failedCallback)
    {
        $this->succeedCallback = $succeedCallback;
        $this->failedCallback = $failedCallback;
    }

    public function onSucceed(BaseResponse $result)
    {
        return call_user_func($this->succeedCallback, $result);
    }

    public function onFailed(MnsException $e)
    {
        return call_user_func($this->failedCallback, $e);
    }
}

?>
