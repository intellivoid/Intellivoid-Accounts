<?php
    /**
     * ModularAPI HTTP Handler File
     *
     * This file determines if the User is making a valid request, and if the authentication method used is valid and correct
     */

    include_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'ModularAPI' . DIRECTORY_SEPARATOR . 'ModularAPI.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'authentication.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'generic_responses.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'request.php');

    $Configuration = verifyRequest();

    if($Configuration->API->Available == false)
    {
        unavailableError($Configuration->API->UnavailableMessage);
    }

    $AccessKey = null;
    $ModularAPI = new \ModularAPI\ModularAPI();

    if($Configuration->Policies->AuthenticatedRequired == true)
    {
        $AccessKey = verifyAuthentication($ModularAPI, $Configuration->Policies->ForceCertificate);

        if($AccessKey->Usage->expired() == true)
        {
            keyExpiredError();
        }
    }

    $Query = \ModularAPI\HTTP\Request::parseQuery();
    if($Configuration->moduleExists($Query->Module) == false)
    {
        invalidModuleError();
    }

    $Module = $Configuration->getModule($Query->Module);

    if($Query->RequestMethod == \ModularAPI\Abstracts\HTTP\RequestMethod::GET)
    {
        if($Module->GetMethodAllowed == false)
        {
            requestMethodNotAllowed($Query->RequestMethod);
        }
    }
    elseif($Query->RequestMethod == \ModularAPI\Abstracts\HTTP\RequestMethod::POST)
    {
        if($Module->PostMethodAllowed == false)
        {
            requestMethodNotAllowed($Query->RequestMethod);
        }
    }
    else
    {
        // Any other request method which isn't supported isn't allowed
        requestMethodNotAllowed($Query->RequestMethod);
    }

    if($Module->RequireAuthentication == true)
    {
        if($AccessKey == null)
        {
            $AccessKey = verifyAuthentication($ModularAPI, false);

            if($AccessKey->Usage->expired() == true)
            {
                keyExpiredError();
            }
        }

        if($AccessKey->Permissions->AllowAll == false)
        {
            if($AccessKey->Permissions->hasPermission($Module->Name) == false)
            {
                invalidPermissionError();
            }
        }

        if($Module->RequireUsage == true)
        {
            if($AccessKey->Usage->usageExceeded() == true)
            {
                usageExceededError();
            }
        }

        if($AccessKey->State == \ModularAPI\Abstracts\AccessKeyStatus::Suspended)
        {
            accessKeySuspended();
        }
    }

    try
    {
        $ModularAPI->AccessKeys()->trackUsage($AccessKey, $Module->RequireUsage);
    }
    catch(\ModularAPI\Exceptions\AccessKeyExpiredException $accessKeyExpiredException)
    {
        keyExpiredError();
    }
    catch(\ModularAPI\Exceptions\UsageExceededException $usageExceededException)
    {
        usageExceededError();
    }

    $SafeVersion = str_ireplace('/', '_', $Query->Version);
    $SafeVersion = str_ireplace('\\', '_', $SafeVersion);
    $ModuleFile = __DIR__ . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $SafeVersion . DIRECTORY_SEPARATOR . $Module->ScriptName . '.php';

    if(file_exists($ModuleFile) == false)
    {
        moduleScriptNotFoundError();
    }

    $ExecutionStart = microtime(true);
    $ExecutionEnd = null;
    $Response = null;
    $FatalError = false;
    $ExceptionDetails = null;
    $Parameters = [];

    try
    {
        $Parameters = ModularAPI\HTTP\Request::getParameters($Module->Parameters);
        /** @noinspection PhpIncludeInspection */
        include($ModuleFile);
        $Response = Module($AccessKey, $Parameters);
        $ExecutionEnd = microtime(true);
    }
    catch(\ModularAPI\Exceptions\MissingParameterException $missingParameterException)
    {
        $ExecutionEnd = microtime(true);
        missingParamerter($missingParameterException->ParamerterName);
    }
    catch(Exception $exception)
    {
        $ExecutionEnd = microtime(true);
        $Response = new \ModularAPI\Objects\Response();
        $Response->ResponseCode = ModularAPI\Abstracts\HTTP\ResponseCode\ServerError::_500;
        $Response->ResponseType = \ModularAPI\Abstracts\HTTP\ContentType::application . '/' . \ModularAPI\Abstracts\HTTP\FileType::json;
        $Response->Content = array(
            'status' => false,
            'code' => \ModularAPI\Abstracts\HTTP\ResponseCode\ServerError::_500,
            'message' => 'Internal Server Error',
            'exception_code' => $exception->getCode()
        );
        $ExceptionDetails = new \ModularAPI\Objects\ExceptionDetails();
        $ExceptionDetails->Line = $exception->getLine();
        $ExceptionDetails->File = $exception->getFile();
        $ExceptionDetails->Message = $exception->getMessage();
        $ExceptionDetails->ExceptionCode = $exception->getCode();
    }

    $ReferenceID = $ModularAPI->RequestsLog()->recordRequest(
        getClientIP(),
        ($ExecutionEnd - $ExecutionStart),
        $Query,
        $Parameters,
        $Response,
        ModularAPI\HTTP\Request::parseAuthentication(),
        $AccessKey->PublicID,
        $FatalError,
        $ExceptionDetails
    );

    $Response->executeResponse($ReferenceID);