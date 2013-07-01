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
class ContactEntry
{
    /**
     * Etag
     * @var string
     */
    private $etag;

    /**
     * $id
     * @var string
     */
    private $id;

    /**
     * $updated
     * @var DateTime
     */
    private $updated;

    /**
     * $links
     * @var array
     */
    private $links = array();

    /**
     * $title
     * @var string
     */
    private $title;

    /**
     * $name
     * @var string
     */
    private $name;

    /**
     * $phoneNumbers
     * @var array
     */
    private $phoneNumbers = array();

    /**
     * $emails
     * @var array
     */
    private $emails = array();

    /**
     * $postalAddresses
     * @var array
     */
    private $postalAddresses = array();

    /**
     * $birthday
     * @var DateTime
     */
    private $birthday;

    /**
     * Custom fields
     * 
     * @var array
     */
    private $customFields = array();

    /**
     * The xml entry for a single contact
     * 
     * @var array
     */
    private $entry;

    /**
     * Constructor
     * @param array $entry 
     */
    public function __construct($entry=null)
    {
        if(!is_null($entry)) {
            //var_dump($entry);exit;
            //return;
            $this->expandEntry($entry);
        }
    }

    private function expandEntry($entry)
    {
        $this->etag = $entry['gd$etag'];
        $this->id = $entry['id']['$t'];
        $this->updated = new \DateTime($entry['updated']['$t']);

        $this->title = $entry['title']['$t'];

        $links = array();
        foreach($entry['link'] as $link) {
            $l = new Entry\Link();
            $l->setRel($link['rel'])
                ->setType($link['type'])
                ->setHref($link['href']);
            $links[] = $l;
        }
        $this->setLinks($links);

        if(isset($entry['gContact$birthday'])) {
            $this->birthday = new \DateTime($entry['gContact$birthday']['when']);
        }

        if(isset($entry['gContact$userDefinedField'])) {
            $fields = $entry['gContact$userDefinedField'];
            $this->customFields = new Entry\CustomFields();
            foreach($fields as $field) {
                $this->customFields[$field['key']] = $field['value'];
            }
        }

        if(isset($entry['gd$name'])) {
           $this->name = new Entry\Name($entry['gd$name']);
        }

        $phones = array();
        if(isset($entry['gd$phoneNumber'])) {
            foreach($entry['gd$phoneNumber'] as $el) {
                $number = $el['$t'];
                $primary = (isset($el['primary']) && $el['primary'] === 'true') ? true : false;
                $phones[] = new Entry\PhoneNumber($el['rel'], $number, $primary);
            }
        }
        $this->setPhoneNumbers($phones);

        $emails = array();
        if(isset($entry['gd$email'])) {
            $el = $entry['gd$email'];
            foreach($el as $email) {
                $address = $email['address'];
                $primary = (isset($email['primary']) && $email['primary'] === 'true') ? true : false;
                $emails[] = new Entry\Email($email['rel'], $address, $primary);
            }
        }
        $this->setEmails($emails);

        $postalAddresses = array();
        if(isset($entry['gd$structuredPostalAddress'])) {
            foreach($entry['gd$structuredPostalAddress'] as $spa) {
                $addr = new Entry\StructuredPostalAddress();
                $addr->setPrimary((isset($spa['primary']) && $spa['primary'] === 'true'))
                    ->setRel($spa['rel'])
                    ->setFormattedAddress($spa['gd$formattedAddress']['$t'])
                    ->setStreet($spa['gd$street']['$t'])
                    ->setPostcode($spa['gd$postcode']['$t'])
                    ->setCity($spa['gd$city']['$t'])
                    ->setRegion($spa['gd$region']['$t'])
                    ->setCountry($spa['gd$country']['$t']);
                $postalAddresses[] = $addr;
            }
        }
        $this->setPostalAddresses($postalAddresses);
    }

