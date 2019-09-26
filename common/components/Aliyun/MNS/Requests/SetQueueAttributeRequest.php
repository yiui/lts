<?php
namespace common\components\Aliyun\MNS\Requests;

use common\components\Aliyun\MNS\Constants;
use common\components\Aliyun\MNS\Requests\BaseRequest;
use common\components\Aliyun\MNS\Model\QueueAttributes;

class SetQueueAttributeRequest extends BaseRequest
{
    private $queueName;
    private $attributes;

    public function __construct($queueName, QueueAttributes $attributes = NULL)
    {
        parent::__construct('put', 'queues/' . $queueName . '?metaoverride=true');

        if ($attributes == NULL)
        {
            $attributes = new QueueAttributes;
        }

        $this->queueName = $queueName;
        $this->attributes = $attributes;
    }

    public function getQueueName()
    {
        return $this->queueName;
    }

    public function getQueueAttributes()
    {
        return $this->attributes;
    }

    public function generateBody()
    {
        $xmlWriter = new \XMLWriter;
        $xmlWriter->openMemory();
        $xmlWriter->startDocument("1.0", "UTF-8");
        $xmlWriter->startElementNS(NULL, "Queue", Constants::MNS_XML_NAMESPACE);
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
