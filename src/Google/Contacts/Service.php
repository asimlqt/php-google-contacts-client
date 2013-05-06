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
    public function getAll($userEmail)
    {
        $serviceRequest = ServiceRequestFactory::getInstance();
        $serviceRequest->getRequest()->setEndpoint("{$userEmail}/full");
        $res = $serviceRequest->execute();
        return new ListFeed(json_decode($res, true));
    }

    /**
     * Fetches a single Contacts from google drive by id if you decide 
     * to store the id locally. This can help reduce api calls.
     * 
     * @param  string $id the id of the Contacts
     * 
     * @return \Google\Contacts\Contacts
     */
    /*
    public function getContactsById($id)
    {
        $this->request->setEndpoint('feeds/Contactss/private/full/'. $id);
        $res = $this->execute();
        return new Contacts($res);
    }*/
}