<?php
namespace common\components\Aliyun\MNS\Requests;

use common\components\Aliyun\MNS\Constants;
use common\components\Aliyun\MNS\Requests\BaseRequest;
use common\components\Aliyun\MNS\Model\SubscriptionAttributes;

class SubscribeRequest extends BaseRequest
{
    private $attributes;

    public function __construct(SubscriptionAttributes $attributes)
    {
        parent::__construct('put', 'topics/' . $attributes->getTopicName() . '/subscriptions/' . $attributes->getSubscriptionName());

        $this->attributes = $attributes;
    }

    public function getSubscriptionAttributes()
    {
        return $this->attributes;
    }

    public function generateBody()
    {
        $xmlWriter = new \XMLWriter;
        $xmlWriter->openMemory();
        $xmlWriter->startDocument("1.0", "UTF-8");
        $xmlWriter->startElementNS(NULL, "Subscription", Constants::MNS_XML_NAMESPACE);
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
