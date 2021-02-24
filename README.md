GraphQL Test Case
=================

Makes testing your GraphQL queries and mutations easier.

Support for Symfony.

[![PHP Version](https://img.shields.io/badge/php-%5E7.1-blue.svg)](https://img.shields.io/badge/php-%5E7.1-blue.svg)
[![Latest Stable Version](https://poser.pugx.org/kunicmarko/graphql-test/v/stable)](https://packagist.org/packages/kunicmarko/graphql-test)
[![Latest Unstable Version](https://poser.pugx.org/kunicmarko/graphql-test/v/unstable)](https://packagist.org/packages/kunicmarko/graphql-test)

[![Build Status](https://travis-ci.org/kunicmarko20/graphql-test.svg?branch=master)](https://travis-ci.org/kunicmarko20/graphql-test)
[![Coverage Status](https://coveralls.io/repos/github/kunicmarko20/graphql-test/badge.svg?branch=master)](https://coveralls.io/github/kunicmarko20/graphql-test?branch=master)

Documentation
-------------

* [Installation](#installation)
* [How to use](#how-to-use)
* [Examples](#examples)
    * [Query](#query)
    * [Mutation](#mutation)

## Installation

**1.**  Add dependency with composer

```bash
composer require --dev kunicmarko/graphql-test
```

> If you are using Symfony you will have to install "symfony/browser-kit".

## How to use

Depending on your framework, extend the correct `TestCase`:

```php
use KunicMarko\GraphQLTest\Bridge\Symfony\TestCase;
```

> Everything you see in the next snippets is the same for all Test Cases.

In your tests you now have 2 additional helper methods:

```php
public function query(QueryInterface $query, array $files = [], array $headers = []);
public function mutation(MutationInterface $mutation, array $files = [], array $headers = [])
```

By default, endpoint is `/graphql`, you can overwrite this by changing variable in your tests:

```php
use KunicMarko\GraphQLTest\Bridge\Symfony\TestCase;

class UserTest extends TestCase
{
    public static $endpoint = '/';
}

```

There is a helper method that allows you to preset headers:

```php
use KunicMarko\GraphQLTest\Bridge\Symfony\TestCase;

class SettingsTest extends TestCase
{
    protected function setUp()
    {
        $this->setDefaultHeaders([
            'Content-Type' => 'application/json',
        ]);
    }
}

```

## Examples

### Query

```php
use KunicMarko\GraphQLTest\Bridge\Symfony\TestCase;
use KunicMarko\GraphQLTest\Operation\Query;

class SettingsQueryTest extends TestCase
{
    public static $endpoint = '/';

    protected function setUp()
    {
        $this->setDefaultHeaders([
            'Content-Type' => 'application/json',
        ]);
    }

    public function testSettingsQuery(): void
    {
        $query = $this->query(
            new Query(
                'settings',
                [],
                [
                    'name',
                    'isEnabled',
                ],
            )
        );
        
        //Fetch response and do asserts
    }
}
```

`KunicMarko\GraphQLTest\Operation\Query` construct accepts 3 arguments:

* name of query (mandatory)
* parameters (optional)
* fields (optional)

### Mutation

```php
use KunicMarko\GraphQLTest\Bridge\Symfony\TestCase;
use KunicMarko\GraphQLTest\Operation\Mutation;

class SettingsMutationTest extends TestCase
{
    public static $endpoint = '/';

    protected function setUp()
    {
        $this->setDefaultHeaders([
            'Content-Type' => 'application/json',
        ]);
    }

    public function testSettingsMutation(): void
    {
        $mutation = $this->mutation(
            new Mutation(
                'createSettings',
                [
                    'name' => 'hide-menu-bar',
                    'isEnabled' => true,
                ],
                [
                    'name',
                    'isEnabled',
                ],
            )
        );
        
        //Fetch response and do asserts
    }
}
```

`KunicMarko\GraphQLTest\Operation\Mutation` construct accepts 3 arguments:

* name of mutation (mandatory)
* parameters (optional)
* fields (optional)

If you have a Enum, Boolean or Array as an argument you can pass it as following:

```php
use KunicMarko\GraphQLTest\Bridge\Symfony\TestCase;
use KunicMarko\GraphQLTest\Operation\Mutation;
use KunicMarko\GraphQLTest\Type\EnumType;
use KunicMarko\GraphQLTest\Type\BooleanType;
use KunicMarko\GraphQLTest\Type\ArrayType;

class UserMutationTest extends TestCase
{
    //...
    public function testUserMutation(): void
    {
        $mutation = $this->mutation(
            new Mutation(
                'createUser',
                [
                    
                    'username' => 'kunicmarko20',
                    'salutation' => new EnumType('Mr'),
                    'enabled' => new BooleanType(true),
                    'roles' => new ArrayType(['ROLE_ADMIN', 'ROLE_TEST']),
                    //..
                ],
                [
                    'username',
                    'salutation',
                ],
            )
        );
        
        //Fetch response and do asserts
    }
}
```

Also, if you need a custom type you can always extend `KunicMarko\GraphQLTest\Type\TypeInterface`
and use your own Type instead.
