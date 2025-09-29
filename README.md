# GitHub RSS Feed

A sophisticated RSS feed generator for tracking GitHub repository activity, designed to demonstrate modern software engineering practices and architectural patterns.

While the core functionality could be implemented simply, this project intentionally showcases enterprise-level engineering approaches including comprehensive testing strategies, CQRS architecture, and containerized deployment.

## Architecture & Engineering Goals

### ✅ Comprehensive Testing Strategy
- **Test Pyramid Implementation**: Emphasizing integration tests over unit tests for better confidence in system behavior
- **High Coverage**: All critical paths covered with meaningful tests

##  🧪 Test Method Naming Convention

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

### 🐳 Containerized Deployment
- **Docker Integration**: Streamlined local development and deployment
- **Environment Consistency**: Eliminates "works on my machine" issues

## Roadmap

This project serves as a foundation for demonstrating additional enterprise patterns and technologies:

- **RESTful API Development**: Clean API design following REST principles
- **Event-Driven Architecture**: Integration with AWS SQS via LocalStack
- **Polyglot Microservices**: Additional services in different languages

## Getting Started

### Initial Setup

```bash
# Starting and stopping the environment
bin/start          # Basic setup
bin/start --seed   # Setup with database seeding (this will wipe out the existing database content)
bin/stop           # Stop containers
```

### Daily Development

```bash
bin/aritsan <...>           # Run Artisan commands
DEBUG=1 bin/aritsan <...>   # Run Artisan commands with xdebug
bin/artisan tinker          # Run tinker
bin/composer <...>          # Run composer
```

### Testing

```bash
bin/test                     # Run all tests
bin/test --coverage          # Run with coverage
```

### Database Management

```bash
bin/artisan migrate          # Run migrations
```

---

This project demonstrates practical application of modern software engineering principles while solving a real-world problem of GitHub activity tracking.
