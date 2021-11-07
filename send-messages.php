<?php

require_once 'vendor/autoload.php';

$transport = new Swift_SmtpTransport('smtp.mailtrap.io', 25);
$transport->setUsername('c2d0a1632ae0c0');
$transport->setPassword('02f8d42cde5b1a');

$mailer = new Swift_Mailer($transport);
