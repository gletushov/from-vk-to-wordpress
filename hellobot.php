<?php 
$fp = fopen("hellobotlog.txt", "a");

if (!isset($_REQUEST)) { 
  return; 
} 
fwrite($fp, "request arrived\n");

//Строка для подтверждения адреса сервера из настроек Callback API 
$confirmation_token = 'строка подтверждения'; 

//Ключ доступа сообщества 
$token = 'ключ доступа'; 


//Получаем и декодируем уведомление 
$data = json_decode(file_get_contents('php://input')); 

//Проверяем, что находится в поле "type" 
switch ($data->type) { 
  //Если это уведомление для подтверждения адреса... 
  case 'confirmation': 
    //...отправляем строку для подтверждения 
    echo $confirmation_token; 
    break; 

 //Если это уведомление о новом посте...
    case 'wall_post_new':
        //...получаем текст поста
        

        $post_content = $data->object->text;
        $post_title = $data->object->id;
        $post_attachments = $data->object->attachments; //Является массивом, обходится форичем

 
  
//Возвращаем "ok" серверу Callback API 

echo('ok'); 

break; 

} 


 fwrite($fp, "request attachments reached \n");




$urlsString = "";
foreach ($post_attachments as $k => $attachment) {
fwrite($fp, "request attachment reached \n");
fwrite($fp, "attachment type {$attachment->type} \n");
if($attachment->type == 'photo')
{
fwrite($fp, "attachment url reached \n");
fwrite($fp, "attachment url {$attachment->photo->photo_130} \n");
//$urlsString .= "<img class=\"col-10  col-md-6  d-block mr-auto ml-auto my-2\" height=\"300\" src=\"{$attachment->photo->photo_130}\">\n";
$urlsString .= "<a href=\"{$attachment->photo->photo_130}\" target=\"_blank\"><img src=\"{$attachment->photo->photo_130}\" class=\"img-responsive img-thumbnail\"></a>\n";

}
}

$data = preg_replace('/(\[)(\w+)(\|)([\w ]+)(\])/u', "<a href=\"https://vk.com/$2\">$4</a>", $post_content);

//$post_wordpress="{$urlsString} <p>{$data}</p>";

$post_wordpress = '<p>' . $data . '</p><p>' . $urlsString . '</p>';




    $new_post = array(
          'post_author' => '$user->ID',
          'post_content' => $post_wordpress,
          'post_title' => $post_title,
          'post_status' => 'publish',
          'comment_status' => 'closed'
        );

define('WP_USE_THEMES', false);
//require( $_SERVER['DOCUMENT_ROOT'] .'/wp-blog-header.php');
require_once( dirname(__FILE__) . '/wordpress/wp-load.php' );
fwrite($fp, "new post reached \n");

if ($post_content != '') {
      $post_id = wp_insert_post($new_post);

} elseif ($urlsString != '') {
      $post_id = wp_insert_post($new_post);
      
} else {
      fwrite($fp, "post dont support \n");
}

//$post_id = wp_insert_post($new_post);
    

fclose($fp);



?> 