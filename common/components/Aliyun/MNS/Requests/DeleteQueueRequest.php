<?php
namespace common\components\Aliyun\MNS\Requests;

use common\components\Aliyun\MNS\Constants;
use common\components\Aliyun\MNS\Requests\BaseRequest;
use common\components\Aliyun\MNS\Model\QueueAttributes;

class DeleteQueueRequest extends BaseRequest
{
    private $queueName;

    public function __construct($queueName)
    {
        parent::__construct('delete', 'queues/' . $queueName);
        $this->queueName = $queueName;
    }

    public function getQueueName()
    {
        return $this->queueName;
    }

    public function generateBody()
    {
        return NULL;
    }

    public function generateQueryString()
    {
        return NULL;
    }
}
?>
