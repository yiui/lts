<?php
namespace common\components\Aliyun\MNS\Responses;

use common\components\Aliyun\MNS\Constants;
use common\components\Aliyun\MNS\Model\SubscriptionAttributes;
use common\components\Aliyun\MNS\Exception\MnsException;
use common\components\Aliyun\MNS\Exception\SubscriptionNotExistException;
use common\components\Aliyun\MNS\Responses\BaseResponse;
use common\components\Aliyun\MNS\Common\XMLParser;

class GetSubscriptionAttributeResponse extends BaseResponse
{
    private $attributes;

    public function __construct()
    {
        $this->attributes = NULL;
    }

    public function getSubscriptionAttributes()
    {
        return $this->attributes;
    }

    public function parseResponse($statusCode, $content)
    {
        $this->statusCode = $statusCode;
        if ($statusCode == 200)
        {
            $this->succeed = TRUE;
        }
        else
        {
            $this->parseErrorResponse($statusCode, $content);
        }

        $xmlReader = $this->loadXmlContent($content);

        try
        {
            $this->attributes = SubscriptionAttributes::fromXML($xmlReader);
        }
        catch (\Exception $e)
        {
            throw new MnsException($statusCode, $e->getMessage(), $e);
        }
        catch (\Throwable $t)
        {
            throw new MnsException($statusCode, $t->getMessage());
        }
    }

    public function parseErrorResponse($statusCode, $content, MnsException $exception = NULL)
    {
        $this->succeed = FALSE;
        $xmlReader = $this->loadXmlContent($content);

        try
        {
            $result = XMLParser::parseNormalError($xmlReader);
            if ($result['Code'] == Constants::SUBSCRIPTION_NOT_EXIST)
            {
                throw new SubscriptionNotExistException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }
            throw new MnsException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
        }
        catch (\Exception $e)
        {
            if ($exception != NULL)
            {
                throw $exception;
            }
            elseif ($e instanceof MnsException)
            {
                throw $e;
            }
            else
            {
                throw new MnsException($statusCode, $e->getMessage());
            }
        }
        catch (\Throwable $t)
        {
            throw new MnsException($statusCode, $t->getMessage());
        }
    }
}
?>
