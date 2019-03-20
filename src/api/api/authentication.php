<?php
    function verifyAuthentication(\ModularAPI\ModularAPI $modularAPI, bool $forceCertificate = false): \ModularAPI\Objects\AccessKey
    {
        $Authentication = ModularAPI\HTTP\Request::parseAuthentication();

        if($Authentication->Type == \ModularAPI\Abstracts\AuthenticationType::None)
        {
            authenticationRequiredError();
            return null;
        }

        if($forceCertificate == true)
        {
            if($Authentication->Type !== \ModularAPI\Abstracts\AuthenticationType::Certificate)
            {
                certificateRequiredError();
            }

            try
            {
                return $modularAPI->AccessKeys()->verifyCertificate($Authentication->Certificate);
            }
            catch(Exception $exception)
            {
                invalidAuthenticationError();
            }
        }

        if($Authentication->Type == \ModularAPI\Abstracts\AuthenticationType::Certificate)
        {
            try
            {
                return $modularAPI->AccessKeys()->verifyCertificate($Authentication->Certificate);
            }
            catch(Exception $exception)
            {
                invalidAuthenticationError();
            }
        }

        if($Authentication->Type == \ModularAPI\Abstracts\AuthenticationType::APIKey)
        {
            try
            {
                return $modularAPI->AccessKeys()->verifyAPIKey($Authentication->Key);
            }
            catch(Exception $exception)
            {
                invalidAuthenticationError();
            }
        }

        invalidAuthenticationError();
        return null;
    }
