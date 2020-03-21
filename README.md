# Forum App For Developers to Contribute :)

## Preview

![](/public/images/Preview.png)


Installation : Clone the Repository

```
 git clone https://Ngizeh@bitbucket.org/Ngizeh/forum.git
```

Make sure you have redis server installed in your machine. 
If you don't, paste the following on terminal

```
  apt-get install redis-server
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

Finally

- Copy `.env.example` to `.env` and fill your values 
(`php artisan key:generate`, configure your database and run `php artisan migrate`)

Serve your Application on localhost :)

Fork and contribute...
