# Бот для автопостинга в Twitter

## Общие сведения

Программа предназначена для автоматического заполнения записями аккаунта пользователя в Twitter. В отличие от других подобных программ, этот бот не выбирает тексты постов из готовой базы данных, а собирает их со сторонних ресурсов. Посты публикуются со ссылками на оригинальные публикации, так что авторские права источников не нарушаются. На данный момент в программе реализованы следующие основные функции - сбор новостей из открытых источников в локальную базу данных, постинг новостей в Twitter по расписанию cron, вывод различного рода статистики. В программе используются компоненты сторонних разработчиков (свободно распространяемая библиотека twitteroauth).

Программа написана на PHP, но управление ей осуществляется через скрипты Unix shell. Впрочем, при желании, программа легко портируется под Windows.

## Сбор новостей

Новости собираются из RSS-каналов ресурсов-доноров. В базе данных хранится список каналов со ссылками на RSS-ленты новостей. Новости из всех активных лент собираются в базу данных. Данные каналов постоянно обновляются и отражают изменения в лентах доноров. Т.е., если событие удалено из RSS-ленты источника, то оно будет удалено и из локальной базы данных. Это позволяет поддерживать базу данных событий в актуальном состоянии.

## Публикация новостей в Twitter

Новости публикуются с установленной периодичностью, но не точно по времени, а с некоторым случайным разбросом. Выборка новости для публикации из базы данных осуществляется случайным образом, но так, что каждая новость может быть опубликована только один раз. В Twitter публикуется краткий текст и ссылка на оригинал новости на сайте-доноре.

## Статистика

Программа ведёт подробный журнал работы, который записывает в базу данных. Чтобы журнал не занимал в базе данных слишком много места, старые записи периодически удаляются. Записи в журнале делятся на три категории: сообщения, предупреждения и ошибки. Скрипт сбора статистики позволяет получить обобщённую информацию о количестве записей в журнале по каждой категории. Также доступна возможность просмотра статистики по хранящимся в базе данных новостям. При этом вычисляется процент утилизации событий. Т.е., часть событий, в результате обновления лент, может быть удалена из базы данных не дождавшись своей очереди на публикацию в Twitter. Если публикуется отностительно небольшой процент событий, то к данной базе можно привязать ещё один (или несколько) аккаунтов, при этом посты в этих аккаунтах не будут повторяться.