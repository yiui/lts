<?php
namespace common\components\Aliyun\MNS\Requests;

use common\components\Aliyun\MNS\Constants;
use common\components\Aliyun\MNS\Requests\BaseRequest;
use common\components\Aliyun\MNS\Model\TopicAttributes;

class CreateTopicRequest extends BaseRequest
{
    private $topicName;
    private $attributes;

    public function __construct($topicName, TopicAttributes $attributes = NULL)
    {
        parent::__construct('put', 'topics/' . $topicName);

        if ($attributes == NULL)
        {
            $attributes = new TopicAttributes;
        }

        $this->topicName = $topicName;
        $this->attributes = $attributes;
    }

    public function getTopicName()
    {
        return $this->topicName;
    }

    public function getTopicAttributes()
    {
        return $this->attributes;
    }

    public function generateBody()
    {
        $xmlWriter = new \XMLWriter;
        $xmlWriter->openMemory();
        $xmlWriter->startDocument("1.0", "UTF-8");
        $xmlWriter->startElementNS(NULL, "Topic", Constants::MNS_XML_NAMESPACE);
        $this->attributes->writeXML($xmlWriter);
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
