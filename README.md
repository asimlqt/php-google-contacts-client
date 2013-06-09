php-google-contacts-client
==========================

PHP client library for accessing Google Contacts

**Warning: Do not use update contacts using the Entry::save() method if you have groups or photos setup for your contacts.They have not yet been implemented and you will lose that data**

Example Usage:

```
<?php

require_once 'src/Google/Contacts/Autoloader.php';

$accessToken = 'ya29.AHES6ZSe_cWRY4GHXI2PTAn5cIi_l_mVYniFzbTpYyaPux1RgPw';
Google\Contacts\ServiceRequestFactory::setInstance(new Google\Contacts\DefaultServiceRequest(new Google\Contacts\Request($accessToken)));

$service = new Google\Contacts\Service();
$contacts = $service->getAll('asimlqt22@gmail.com')->getEntries();

foreach($contacts as $entry) {
    if(!is_null($entry->getName()))
        echo $entry->getName()->getFullName() . "\n";
}
```
