<?php
    function invalidResourceError()
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_404,
            'message' => 'The requested resource is invalid/unavailable'
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_404);
        exit();
    }

    function invalidModuleError()
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_404,
            'message' => 'The requested resource/module is invalid or unavailable'
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_404);
        exit();
    }

    function invalidConfigurationError()
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ServerError::_500,
            'message' => 'The sever does not have a valid configuration file, if you are the administrator then please consult the documentation'
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ServerError::_500);
        exit();
    }

    function invalidVersionError()
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_400,
            'message' => 'The API Version is not supported by the server'
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_400);
        exit();
    }

    function authenticationRequiredError()
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_401,
            'message' => 'Authentication is required'
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_401);
        exit();
    }

    function certificateRequiredError()
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_401,
            'message' => 'A Certificate is required for authentication'
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_401);
        exit();
    }

    function invalidAuthenticationError()
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_401,
            'message' => 'Incorrect Authentication'
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_401);
        exit();
    }

    function invalidPermissionError()
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_403,
            'message' => 'You don\'t have the required permissions to use this API Module'
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_403);
        exit();
    }

    function unavailableError(string $Message)
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ServerError::_503,
            'message' => $Message
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ServerError::_503);
        exit();
    }

    function keyExpiredError()
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_403,
            'message' => 'The Key/Certificate has expired'
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_403);
        exit();
    }

    function usageExceededError()
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_429,
            'message' => 'Usage limit has exceeded'
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_429);
        exit();
    }

    function moduleScriptNotFoundError()
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ServerError::_500,
            'message' => 'The module was not found on the server'
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ServerError::_500);
        exit();
    }

    function missingParamerter(string $paramerter_name)
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_400,
            'message' => 'The paramerter "' . $paramerter_name . '" is missing'
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_400);
        exit();
    }


    function internalServerError(Exception $exception)
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ServerError::_500,
            'message' => 'Internal Server Error',
            'exception_code' => $exception->getCode()
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ServerError::_500);
        exit();
    }

    function requestMethodNotAllowed(string $requestMethod)
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_405,
            'message' => 'The request method "' . $requestMethod . '" is not allowed'
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_405);
        exit();
    }

    function accessKeySuspended()
    {
        $Payload = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_403,
            'message' => 'Your access key has been suspended'
        );
        \ModularAPI\HTTP\Response::json($Payload, \ModularAPI\Abstracts\HTTP\ResponseCode\ClientError::_403);
        exit();
    }