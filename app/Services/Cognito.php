<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Aws\Sdk;
use pmill\AwsCognito\CognitoClient;
use pmill\AwsCognito\Exception\CognitoResponseException;
use pmill\AwsCognito\Exception\UserNotFoundException;
use pmill\AwsCognito\Exception\ChallengeException;
use pmill\AwsCognito\Exception\PasswordResetRequiredException;

class Cognito extends CognitoClient
{
    private $password;
    private $username;
    private $brand_uuid;
    private $config;
    private $cognitoClient;

    /**
     * Cognito constructor.
     */
    public function __construct()
    {
        $this->config = config('aws-cognito-auth');
        $aws = new Sdk($this->config);
        $this->cognitoClient = $aws->createCognitoIdentityProvider();
        parent::__construct($this->cognitoClient);

        $this->setAppClientId($this->config['app_client_id']);
        $this->setAppClientSecret($this->config['app_client_secret']);
        $this->setRegion($this->config['region']);
        $this->setUserPoolId($this->config['user_pool_id']);
    }

    /**
     * @return bool
     */
    public function setCognitoUserIfNotExists()
    {
        try {
            if (!$this->checkUserAndGroup()) {
                $this->addUserToGroup($user['Username'], $this->config['group']);
            }
        } catch (UserNotFoundException $e) {
            return $this->setCognitoUser();
        } catch (\Exception $e) {
            Log::error('Cognito error: ', [$e->getMessage()]);
        }

        return false;
    }

    /**
     * @return bool
     * @throws CognitoResponseException
     * @throws UserNotFoundException
     */
    public function checkUserAndGroup()
    {
        $user = $this->getUser($this->username);

        foreach ($this->getUserGroups($user['Username']) as $groups) {
            if (isset($groups['Groups']) && $groups['Groups'] == $this->config['group']) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function setCognitoUser()
    {
        try {
            $sub = $this->registerUser($this->username, $this->password, [
                'custom:brand_uuid' => $this->brand_uuid
            ]);

            $this->addUserToGroup($sub, $this->config['group']);

            return true;
        } catch (\Exception $e) {
            Log::error('Cognito error: ', [$e->getMessage()]);
        }

        return false;
    }

    /**
     * @return array|string
     * @throws ChallengeException
     * @throws \Exception
     */
    public function login()
    {
        try {
            $authenticationResponse = $this->authenticate($this->username, $this->password);
        } catch (ChallengeException $e) {
            if ($e->getChallengeName() === CognitoClient::CHALLENGE_NEW_PASSWORD_REQUIRED) {
                $authenticationResponse = $this->respondToNewPasswordRequiredChallenge(
                    $this->username,
                    'password_new',
                    $e->getSession()
                );
            }
        } catch (PasswordResetRequiredException $e) {
            $authenticationResponse = "PASSWORD RESET REQUIRED";
        }

        return $authenticationResponse;
    }

    /**
     * @param string $username
     * @return AwsResult
     * @throws UserNotFoundException
     * @throws CognitoResponseException
     */
    public function getUser($username)
    {
        try {
            $response = $this->cognitoClient->adminGetUser([
                'Username'   => $username,
                'UserPoolId' => $this->userPoolId,
            ])->toArray();

            return $response;
        } catch (\Exception $e) {
            throw CognitoResponseException::createFromCognitoException($e);
        }
    }

    /**
     * @param string $username
     * @return Result
     * @throws UserNotFoundException
     * @throws CognitoResponseException
     */
    public function getUserGroups($username)
    {
        try {
            $response = $this->cognitoClient->adminListGroupsForUser([
                'Username'   => $username,
                'UserPoolId' => $this->userPoolId,
            ])->toArray();

            return $response;
        } catch (\Exception $e) {
            throw CognitoResponseException::createFromCognitoException($e);
        }
    }

    /**
     * @param $email
     */
    public function setUsername($email)
    {
        $this->username = $email;
    }

    /**
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param $brand_uuid
     */
    public function setUuid($brand_uuid)
    {
        $this->brand_uuid = $brand_uuid;
    }
}
