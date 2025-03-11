#### Getting Started

Create project with this composer command:

```bash
composer create-project riodwanto/superduper-filament-starter-kit
```

Setup your env:

```bash
cd superduper-filament-starter-kit-evan
cp .env.example .env
```

Run migration & seeder:

```bash
php artisan migrate
php artisan db:seed
```

<p align="center">or</p>

```bash
php artisan migrate:fresh --seed
```

Generate key:

```bash
php artisan key:generate
```

Run :

```bash
npm run dev
OR
npm run build
```

```bash
php artisan serve
```

Now you can access with `/admin` path, using:

```bash
email: superadmin@starter-kit.com
password: superadmin
```

#### Performance

*It's recommend to run below command as suggested in [Filament Documentation](https://filamentphp.com/docs/3.x/panels/installation#improving-filament-panel-performance) for improving panel perfomance.*

```bash
php artisan icons:cache
```

Please see this [Improving Filament panel performance](https://filamentphp.com/docs/3.x/panels/installation#improving-filament-panel-performance) documentation for further improvement

#### Language Generator

This project include lang generator.

```bash
php artisan superduper:lang-translate [from] [to]
```


##### Usage example

- Single output

```bash
php artisan superduper:lang-translate en fr
```

- Multiple output

```bash
php artisan superduper:lang-translate en es ar fr pt-PT pt-BR zh-CN zh-TW
```

###### If you are using json translation

```bash
php artisan superduper:lang-translate en fr --json
```
