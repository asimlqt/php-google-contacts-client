php-google-contacts-client
==========================

PHP client library for accessing Google Contacts

**Warning: Do not update contacts using the Entry::save() method if you have groups or photos setup for your contacts.  They have not yet been implemented and you will lose that data**

Example Usage:

```
<?php

require_once 'src/Google/Contacts/Autoloader.php';

$accessToken = 'ya29.AHES6ZSe_cWRY4GHXI2PTAn5cIi_l_mVYniFzbTpYyaPux1RgPw';
$serviceRequest = new Google\Contacts\DefaultServiceRequest(new Google\Contacts\Request($accessToken));
Google\Contacts\ServiceRequestFactory::setInstance($serviceRequest);

$service = new Google\Contacts\Service();
$contacts = $service->getAll()->getEntries();

foreach($contacts as $entry) {
    if(!is_null($entry->getName()))
        echo $entry->getName()->getFullName() . "\n";
}
```
