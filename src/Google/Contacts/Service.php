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
        $serviceRequest->getRequest()->addQueryParam('max-results', 1000000);
        $serviceRequest->getRequest()->addQueryParam('alt', 'json');
        $res = $serviceRequest->execute();
        return new ListFeed(json_decode($res, true));
    }

    public function createContact($userEmail, $data)
    {

        $entry = "
            <atom:entry xmlns:atom='http://www.w3.org/2005/Atom'
                xmlns:gd='http://schemas.google.com/g/2005'>
              <gd:name>
                 <gd:givenName>{firstname}</gd:givenName>
                 <gd:familyName>{lastname}</gd:familyName>
                 <gd:fullName>{fullname}</gd:fullName>
              </gd:name>
              <gd:email rel='http://schemas.google.com/g/2005#work'
                primary='true'
                address='{email}' displayName='{fullname}'/>
            </atom:entry>
        ";
        
        $entry = str_replace("\n", '', $entry);
        $entry = preg_replace('/\s{2,}/', ' ', $entry);
        
        if(!isset($data['firstname']) || !isset($data['lastname'])) {
            throw new Exception('Must supply contact firstname and lastname');
        }
        
        $name = $data['firstname'] . ' ' . $data['lastname'];
        $entry = str_replace('{firstname}', $data['firstname'], $entry);
        $entry = str_replace('{lastname}', $data['lastname'], $entry);
        $entry = str_replace('{fullname}', $name, $entry);
        $entry = str_replace('{email}', $data['email'], $entry);

        //echo $entry;exit;
        
        $serviceRequest = ServiceRequestFactory::getInstance();
        $request = $serviceRequest->getRequest(); /* @var $request \Google\Contacts\Request */
        $request->setEndpoint("{$userEmail}/full");
        $request->setPost($entry);
        $request->setMethod('POST');
        $request->setHeaders(array('Content-Type' => 'application/atom+xml; charset=UTF-8; type=feed'));
        //$request->removeQueryParams();
        $res = $serviceRequest->execute();
        var_dump($res);
    }


    /**
     * Fetches a single Contact
     * 
     * @param string $contactId contact id
     * 
     * @return \Google\Contacts\Contacts
     *
     * @throws \Google\Contacts\Exception if contact not found
     */
    public function getContact($contactId)
    {
        // The request will fail with the standard contact id url
        $contactId = str_replace('http://', 'https://', $contactId);
        $contactId = str_replace('/base/', '/full/', $contactId);
        
        $serviceRequest = ServiceRequestFactory::getInstance();
        $serviceRequest->getRequest()->setFullUrl($contactId);
        $res = json_decode($serviceRequest->execute(), true);
        return new Entry($res['entry']);
    }

}