<?php

namespace Thinkstudeo\Textlocal\Traits;

use Thinkstudeo\Textlocal\Response;
use Thinkstudeo\Textlocal\PromotionalClient;
use Thinkstudeo\Textlocal\TransactionalClient;
use Thinkstudeo\Textlocal\Exceptions\InvalidInput;
use Thinkstudeo\Textlocal\Exceptions\ApiRequestFailure;

trait ResellerAccount
{
    /**
     * @return Response
     * @throws ApiRequestFailure
     */
    public function users()
    {
        return $this->request->post($this->requestUrl('get_users'), $this->params);
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $sender
     * @return Response
     * @throws InvalidInput
     */
    public function addUser($email, $password, $sender)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            throw InvalidInput::unacceptable("{$email} is not valid email address", 'INVALID EMAIL');
        }

        if (strlen($password) < 6)
        {
            throw InvalidInput::unacceptable('Password length is too short', 'SHORT PASSWORD');
        }

        if (strlen($sender) < 3 || strlen($sender) > 11)
        {
            throw InvalidInput::unacceptable('Sender length not within the range of 3-11 characters', 'INVALID SENDER LENGTH');
        }

        $this->addParam('user_email', $email);
        $this->addParam('user_password', $password);
        $this->addParam('sender', $sender);
        $this->addParam('name', 'Pandya Neerav');

