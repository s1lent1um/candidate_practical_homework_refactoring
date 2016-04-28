<?php

namespace Language\api;

use Language\ApiCall;

class Handler
{
    public $target;
    public $mode;

    public function __construct($target, $mode)
    {
        $this->target = $target;
        $this->mode = $mode;
    }

    /**
     * @param $system
     * @return SystemHandler
     */
    public function getSystemHandler($system)
    {
        return new SystemHandler($this, $system);
    }
    
    /**
     * @param $system
     * @param $action
     * @param $args

     * @return array|null
     * @throws ExecutionException
     * @throws UsageException
     */
    public function call($system, $action, $args)
    {
        $response = ApiCall::call(
            $this->target,
            $this->mode,
            ['system' => $system, 'action' => $action],
            $args
        );

        $this->validateResponse($response);
        return $response['data'];
    }

    /**
     * @param $response
     * @throws ExecutionException
     * @throws UsageException
     */
    protected function validateResponse($response)
    {
        // Error during the api call.
        if ($response === false || !isset($response['status'])) {
            throw new ExecutionException('Error during the api call');
        }
        // Wrong content.
        if ($unset = array_diff(['status', 'data'], array_keys($response))) {
            throw new UsageException('Malformed response. Fields not set: "' . implode('", "', $unset) . '"');
        }
        // Wrong response.
        if ($response['status'] != 'OK') {
            throw new UsageException('Wrong response: '
                . (!empty($response['error_type']) ? 'Type(' . $response['error_type'] . ') ' : '')
                . (!empty($response['error_code']) ? 'Code(' . $response['error_code'] . ') ' : '')
                . ((string)$response['data']));
        }
        // Wrong content.
        if ($response['data'] === false) {
            throw new UsageException('Wrong content!');
        }
    }
}
