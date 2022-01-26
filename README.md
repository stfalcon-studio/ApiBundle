# ApiBundle

:package: Base classes and helper services to build API application via Symfony.

[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/stfalcon-studio/ApiBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/stfalcon-studio/ApiBundle/)
[![Build Status](https://img.shields.io/github/workflow/status/stfalcon-studio/ApiBundle/CI/main?style=flat-square)](https://github.com/stfalcon-studio/ApiBundle/actions?query=workflow%3ACI+branch%3Amain+)
[![CodeCov](https://img.shields.io/codecov/c/github/stfalcon-studio/ApiBundle.svg?style=flat-square)](https://codecov.io/github/stfalcon-studio/ApiBundle)
[![License](https://img.shields.io/packagist/l/stfalcon-studio/api-bundle.svg?style=flat-square)](https://packagist.org/packages/stfalcon-studio/api-bundle)
[![Latest Stable Version](https://img.shields.io/packagist/v/stfalcon-studio/api-bundle.svg?style=flat-square)](https://packagist.org/packages/stfalcon-studio/api-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/stfalcon-studio/api-bundle.svg?style=flat-square)](https://packagist.org/packages/stfalcon-studio/api-bundle)
[![StyleCI](https://styleci.io/repos/257974142/shield?style=flat-square)](https://styleci.io/repos/257974142)

## Installation

```composer req stfalcon-studio/api-bundle='0.16'```

#### Check the `config/bundles.php` file

By default, Symfony Flex adds this bundle to the `config/bundles.php` file.
In case when you ignored `contrib-recipe` during bundle installation it would not be added. In this case add the bundle manually.

```php
# config/bundles.php

return [
    // Other bundles...
    StfalconStudio\ApiBundle\StfalconApiBundle::class => ['all' => true],
    // Other bundles...
];
```

## Set Up Steps

#### Add mappings to Doctrine ORM config

```yaml
doctrine:
    orm:
        mappings:
            StfalconApiBundle: ~
```

#### Setup a directory for JSON schemas

The default directory is `./src/Json/Schema/`. Or you can change it via bundle configuration.

```yaml
stfalcon_api:
    api_host: '%env(APP_API_HOST)%'
    redis_client_jwt_black_list: "@snc_redis.jwt_black_list"
    json_schema_dir: '%kernel.project_dir%/src/Json/Schema/'
```

## Contributing

Read the [CONTRIBUTING](https://github.com/stfalcon-studio/ApiBundle/blob/master/.github/CONTRIBUTING.md) file.
