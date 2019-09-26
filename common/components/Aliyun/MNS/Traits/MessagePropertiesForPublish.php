<?php
namespace common\components\Aliyun\MNS\Traits;

use common\components\Aliyun\MNS\Constants;
use common\components\Aliyun\MNS\Model\MessageAttributes;

trait MessagePropertiesForPublish
{
    public $messageBody;
    public $messageAttributes;

    public function getMessageBody()
    {
        return $this->messageBody;
    }

    public function setMessageBody($messageBody)
    {
        $this->messageBody = $messageBody;
    }

    public function getMessageAttributes()
    {
        return $this->messageAttributes;
    }

    public function setMessageAttributes($messageAttributes)
    {
        $this->messageAttributes = $messageAttributes;
    }

    public function writeMessagePropertiesForPublishXML(\XMLWriter $xmlWriter)
    {
        if ($this->messageBody != NULL)
        {
            $xmlWriter->writeElement(Constants::MESSAGE_BODY, $this->messageBody);
        }
        if ($this->messageAttributes !== NULL)
        {
            $this->messageAttributes->writeXML($xmlWriter);
        }
    }
}

?>
