# Forum App For Developers to Contribute :)

## Preview

![](/public/images/Preview.png)


## Installation : Clone the Repository

```
 git clone https://Ngizeh@bitbucket.org/Ngizeh/forum.git
```

## Copy the config file and edit your system setting

```
  cp .env.example .env
```

```
DB_CONNECTION="Your database system"
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE="Your database name"
DB_USERNAME="Your database user name"
DB_PASSWORD="Your database passoword"
```

Make sure you have redis server installed in your machine.
If you don't, paste the following on terminal

```
  sudo apt-get install redis-server
```

Navigate to the project folder

```
 cd path/to/your/project
```

On the project folder run the following command on the terminal

```
 composer install
```

Install the project dependencies


```
 npm install && npm run dev
```

 or

 If you have yarn installed

```
 yarn install && yarn run dev
```


- Copy `.env.example` to `.env` and fill your values
(`php artisan key:generate`, configure your database and run `php artisan migrate`)

- Get your your mailtrap config setting at mailtrap in .env file

```

    MAIL_USERNAME=Your-username-here
    MAIL_PASSWORD=Your-password-here

```

- Get your G-RECAPTCHA settings here https://www.google.com/recaptcha/intro/v3.html in your .env file

```

    RECAPTCHA_KEY=Your-key-here
    RECAPTCHA_SECRET=Your-secret-here

```


Finally,serve your Application on localhost:) http://localhost:8000
- If you don't have valet installed in your machine. Use this command on your terminal
```
  php artisan serve
```

Fork and contribute...
