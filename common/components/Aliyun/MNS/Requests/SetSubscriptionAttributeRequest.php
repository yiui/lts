<?php
namespace common\components\Aliyun\MNS\Requests;

use common\components\Aliyun\MNS\Constants;
use common\components\Aliyun\MNS\Requests\BaseRequest;
use common\components\Aliyun\MNS\Model\UpdateSubscriptionAttributes;

class SetSubscriptionAttributeRequest extends BaseRequest
{

    public function __construct(UpdateSubscriptionAttributes $attributes = NULL)
    {
        parent::__construct('put', 'topics/' . $attributes->getTopicName() . '/subscriptions/' . $attributes->getSubscriptionName() . '?metaoverride=true');

        if ($attributes == NULL)
        {
            $attributes = new UpdateSubscriptionAttributes();
        }

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
