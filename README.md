# sportb
Веб-клиент Sportbox, реализующий API официального мобильного приложения.
Создан для отправки HD-ссылок трансляций на Chromecast. На официальном сайте sportbox.ru ссылки на трансляции в HD формате выдаются только платно, однако в мобильном приложении - бесплатно. Это и использует данный клиент.

# Описание
На главной странице выводится список из 100 актуальных видео, и у каждого из них две ссылки:

1. ***Video***: генерируется страница, содержащая ссылку на HD-трансляцию внутри тега `<video/>`, которую можно отправить на Chromecast через приложение [Web Video Cast](https://play.google.com/store/apps/details?id=com.instantbits.cast.webvideo).
1. ***M3U***: генерируется плейлист формата M3U, эмулирующий playlist.m3u8 (для обхода оригинального playlist.m3u8). Это рекомендованный вариант для Chromecast, т.к. в оригинальном потоке запрещено кеширование.

# Скриншот
![screenshot](https://cloud.githubusercontent.com/assets/2682026/16022716/51dee470-31ca-11e6-8a14-0363d37e28be.png)
