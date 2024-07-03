<?php
namespace Avency\Customerdashboard\Site\Trait;

use Neos\Flow\Mvc\Exception\StopActionException;

trait ResponseTrait
{
    /**
     * Returns a message with a specific status code
     *
     * @param int $statusCode the status code of the response
     * @param string|array|null $message the message that should be set as the content
     * @param int|null $code error code
     * @param string|null $errorMessage the error message
     * @return void
     * @throws StopActionException
     */
    protected function sendResponse(int $statusCode, string|array|null $message, ?int $code = null, ?string $errorMessage = null): void
    {
        $this->response->setContentType('application/json');
        $this->response->setStatusCode($statusCode);

        $result = [];
        $result['content'] = $message ?: '';

        if ($code) {
            $result['code'] = $code;
        }
        if ($errorMessage) {
            $result['errorMessage'] = $errorMessage;
        }

        $this->response->setContent(
            json_encode($result)
        );

        throw new StopActionException();
    }

    /**
     * Returns a message with a specific status code
     *
     * @param int $statusCode the status code of the response
     * @param string|array|null $message the message that should be set as the content
     * @return void
     * @throws StopActionException
     */
    protected function sendContentResponse(int $statusCode, string|array|null $message): void
    {
        $this->response->setContentType('application/json');
        $this->response->setStatusCode($statusCode);

        $result = $message ?: '';

        $this->response->setContent(
            json_encode($result)
        );

        throw new StopActionException();
    }
}
