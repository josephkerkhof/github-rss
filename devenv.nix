{ config, pkgs, ... }:

let
  laravelEnv = {
    APP_URL = "http://localhost:8000";
    DB_CONNECTION = "pgsql";
    DB_HOST = "127.0.0.1";
    DB_PORT = builtins.toString config.services.postgres.port;
    DB_DATABASE = "laravel";
    DB_USERNAME = "laravel";
    DB_PASSWORD = "secret";
    REDIS_HOST = "127.0.0.1";
    REDIS_PORT = builtins.toString config.services.redis.port;
  };
in
{
  languages.php = {
    enable = true;
    version = "8.4";
    extensions = [
      "bcmath"
      "gd"
      "intl"
      "pcntl"
      "pdo_pgsql"
      "redis"
      "xdebug"
    ];
    ini = ''
      upload_max_filesize = 100M
      post_max_size = 100M
      memory_limit = 512M
      max_execution_time = 600
      max_input_vars = 5000
      max_input_time = 600
      xdebug.mode = debug,coverage
      xdebug.start_with_request = trigger
    '';
  };

  languages.javascript = {
    enable = true;
    package = pkgs.nodejs_22;
    npm.enable = true;
  };

  packages = with pkgs; [
    git
    unzip
  ];

  env = laravelEnv;

  services.postgres = {
    enable = true;
    package = pkgs.postgresql_17;
    listen_addresses = "127.0.0.1";
    initialDatabases = [
      {
        name = "laravel";
        user = "laravel";
        pass = "secret";
      }
    ];
  };

  services.redis.enable = true;

  tasks = {
    "github-rss:composer-install" = {
      exec = "composer install --no-interaction --prefer-dist";
      execIfModified = [
        "composer.json"
        "composer.lock"
      ];
      before = [ "devenv:enterShell" ];
    };

    "github-rss:npm-install" = {
      exec = "npm ci";
      execIfModified = [
        "package.json"
        "package-lock.json"
      ];
      before = [ "devenv:enterShell" ];
    };

    "github-rss:bootstrap" = {
      exec = ''
        if [ ! -f .env ]; then
          cp .env.example .env
        fi

        if ! grep -Eq '^APP_KEY=.+$' .env; then
          php artisan key:generate --ansi
        fi
      '';
      status = "test -f .env && grep -Eq '^APP_KEY=.+$' .env";
      after = [ "github-rss:composer-install" ];
      before = [ "devenv:enterShell" ];
    };

    "github-rss:migrate" = {
      exec = "php artisan migrate --force --ansi";
      after = [ "devenv:processes:postgres" ];
      before = [
        "devenv:processes:server"
        "devenv:processes:queue"
        "devenv:processes:scheduler"
      ];
    };

    "github-rss:seed" = {
      exec = "php artisan migrate:fresh --seed --force --ansi";
      after = [ "github-rss:migrate" ];
    };
  };

  processes = {
    server = {
      exec = "php -S 127.0.0.1:${builtins.toString config.processes.server.ports.http.value} -t public vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php";
      env = laravelEnv;
      ports.http.allocate = 8000;
      after = [
        "devenv:processes:redis"
        "github-rss:migrate"
      ];
    };

    queue = {
      exec = "php artisan queue:listen --tries=1";
      env = laravelEnv;
      after = [
        "devenv:processes:redis"
        "github-rss:migrate"
      ];
    };

    scheduler = {
      exec = "php artisan schedule:work";
      env = laravelEnv;
      after = [
        "devenv:processes:redis"
        "github-rss:migrate"
      ];
    };

    logs = {
      exec = "php artisan pail --timeout=0";
      env = laravelEnv;
    };

    vite = {
      exec = "npm run dev -- --host=127.0.0.1 --port=${builtins.toString config.processes.vite.ports.http.value} --strictPort";
      ports.http.allocate = 5173;
    };
  };

  enterTest = ''
    php artisan test
  '';
}
