<?php
namespace common\components\Aliyun\MNS\Requests;

use common\components\Aliyun\MNS\Requests\BaseRequest;

class GetTopicAttributeRequest extends BaseRequest
{
    private $topicName;

    public function __construct($topicName)
    {
        parent::__construct('get', 'topics/' . $topicName);

        $this->topicName = $topicName;
    }

    public function getTopicName()
    {
        return $this->topicName;
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
