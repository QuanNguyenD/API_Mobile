<?php
/**************************LOGIN***************************** */
$router->map('GET|POST','/login/?','LoginController#process');
/**************************SIGNUP********************************/
$router->map('POST','/signup/?','SignupController#process');