        return $this->request->post($this->requestUrl('create_user'), $this->params);
    }

    /**
     * Update the user record.
     * Eg: $client->name('value')->editField('address', 'address value')->updateUser('userId');
     * @param number $userId
     * @return Response
     * @throws InvalidInput
     */
    public function updateUser($userId)
    {
        if (!is_numeric($userId))
        {
            throw InvalidInput::unacceptable("{$userId} is not valid user id", 'INVALID USER ID');
        }

        $this->addParam('user_id', $userId);

        return $this->request->post($this->requestUrl('update_user'), $this->params);
    }

    /**
     * Transfer credits to a sub account identified by the userId.
     *
     * @param number $credits
     * @param number $userId
     * @param string|null $email
     * @return Response
     * @throws InvalidInput|ApiRequestFailure
     */
    public function transferCredits($credits, $userId, $email = null)
    {
        if (!is_numeric($credits) || $credits <= 0)
        {
            throw InvalidInput::unacceptable('Invalid credits provided', 'INVALID CREDITS');
        }

        if (!is_numeric($userId))
        {
            throw InvalidInput::unacceptable("{$userId} is not valid user id", 'INVALID USER ID');
        }

        if (!is_null($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            throw InvalidInput::unacceptable("{$email} is not valid email address", 'INVALID EMAIL');
        }

        $this->addParam('credits', $credits);
        $this->addParam('user_id', $userId);
        $this->addParam('user_email', $email);

        return $this->request->post($this->requestUrl('transfer_credits'), $this->params);
    }

    /**
     * Set the request parameter user_id.
     *
     * @param number $userId
     * @return TransactionalClient|PromotionalClient|ResellerAccount
     * @throws TextlocalException
     */
    public function user($userId)
    {
        if (!is_numeric($userId))
        {
            throw TextlocalException::requestRespondedWithError("{$userId} is not valid user id", 'INVALID USER ID');
        }

        $this->addParam('user_id', $userId);

        return $this;
    }

    /**
     * Set the request parameter user_name.
     *
     * @param string $name
     * @return TransactionalClient|PromotionalClient|ResellerAccount
     * @throws InvalidInput
     */
    public function name($name)
    {
        if (!is_string($name))
        {
            throw InvalidInput::unacceptable('Invalid datatype for user name', 'INVALID DATATYPE');
        }

        $this->addParam('user_name', $name);

        return $this;
    }

    /**
     * Set the request parameter user_email.
     *
     * @param string $email
     * @return TransactionalClient|PromotionalClient|ResellerAccount
     * @throws InvalidInput
     */
    public function email($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            throw InvalidInput::unacceptable("{$email} is not valid email address", 'INVALID EMAIL');
        }

        $this->addParam('user_email', $email);

        return $this;
    }

    /**
     * Set the request parameter user_password.
     *
     * @param string $password
     * @return TransactionalClient|PromotionalClient|ResellerAccount
     * @throws InvalidInput
     */
    public function password($password)
    {
        if (strlen($password) < 6)
        {
            throw InvalidInput::unacceptable('Password length is too short', 'SHORT PASSWORD');
        }

        $this->addParam('user_password', $password);

        return $this;
    }

    /**
     * Set the request parameter sender.
     *
     * @param string $sender
     * @return TransactionalClient|PromotionalClient|ResellerAccount
     * @throws InvalidInput
     */
    public function sender($sender)
    {
        if (strlen($sender) < 3 || strlen($sender) > 11)
        {
            throw InvalidInput::unacceptable('Sender length not within the range of 3-11 characters', 'INVALID SENDER LENGTH');
        }

        $this->addParam('sender', $sender);

        return $this;
    }

    /**
     * Set the request parameter address|postcode|town|country|telephone|company.
     *
     * @param string $field
     * @param string $value
     * @return TransactionalClient|PromotionalClient|ResellerAccount
     * @throws InvalidInput
     */
    public function editField($field, $value)
    {
        if (!in_array($field, ['address', 'postcode', 'town', 'country', 'telephone', 'company']))
        {
            throw InvalidInput::unacceptable("{$field} is not an available update option", 'INVALID OPTION');
        }

        $this->addParam($field, $value);

        return $this;
    }

    /**
     * Activate the user.
     *
     * Command: $client->user('userId')->activate()
     *
     * @return Response
     * @throws ApiRequestFailure
     */
    public function activate()
    {
        $this->addParam('status', 1);

        return $this->request->post($this->requestUrl('update_user_status'), $this->params);
    }

    /**
     * Deactivate the user.
     *
     * Command: $client->user('userId')->deactivate()
     *
     * @return Response
     * @throws ApiRequestFailure
     */
    public function deactivate()
    {
        $this->addParam('status', 0);

        return $this->request->post($this->requestUrl('update_user_status'), $this->params);
    }

    /**
     * Generate an API KEY for user.
     *
     * Command: $client->user('userId')->generateApiKey()
     *
     * @param string|null $notes
     * @param string|null $ips
     * @return Response|TextlocalException
     */
    public function generateApiKey($notes = null, $ips = null)
    {
        //TODO: validate ip addresses and allow comma separated as well as array values for ip addresses
        $this->addParam('notes', $notes);
        $this->addParam('ip_addresses', $ips);

        return $this->request->post($this->requestUrl('create_user_apikey'), $this->params);
    }

    /**
     * Update an API KEY for user.
     *
     * Command: $client->user('userId')->updateApiKey('apiKey')
     *
     * @param $apiKey
     * @param null $notes
     * @param null $ips
     * @return Response
     * @throws ApiRequestFailure
     */
    public function updateApiKey($apiKey, $notes = null, $ips = null)
    {
        //TODO: validate ip addresses and allow comma separated as well as array values for ip addresses
        $this->addParam('user_api_key', $apiKey);
        $this->addParam('notes', $notes);
        $this->addParam('ip_addresses', $ips);

        return $this->request->post($this->requestUrl('update_user_apikey'), $this->params);
    }

    /**
     * Delete an API KEY for user.
     *
     * Command: $client->user('userId')->deleteApiKey('apiKey')
     *
     * @param $apiKey
     * @return Response
     * @throws ApiRequestFailure
     */
    public function deleteApiKey($apiKey)
    {
        $this->addParam('user_api_key', $apiKey);

        return $this->request->post($this->requestUrl('delete_user_apikey'), $this->params);
    }
}