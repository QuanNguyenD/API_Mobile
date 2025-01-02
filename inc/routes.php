<?php
/**************************LOGIN***************************** */
$router->map('GET|POST','/login/?','LoginController#process');
/**************************SIGNUP********************************/
$router->map('POST','/signup/?','SignupController#process');

/***********************TASK******************** */
$router->map('POST|PUT|GET|DELETE','/tasks/?','TasksController#process');
$router->map('POST|PUT|GET|DELETE','/tasks/[i:id]','TaskController#process');

/***************************Category******************* */
$router->map('POST|PUT|GET|DELETE','/categories/?','CategoriesController#process');
$router->map('POST|PUT|GET|DELETE','/categories/[i:id]','CategoryController#process');

