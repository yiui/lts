<?php

/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="lts_api.de",
 *     basePath="/v2",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="接口文档",
 *     ),
 * )
 *
 */

/**
 * @SWG\Definition(
 *   @SWG\Xml(name="##default")
 * )
 */
class ApiResponse
{
    /**
     * @SWG\Property
     * @var int
     */
    public $code;
    /**
     * @SWG\Property
     * @var string
     */
    public $message;
}
