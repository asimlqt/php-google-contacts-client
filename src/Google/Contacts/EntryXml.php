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
class EntryXml
{
/*
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
 */
            private $xml;

    public function __construct() {}

    public function writeName() {

    }

    public function getXml(Entry $entry)
    {
        $xml = new \XmlWriter();
        $xml->openMemory();
        //$this->xml->startElement('entry');
        $xml->startElementNS('atom', 'entry', 'http://www.w3.org/2005/Atom'); 
        //$xml->endElement();
        $xml->writeAttribute('xmlns:gd', 'http://schemas.google.com/g/2005'); 

        $xml->startElement('gd:name');
        $xml->writeElement('gd:givenName', $entry->getName()->getGivenName());
        $xml->writeElement('gd:familyName', $entry->getName()->getFamilyName());
        $xml->writeElement('gd:fullName', $entry->getName()->getFullName());
        $xml->endElement(); // gd:name element

        // phone numbers
        if(count($entry->getPhoneNumbers()) > 0) {
            foreach($entry->getPhoneNumbers() as $phone) {
                $xml->startElement('gd:phoneNumber');
                $xml->writeAttribute('rel', $phone->getType());

                if($phone->getPrimary() === true) {
                    $xml->writeAttribute('primary', $phone->getPrimary());
                }

                $xml->text($phone->getPhoneNumber());
                $xml->endElement();
            }
        }

        $xml->endElement();

        return $xml->outputMemory();
    }
}