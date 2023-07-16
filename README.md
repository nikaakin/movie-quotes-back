<div style="display:flex; align-items: center">
  <h1 style="position:relative; top: -6px" >Movie Quotes</h1>
</div>

---

Movie Quotes - Website where users can share their favourite movie quotes. Every user will be able to delete any number of quotes and movies they have made. Reacting or commenting to someones quote will result to notifying the receiver.

#

### Table of Contents

-   [Prerequisites](#prerequisites)
-   [Tech Stack](#tech-stack)
-   [Getting Started](#getting-started)
-   [Migrations](#migration)
-   [Development](#development)
-   [Project Structure](#project-structure)
-   [Database Structure](#database-structure)
-   [Recources](#recources)

#

### Prerequisites

-   *PHP@8.1 and up*
-   _MYSQL@8 and up_
-   _composer@2.5.5 and up\_

#

### Tech Stack

-   [Laravel@10.x](https://laravel.com/docs/10.x) - Back-end framework.

-   [pusher-http-php](https://github.com/pusher/pusher-http-php) - PHP library for interacting with the Pusher Channels HTTP API.

#

### Getting Started

1\. First of all you need to clone repository from github:

```sh
git clone git@github.com:RedberryInternship/nika-cuckiridze-covid-epic-movie-quotes-back.git
```

2\. Next step requires you to run _composer install_ in order to install all the dependencies.

```sh
composer install
```

in order to use tailwind styles.

3\. Now we need to set our env file. Go to the root of your project and execute this command.

```sh
cp .env.example .env
```

And now you should provide **.env** file all the necessary environment variables:

#

**MYSQL:**

> DB_CONNECTION=mysql

> DB_HOST=127.0.0.1

> DB_PORT=3306

> DB_DATABASE=**\***

> DB_USERNAME=**\***

> DB_PASSWORD=**\***

**App Setup:**

> APP_URL=**\***

> FRONTEND_URL=**\***

> SESSION_DOMAIN=**\***

> SANCTUM_STATEFUL_DOMAINS=**\***

> BROADCAST_DRIVER=pusher

> SESSION_DRIVER=cookie

**Google Client:**

> GOOGLE_CLIENT_ID=**\***

> GOOGLE_CLIENT_SECRET=**\***

**Mail:**

> MAIL_MAILER=**\***

> MAIL_HOST=**\***

> MAIL_PORT=**\***

> MAIL_USERNAME=**\***

> MAIL_PASSWORD=**\***

> MAIL_ENCRYPTION=**\***

**Pusher:**

> PUSHER_APP_ID=**\***

> PUSHER_APP_KEY=**\***

> PUSHER_APP_SECRET=**\***

> PUSHER_HOST=**\***

> PUSHER_PORT=**\***

> PUSHER_SCHEME=**\***

> PUSHER_APP_CLUSTER=**\***

#

```sh
php artisan config:cache
```

in order to cache environment variables.

4\. Now execute in the root of you project following:

```sh
  php artisan key:generate
```

Which generates auth key.

##### Now, you should be good to go!

#

### Migration

if you've completed getting started section, then migrating database if fairly simple process, just execute:

```sh
php artisan migrate
```

#

### Development

You can run Laravel's built-in development server by executing:

```sh
  php artisan serve
```

#

### Project Structure

```bash
├─── github
|   ├─── workflows
|   |   ├─── cd.yml
├─── app
|   |... Broadcasting
|   |... Events
|   ├─── Console
│   ├─── Exceptions
│   ├─── Facades
│   |... Http
│   ├─── Providers
│   │... Models
|   |... Mail
|   |... Rules
├─── bootstrap
├─── config
├─── database
├─── packages
├─── public
├─── resources
├─── routes
├─── storage
- .env
- artisan
- composer.json
- package.json
```

Project structure is fairly straitforward(at least for laravel developers)...

For more information about project standards, take a look at these docs:

-   [Laravel](https://laravel.com/docs/10.x)

#

### Database Structure

Database structure - https://drawsql.app/teams/personal-865/diagrams/epic-movie-quotes

### Recources

-   [Figma - project design.](https://www.figma.com/file/5uMXCg3itJwpzh9cVIK3hA/Movie-Quotes-Bootcamp-assignment?type=design&node-id=264-15824&mode=design)
-   [Assignmant details](https://redberry.gitbook.io/assignment-iv-movie-quotes-1/)
-   [Git commit rules](https://redberry.gitbook.io/resources/other/git-is-semantikuri-komitebi)
