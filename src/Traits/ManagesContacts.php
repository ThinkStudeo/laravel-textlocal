<?php

namespace Thinkstudeo\Textlocal\Traits;

use Thinkstudeo\Textlocal\Response;
use Thinkstudeo\Textlocal\Exceptions\ApiRequestFailure;

trait ManagesContacts
{
    /**
     * Determine whether a group identified by the given name exists.
     *
     * @param string $name
     * @return bool
     */
    public function groupExists($name)
    {
        foreach ($this->groups() as $group)
        {
            if ($group->name === $name)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Create a new contact group.
     *
     * @param string $name
     * @return Response
     * @throws ApiRequestFailure
     */
    public function createGroup($name)
    {
        $this->addParam('name', $name);
        $response = $this->request->post($this->requestUrl('create_group'), $this->params);

//        var_dump("ManagesContacts createGroup response");
//        var_dump($response);

        return $response;
    }

    /**
     * Delete the group identified by the given name or id.
     *
     * @param string|number $group
     * @return Response
     * @throws ApiRequestFailure
     */
    public function deleteGroup($group)
    {
        $id = is_numeric($group) ? $group : $this->getGroupId($group);
        $this->addParam('group_id', $id);

        $this->request->post($this->requestUrl('delete_group'), $this->params);
    }

    /**
     * Determine the id of the group, if exists, from the given group name.
     *
     * @param string $groupName
     * @return number
     * @throws ApiRequestFailure
     */
    protected function getGroupId($groupName)
    {
        $groups = $this->groups();

        foreach ($groups as $group)
        {
            if ($group->name === $groupName)
            {
                return intVal($group->id);
            }
        }

        throw ApiRequestFailure::requestRespondedWithError('Invalid Group name', 'NOGRP');
    }

    /**
     * Make an api request to get a list of all existing groups.
     *
     * @return Response
     * @throws ApiRequestFailure
     */
    public function groups()
    {
        $response = $this->request->post($this->requestUrl('get_groups'), $this->params);

        return $response->groups;
    }

    /**
     * Get a list of members limited to the number indicated by $limit from a group.
     * Start position can be defined to get contacts from a position onwards.
     *
     * @param int $group
     * @param int $limit
     * @param int $startPos
     * @return Response
     * @throws ApiRequestFailure
     */
    public function members($group = 5, $limit=25, $startPos = 0)
    {
        $id = is_numeric($group) ? $group : $this->getGroupId($group);
        $this->addParam('group_id', $id);
        $this->addParam('limit', $limit);
        $this->addParam('start', $startPos);
        $response = $this->request->post($this->requestUrl('get_contacts'), $this->params);

        return $response->contacts;
    }

    /**
     * Add only numbers to an existing group or default contacts group.
     *
     * @param array|string $numbers
     * @param int $group
     * @return Response
     * @throws ApiRequestFailure
     */
    public function addNumbers($numbers, $group = 5)
    {
        $this->addParam(
            'group_id',
            is_numeric($group) ? $group : $this->getGroupId($group)
        );

        $this->addParam(
            'numbers',
            implode(
                ',',
                is_string($numbers) ? explode(',', $numbers) : $numbers
            )
        );

        $response = $this->request->post($this->requestUrl('create_contacts'), $this->params);

        return $response;
    }

    /**
     * Add members with complete details like first_name, last_name, number etc
     * to an existing group or to the default contacts group.
     *
     * @param array $contacts
     * @param int $group
     * @return Response
     * @throws ApiRequestFailure
     */
    public function addMembers(array $contacts, $group = 5)
    {
        $this->addParam(
            'contacts',
            rawurlencode(json_encode($contacts))
        );
        $this->addParam(
            'group_id',
            is_numeric($group) ? $group : $this->getGroupId($group)
        );

        $response = $this->request->post($this->requestUrl('create_contacts_bulk'), $this->params);

        return $response;
    }

    /**
     * Remove a member identified by the number from a group.
     *
     * @param string|number $number
     * @param int $group
     * @return Response
     * @throws ApiRequestFailure
     */
    public function removeMember($number, $group = 5)
    {
        if (!isset($number))
        {
            throw ApiRequestFailure::requestRespondedWithError('Contact number is not provided.', 'NONUM');
        }

        $this->addParam(
            'group_id',
            is_numeric($group) ? $group : $this->getGroupId($group)
        );
        $this->addParam(
            'number',
            $number
        );

        $response = $this->request->post($this->requestUrl('delete_contact'), $this->params);

        return $response;
    }

    /**
     * Get all the members who have opted out.
     *
     * @return Response
     * @throws ApiRequestFailure
     */
    public function optOuts()
    {
        $response = $this->request->post($this->requestUrl('get_optouts'), $this->params);

        return $response;
    }
}
