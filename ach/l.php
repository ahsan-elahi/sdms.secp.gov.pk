<?php
require 'secure.inc.php';
//Basic url validation + token check.
if (!($url=trim($_GET['url'])) || !Validator::is_url($url) || !$ost->validateLinkToken($_GET['auth']))
    exit('Invalid url');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="refresh" content="0;URL=<?php echo $url; ?>"/>
</head>
<body/>
</html>