    /**
     * Get the etag
     * 
     * @return string
     */
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
     * Returns only the actual id part of the id url
     * 
     * @return string
     *
     * @throws \Google\Contacts\Exception if the id is not set
     */
    public function getIdPart()
    {
        if(!is_string($this->id))
            throw new Exception('id is not set');

        return substr($this->id, strrpos($this->id, '/')+1);
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

    /**
     * Set the title
     * 
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get the date of birth
     * 
     * @return DateTime|null
     */
    public function getBirthday()
    {
        return $this->birthday;
    }
    
    /**
     * Set the date of birth
     * 
     * @param \DateTime $birthday
     */
    public function setBirthday(\DateTime $birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * Get custom fields
     * 
     * @return \Google\Contacts\Entry\CustomFields
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }
    
    /**
     * [setCustomFields description]
     * 
     * @param \Google\Contacts\Entry\CustomFields $fields
     */
    public function setCustomFields(\Google\Contacts\Entry\CustomFields $customFields)
    {
        $this->customFields = $customFields;
        return $this;
    }

    /**
     * Get links
     * 
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * THIS IS PROBABLY NOT REQUIRED
     * 
     * @param [type] $links [description]
     */
    public function setLinks($links)
    {
        $this->links = $links;
        return $this;
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

    /**
     * Set the name components
     * 
     * @param Entry\Name $name
     */
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

    /**
     * Set phone numbers
     * 
     * @param array $phoneNumbers
     */
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

    /**
     * Set emails
     * 
     * @param array $emails
     *
     * @return Entry
     */
    public function setEmails(array $emails)
    {
        $this->emails = $emails;
        return $this;
    }

    /**
     * Get postal addresses
     * 
     * @return array
     */
    public function getPostalAddresses()
    {
        return $this->postalAddresses;
    }

    /**
     * Set postal addresses
     * 
     * @param array $addresses
     */
    public function setPostalAddresses(array $addresses)
    {
        $this->postalAddresses = $addresses;
        return $this;
    }

    /**
     * Save this contact
     *
     * If the id of this entry is not null then anpu update will be performed
     * otherwise a new contact will be created
     * 
     * @return
     */
    public function save($out=false)
    {
        $post = $this->toXml();
        if($out) {
            var_dump($post);
            exit;
        }

        $serviceRequest = ServiceRequestFactory::getInstance();
        $request = $serviceRequest->getRequest(); /* @var $request \Google\Contacts\Request */
        $headers = array(
            'Content-Type' => 'application/atom+xml; charset=UTF-8; type=feed',
        );

        if(!is_null($this->id)) {
            $request->setMethod('PUT');
            $request->setFullUrl($this->getId());
            $headers['If-Match'] = $this->getEtag();
        } else {
            $request->setEndpoint("default/full");
            $request->setMethod('POST');
        }

        $request->setPost($post);
        $request->setHeaders($headers);
        $res = json_decode($serviceRequest->execute(), true);
        $this->expandEntry($res['entry'], true);
    }

    /**
     * Delete this contact
     *
     * @return null
     */
    public function delete()
    {
        $serviceRequest = ServiceRequestFactory::getInstance();
        $request = $serviceRequest->getRequest(); /* @var $request \Google\Contacts\Request */
        $request->setMethod('DELETE');
        $request->setEndpoint("default/full/".$this->getIdPart());
        //$headers['If-Match'] = $this->getEtag();
        $headers['If-Match'] = '*';
        $request->setHeaders($headers);
        $serviceRequest->execute();
    }

    /**
     * Convert an Entry object into an xml string which can be 
     * posted 
     * 
     * @param  Entry  $entry [description]
     * @return string
     */
    private function toXml()
    {
        $xml = new \XmlWriter();
        $xml->openMemory();

        $xml->startElementNS('atom', 'entry', 'http://www.w3.org/2005/Atom'); 
        $xml->writeAttribute('xmlns:gd', 'http://schemas.google.com/g/2005'); 
        $xml->writeAttribute('xmlns:gContact', 'http://schemas.google.com/contact/2008');

        if(!is_null($this->getId())) {
            $xml->writeAttribute('gd:etag', $this->getEtag()); 
            $xml->writeElement('id', $this->getId());
            $xml->writeElement('updated', date('c', time()));
        }

        if(count($this->getLinks()) > 0) {
            foreach($this->getLinks() as $link) {
                $xml->startElement('link');
                $xml->writeAttribute('rel', $link->getRel());
                $xml->writeAttribute('type', $link->getType());
                $xml->writeAttribute('href', $link->getHref());
                $xml->endElement();
            }
        }

        if(!is_null($this->getName())) {
            $xml->startElement('gd:name');
            $xml->writeElement('gd:givenName', $this->getName()->getGivenName());
            $xml->writeElement('gd:familyName', $this->getName()->getFamilyName());
            $xml->writeElement('gd:fullName', $this->getName()->getFullName());
            $xml->endElement(); // gd:name element
        }

        // phone numbers
        if(count($this->getPhoneNumbers()) > 0) {
            foreach($this->getPhoneNumbers() as $phone) {
                $xml->startElement('gd:phoneNumber');
                $xml->writeAttribute('rel', $phone->getType());

                if($phone->getPrimary() === true) {
                    $xml->writeAttribute('primary', 'true');
                }

                $xml->text($phone->getPhoneNumber());
                $xml->endElement();
            }
        }

        // email addresses
        if(count($this->getEmails()) > 0) {
            foreach($this->getEmails() as $email) {
                $xml->startElement('gd:email');
                $xml->writeAttribute('rel', $email->getType());
                $xml->writeAttribute('address', $email->getAddress());

                if($email->getPrimary() === true) {
                    $xml->writeAttribute('primary', 'true');
                }

                $xml->endElement();
            }
        }

        // postal addresses
        if(count($this->getPostalAddresses()) > 0) {
            foreach($this->getPostalAddresses() as $addr) {
                $xml->startElement('gd:structuredPostalAddress');
                $xml->writeAttribute('rel', $addr->getRel());

                if($addr->getPrimary() === true) {
                    $xml->writeAttribute('primary', 'true');
                }

                if(!is_null($addr->getCity()))
                    $xml->writeElement('gd:city', $addr->getCity());
                if(!is_null($addr->getStreet()))
                    $xml->writeElement('gd:street', $addr->getStreet());
                if(!is_null($addr->getRegion()))
                    $xml->writeElement('gd:region', $addr->getRegion());
                if(!is_null($addr->getPostcode()))
                    $xml->writeElement('gd:postcode', $addr->getPostcode());
                if(!is_null($addr->getCountry()))
                    $xml->writeElement('gd:country', $addr->getCountry());
                if(!is_null($addr->getFormattedAddress()))
                    $xml->writeElement('gd:formattedAddress', $addr->getFormattedAddress()); 

                $xml->endElement();
            }
        }

        if($this->getBirthday() instanceof \DateTime) {
            $xml->startElement('gContact:birthday');
            $xml->writeAttribute('when', $this->getBirthday()->format('Y-m-d'));
            $xml->endElement();
        }

        if(count($this->getCustomFields()) > 0) {
            foreach($this->getCustomFields() as $key => $value) {
                $xml->startElement('gContact:userDefinedField');
                $xml->writeAttribute('key', $key);
                $xml->writeAttribute('value', $value);
                $xml->endElement();
            }
        }

        $xml->endElement();

        return $xml->outputMemory();
    }
}