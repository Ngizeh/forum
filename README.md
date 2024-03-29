# Forum App For Developers to Contribute :)

## Preview

![](/public/images/Preview.png)


## Installation : Clone the Repository

```
 git clone https://Ngizeh@bitbucket.org/Ngizeh/forum.git
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

- Get your mailtrap config settings at mailtrap in .env file

```

    MAIL_USERNAME=Your-username-here
    MAIL_PASSWORD=Your-password-here

```

- Get your G-RECAPTCHA settings here https://www.google.com/recaptcha/intro/v3.html in your .env file

```

    RECAPTCHA_KEY=Your-key-here
    RECAPTCHA_SECRET=Your-secret-here

```


- Fill the database with seeder data, run this on the terminal
```
  php artisan db:seed

```

Finally,serve your Application on localhost:) http://localhost:8000
- If you don't have valet installed in your machine. Use this command on your terminal
```
  php artisan serve
```

Fork and contribute...
