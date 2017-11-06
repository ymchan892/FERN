<?php

// 連線至 Mailgun SMPT by Ivan Wang @ 2017/06/28
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.mailgun.org';
$config['smtp_port'] = '25';
$config['smtp_timeout'] = '7';
$config['smtp_user'] = SMTP_USER;
$config['smtp_pass'] = SMTP_PASS;
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['mailtype'] = 'html';
$config['validate'] = TRUE;
