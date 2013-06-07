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
 * Contacts Entry.
 *
 * @package    Google
 * @subpackage Contacts
 * @version    0.1
 * @author     Asim Liaquat <asimlqt22@gmail.com>
 */
class Entry
{
    private $etag;
    private $id;
    private $updated;
    private $editUrl;
    private $title;
    private $name;
    private $phoneNumbers = array();
    private $emails = array();

    /**
     * The xml entry for a single contact
     * 
     * @var array
     */
    private $entry;

    public function __construct($entry=null)
    {
        if(!is_null($entry)) {
            $this->etag = $entry['gd$etag'];
            $this->id = $entry['id']['$t'];
            $this->$updated = new \DateTime($entry['updated']['$t']);
            $this->editUrl = Util::getLinkHref($entry, 'edit');
            $this->title = $entry['title']['$t'];

            if(isset($entry['gd$name'])) {
               $this->name = new Entry\Name($entry['gd$name']);
            }

            if(isset($entry['gd$phoneNumber'])) {
                foreach($entry['gd$phoneNumber'] as $el) {
                    $type = substr($el['rel'], strpos($el['rel'], '#')+1);
                    $number = $el['$t'];
                    $this->phones[] = new Entry\PhoneNumber($type, $number);
                }
            }

            if(isset($entry['gd$email'])) {
                $el = $entry['gd$email'];
                foreach($el as $email) {
                    $type = substr($email['rel'], strpos($email['rel'], '#')+1);
                    $address = $email['address'];
                    $this->emails[] = new Entry\Email($type, $address, $email['primary'] === 'true');
                }
            }
        }
    }

    public function getEtag()
    {
        return $this->etag;
    }

    /**
     * Get the contact id
     * 
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }    

    /**
     * Get the time contact was updated
     * 
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Get edit url
     * 
     * @return string
     */
    public function getEditUrl()
    {
        return $this->editUrl;
    }

    /**
     * Get title
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get contact name object
     * 
     * @return \Google\Contacts\Entry\Name
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName(Entry\Name $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the contact phone numbers
     * 
     * @return array \Google\Contacts\Entry\PhoneNumber
     */
    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }

    public function setPhoneNumbers(array $phoneNumbers)
    {
        $this->phoneNumbers = $phoneNumbers;
        return $this;
    }

    /**
     * Get the contact email information
     * 
     * @return array \Google\Contacts\Entry\Email
     */
    public function getEmails()
    {
        return $this->emails;
    }

    public function setEmails(array $emails)
    {
        $this->emails = $emails;
        return $this;
    }

    public function save($userEmail)
    {
        $xml = new EntryXml();
        $post = $xml->getXml($this);
        var_dump($post);
        exit;

        $serviceRequest = ServiceRequestFactory::getInstance();
        $request = $serviceRequest->getRequest(); /* @var $request \Google\Contacts\Request */
        $request->setEndpoint("{$userEmail}/full");
        $request->setPost($post);
        $request->setMethod('POST');
        $request->setHeaders(array('Content-Type' => 'application/atom+xml; charset=UTF-8; type=feed'));
        //$request->removeQueryParams();
        $res = $serviceRequest->execute();
        var_dump($res);
    }
}