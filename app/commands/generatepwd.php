<?php 
/**
 * CLI : $ php app/commands/generatepwd.php 
 * URI : /app/commands/generatepwd.php
 *
 * Paste the result on conf.php $this->loginKey
 * 
 */

/* if PHP >= 5.5 */
$options = [
    'cost' => 12,
];

print password_hash("admin", PASSWORD_BCRYPT, $options);

/* if PHP < 5.5 */
/* 
print md5("admin");
*/