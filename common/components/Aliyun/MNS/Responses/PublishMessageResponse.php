<?php
namespace common\components\Aliyun\MNS\Responses;

use common\components\Aliyun\MNS\Constants;
use common\components\Aliyun\MNS\Exception\MnsException;
use common\components\Aliyun\MNS\Exception\TopicNotExistException;
use common\components\Aliyun\MNS\Exception\InvalidArgumentException;
use common\components\Aliyun\MNS\Exception\MalformedXMLException;
use common\components\Aliyun\MNS\Responses\BaseResponse;
use common\components\Aliyun\MNS\Common\XMLParser;
use common\components\Aliyun\MNS\Traits\MessageIdAndMD5;

class PublishMessageResponse extends BaseResponse
{
    use MessageIdAndMD5;

    public function __construct()
    {
    }

    public function parseResponse($statusCode, $content)
    {
        $this->statusCode = $statusCode;
        if ($statusCode == 201) {
            $this->succeed = TRUE;
        } else {
            $this->parseErrorResponse($statusCode, $content);
        }

        $xmlReader = $this->loadXmlContent($content);
        try {
            $this->readMessageIdAndMD5XML($xmlReader);
        } catch (\Exception $e) {
            throw new MnsException($statusCode, $e->getMessage(), $e);
        } catch (\Throwable $t) {
            throw new MnsException($statusCode, $t->getMessage());
        }

    }

    public function parseErrorResponse($statusCode, $content, MnsException $exception = NULL)
    {
        $this->succeed = FALSE;
        $xmlReader = $this->loadXmlContent($content);
        try {
            $result = XMLParser::parseNormalError($xmlReader);
            if ($result['Code'] == Constants::TOPIC_NOT_EXIST)
            {
                throw new TopicNotExistException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }
            if ($result['Code'] == Constants::INVALID_ARGUMENT)
            {
                throw new InvalidArgumentException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }
            if ($result['Code'] == Constants::MALFORMED_XML)
            {
                throw new MalformedXMLException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }
            throw new MnsException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
        } catch (\Exception $e) {
            if ($exception != NULL) {
                throw $exception;
            } elseif($e instanceof MnsException) {
                throw $e;
            } else {
                throw new MnsException($statusCode, $e->getMessage());
            }
        } catch (\Throwable $t) {
            throw new MnsException($statusCode, $t->getMessage());
        }
    }
}

?>
