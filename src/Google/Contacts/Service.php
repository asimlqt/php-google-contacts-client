<?php
/**
 * Copyright 2013 Asim Liaquat
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Google\Contacts;

/**
 * Contacts Service.
 *
 * @package    Google
 * @subpackage Contacts
 * @version    0.1
 * @author     Asim Liaquat <asimlqt22@gmail.com>
 */
class Service
{
    /**
     * Fetches a list of spreadhsheet Contactss from google drive.
     * 
     * @return \Google\Contacts\ContactsFeed
     */
    public function getAllContacts()
    {
        $serviceRequest = ServiceRequestFactory::getInstance();
        $serviceRequest->getRequest()->setEndpoint("default/full");
        $serviceRequest->getRequest()->addQueryParam('max-results', 1000000);
        $serviceRequest->getRequest()->addQueryParam('alt', 'json');
        $res = $serviceRequest->execute();
        return new ContactFeed(json_decode($res, true));
    }

    /**
     * Search contacts
     * 
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function searchContacts($query)
    {
        $serviceRequest = ServiceRequestFactory::getInstance();
        $serviceRequest->getRequest()->setEndpoint("default/full");
        $serviceRequest->getRequest()->addQueryParam('max-results', 1000000);
        $serviceRequest->getRequest()->addQueryParam('alt', 'json');
        $serviceRequest->getRequest()->addQueryParam('q', $query);
        $res = $serviceRequest->execute();
        return new ContactFeed(json_decode($res, true));   
    }

    /**
     * Fetches a single Contact. The contact id can either be a complete
     * url for the contact e.g. the id of the contact entry or it can
     * simply be the actual id string.
     * 
     * @param string $contactId contact id on its own or a coplete url
     * 
     * @return \Google\Contacts\Contacts
     *
     * @throws \Google\Contacts\Exception if contact not found
     */
    public function getContact($contactId)
    {
        $serviceRequest = ServiceRequestFactory::getInstance();
        $serviceRequest->getRequest()->addQueryParam('alt', 'json');
        if(substr($contactId, 0, 4) === 'http') {
            $serviceRequest->getRequest()->setFullUrl($contactId);
        } else {
            $serviceRequest->getRequest()->setEndpoint("default/full/{$contactId}");
        }
        $res = json_decode($serviceRequest->execute(), true);
        if(isset($res['entry']))
            return new Entry($res['entry']);
        else
            throw new Exception('Contact not found');
    }

    /**
     * Fetches a list of spreadhsheet Contactss from google drive.
     * 
     * @return \Google\Contacts\ContactsFeed
     */
    public function getAllGroups()
    {
        $serviceRequest = ServiceRequestFactory::getInstance();
        $serviceRequest->getRequest()->setEndpoint("default/full");
        $serviceRequest->getRequest()->setFeedType('groups');
        $serviceRequest->getRequest()->addQueryParam('max-results', 1000000);
        $serviceRequest->getRequest()->addQueryParam('alt', 'json');
        $res = $serviceRequest->execute();
        return new GroupFeed(json_decode($res, true));
    }

    public function getGroup($groupId)
    {
        $serviceRequest = ServiceRequestFactory::getInstance();
        $serviceRequest->getRequest()->setEndpoint("default/full/{$groupId}");
        $serviceRequest->getRequest()->setFeedType('groups');
        $serviceRequest->getRequest()->addQueryParam('alt', 'json');
        $res = json_decode($serviceRequest->execute(), true);

        if(isset($res['entry']))
            return new GroupEntry($res['entry']);
        else
            throw new Exception('Group not found');
    }
}