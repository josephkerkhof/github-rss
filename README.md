# GitHub RSS Feed

A sophisticated RSS feed generator for tracking GitHub repository activity, designed to demonstrate modern software engineering practices and architectural patterns.

While the core functionality could be implemented simply, this project intentionally showcases enterprise-level engineering approaches including comprehensive testing strategies, CQRS architecture, and containerized deployment.

## Architecture & Engineering Goals

### ✅ Comprehensive Testing Strategy

- **Test Pyramid Implementation**: Emphasizing integration tests over unit tests for better confidence in system behavior
- **High Coverage**: All critical paths covered with meaningful tests

## 🧪 Test Method Naming Convention

This project employs a Given-When-Then approach to test method naming in PHPUnit, making test intentions immediately clear:

```php
public function repositoryWithoutAuthorAndBranch_handle_createsAuthorAndBranchAndRepository()
public function userWithExistingSubscription_subscribe_throwsAlreadySubscribedException()
public function validRepositoryData_createRepository_returnsRepositoryEntity()
```

Structure: `{given_condition}_{when_action}_{then_expected_outcome}`

This naming convention provides several benefits:

- **Self-documenting tests**: The method name serves as living documentation
- **Clear test scope**: Each test's preconditions and expected outcomes are explicit
- **Improved maintainability**: Developers can quickly understand test purpose without reading implementation
- **Better failure reporting**: Failed test names immediately communicate what business scenario broke

### 🔄 CQRS Pattern Implementation

- **Command Query Responsibility Segregation**: Clean separation between read and write operations
- **Scalable Architecture**: Designed for maintainability and future extension

### Reproducible Development Environment

- **Devenv Integration**: PHP, Node.js, PostgreSQL, and Redis are pinned and managed with Nix
- **Environment Consistency**: The complete local stack starts with one command without Docker

## Roadmap

This project serves as a foundation for demonstrating additional enterprise patterns and technologies:

- **RESTful API Development**: Clean API design following REST principles
- **Event-Driven Architecture**: Integration with AWS SQS via LocalStack
- **Polyglot Microservices**: Additional services in different languages

## Getting Started

### Initial Setup

```bash
# Enter the development shell. Dependencies and the application key are set up automatically.
devenv shell

# Start PostgreSQL, Redis, Laravel, the queue worker, scheduler, logs, and Vite.
devenv up
```

The application is available at `http://localhost:8000`. PostgreSQL and Redis data are persisted under `.devenv/state`.

### Daily Development

```bash
php artisan <...>                         # Run Artisan commands inside devenv shell
XDEBUG_TRIGGER=1 php artisan <...>        # Run with Xdebug enabled
php artisan tinker                        # Run Tinker
composer <...>                            # Run Composer
npm <...>                                 # Run npm
```

### Testing

```bash
php artisan test
XDEBUG_MODE=coverage php artisan test --coverage
devenv test                  # Build the environment and run the test suite
```

### Database Management

```bash
php artisan migrate
devenv tasks run github-rss:seed  # Recreate and seed the development database
```

---

This project demonstrates practical application of modern software engineering principles while solving a real-world problem of GitHub activity tracking.
