# HP-printOS-PHP-Class

simple usage:
```
require_once('printos.php');
$clientInfo = array(
	'token' => '', // token
	'secret' => '', // secret
	'baseUrl' => '' // box / siteflow url
);
$printOs = new PrintOs($clientInfo);
$printOs->setPath($method); // api method
$printOs->generateHttpHeaderString('POST');
$response = $printOs->request($postData); // data as array
```
