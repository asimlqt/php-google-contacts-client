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
 * Entry XML Converter.
 *
 * @package    Google
 * @subpackage Contacts
 * @version    0.1
 * @author     Asim Liaquat <asimlqt22@gmail.com>
 */
class EntryToXmlAdapter
{
    /**
     * Convert an Entry object into an xml string which can be 
     * posted 
     * 
     * @param  Entry  $entry [description]
     * @return string
     */
    public function adapt(Entry $entry)
    {
        $xml = new \XmlWriter();
        $xml->openMemory();

        $xml->startElementNS('atom', 'entry', 'http://www.w3.org/2005/Atom'); 
        $xml->writeAttribute('xmlns:gd', 'http://schemas.google.com/g/2005'); 
        $xml->writeAttribute('xmlns:gContact', 'http://schemas.google.com/contact/2008');

        if(!is_null($entry->getId())) {
            $xml->writeAttribute('gd:etag', $entry->getEtag()); 
            $xml->writeElement('id', $entry->getId());
            $xml->writeElement('updated', date('c', time()));
        }

        if(count($entry->getLinks()) > 0) {
            foreach($entry->getLinks() as $link) {
                $xml->startElement('link');
                $xml->writeAttribute('rel', $link->getRel());
                $xml->writeAttribute('type', $link->getType());
                $xml->writeAttribute('href', $link->getHref());
                $xml->endElement();
            }
        }

        if(!is_null($entry->getName())) {
            $xml->startElement('gd:name');
            $xml->writeElement('gd:givenName', $entry->getName()->getGivenName());
            $xml->writeElement('gd:familyName', $entry->getName()->getFamilyName());
            $xml->writeElement('gd:fullName', $entry->getName()->getFullName());
            $xml->endElement(); // gd:name element
        }

        // phone numbers
        if(count($entry->getPhoneNumbers()) > 0) {
            foreach($entry->getPhoneNumbers() as $phone) {
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
        if(count($entry->getEmails()) > 0) {
            foreach($entry->getEmails() as $email) {
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
        if(count($entry->getPostalAddresses()) > 0) {
            foreach($entry->getPostalAddresses() as $addr) {
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

        if($entry->getBirthday() instanceof \DateTime) {
            $xml->startElement('gContact:birthday');
            $xml->writeAttribute('when', $entry->getBirthday()->format('Y-m-d'));
            $xml->endElement();
        }

        if(count($entry->getCustomFields()) > 0) {
            foreach($entry->getCustomFields() as $key => $value) {
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