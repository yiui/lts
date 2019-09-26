<?php
namespace common\components\Aliyun\MNS\Requests;

use common\components\Aliyun\MNS\Constants;
use common\components\Aliyun\MNS\Requests\BaseRequest;
use common\components\Aliyun\MNS\Traits\MessagePropertiesForPublish;

class PublishMessageRequest extends BaseRequest
{
    use MessagePropertiesForPublish;

    private $topicName;

    public function __construct($messageBody, $messageAttributes = NULL)
    {
        parent::__construct('post', NULL);

        $this->topicName = NULL;
        $this->messageBody = $messageBody;
        $this->messageAttributes = $messageAttributes;
    }

    public function setTopicName($topicName)
    {
        $this->topicName = $topicName;
        $this->resourcePath = 'topics/' . $topicName . '/messages';
    }

    public function getTopicName()
    {
        return $this->topicName;
    }

    public function generateBody()
    {
        $xmlWriter = new \XMLWriter;
        $xmlWriter->openMemory();
        $xmlWriter->startDocument("1.0", "UTF-8");
        $xmlWriter->startElementNS(NULL, "Message", Constants::MNS_XML_NAMESPACE);
        $this->writeMessagePropertiesForPublishXML($xmlWriter);
        $xmlWriter->endElement();
        $xmlWriter->endDocument();
        return $xmlWriter->outputMemory();
    }

    public function generateQueryString()
    {
        return NULL;
    }
}
?>