# from-vk-to-wordpress
Автоматическая публикация записей из сообщества Вконтакте (VK.com) на сайт с Wordpress

Скрипт получает событие wall_post_new от callback api vk, забирает данные и обращается к API WordPress wp_insert_post.
Сейчас в пост на Вордпресс добавляется только текст и фотографии из поста сообщества.

Подробней про VK callback api и о том, как получить confirmation token:
https://vk.com/dev/callback_api

