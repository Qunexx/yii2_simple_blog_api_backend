### Тестовое задание от WTS


<h2>Инструкция по разворачиванию проекта</h2>

### 1.Клонировать репозиторий
   ```bash
   git clone git@github.com:Qunexx/yii2_simple_blog_api_backend.git
  ```
### 2. Перейти в app/common, затем создать там файл main-local.php с содержимым ниже:
   ```bash
  <?php

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=mysql;dbname=yii2advanced',
            'username' => 'yii2advanced',
            'password' => 'secret',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
         
        ],
    ],
];

```
### 3. Затем вернуться на одну директорию назад в app и собрать докер образы
```bash
  cd app
docker-compose build
```
### 4.Запустить докер образы
```bash
  docker-compose up -d
```
### 5.Войти в контейнер backend и установить зависимости композер
```bash
   docker-compose exec backend bash -c "composer install"
 ```
### 6.Можно тестить апи и админку

<h3>Админка доступна для пользователей с флагом is_admin по роуту http://localhost/admin/</h3>
    <h3>Апи доступны по роутам:</h2>
    <ol>
        <li>
            <strong>Получить все посты</strong>
            <ul>
                <li><strong>Метод:</strong> <code>GET</code></li>
                <li><strong>Роут:</strong> <code>http://localhost/api/blog/posts</code></li>
                <li><strong>Параметры:</strong>
                    <ul>
                        <li><code>limit</code> : Количество постов для возврата.</li>
                        <li><code>offset</code> : Количество постов для пропуска.</li>
                    </ul>
                </li>
            </ul>
        </li>
        <li>
            <strong>Получить мои посты</strong>
            <ul>
                <li><strong>Метод:</strong> <code>GET</code></li>
                <li><strong>Роут:</strong> <code>http://localhost/api/blog/my-posts</code></li>
                <li><strong>Параметры:</strong>
                    <ul>
                        <li><code>limit</code> : Количество постов для возврата.</li>
                        <li><code>offset</code> : Количество постов для пропуска.</li>
                    </ul>
                </li>
            </ul>
        </li>
        <li>
            <strong>Создать пост</strong>
            <ul>
                <li><strong>Метод:</strong> <code>POST</code></li>
                <li><strong>Роут:</strong> <code>http://localhost/api/blog/post/create</code></li>
                <li><strong>Параметры:</strong>
                    <ul>
                        <li><code>text</code> : Текст поста.</li>
                    </ul>
                </li>
            </ul>
        </li>
        <li>
            <strong>Логин</strong>
            <ul>
                <li><strong>Метод:</strong> <code>POST</code></li>
                <li><strong>Роут:</strong> <code>http://localhost/api/auth/login</code></li>
                <li><strong>Параметры:</strong>
                    <ul>
                        <li><code>email</code> : Email пользователя.</li>
                        <li><code>password</code> : Пароль пользователя.</li>
                    </ul>
                </li>
            </ul>
        </li>
        <li>
            <strong>Регистрация</strong>
            <ul>
                <li><strong>Метод:</strong> <code>POST</code></li>
                <li><strong>Роут:</strong> <code>http://localhost/api/auth/register</code></li>
                <li><strong>Параметры:</strong>
                    <ul>
                        <li><code>name</code> Имя пользователя.</li>
                        <li><code>email</code> Email пользователя.</li>
                        <li><code>password</code> Пароль пользователя.</li>
                    </ul>
                </li>
            </ul>
        </li>
        <li>
            <strong>Логаут</strong>
            <ul>
                <li><strong>Метод:</strong> <code>POST</code></li>
                <li><strong>Роут:</strong> <code>http://localhost/api/auth/logout</code></li>
                <li><strong>Параметры:</strong> Нет.</li>
            </ul>
        </li>
    </ol>



