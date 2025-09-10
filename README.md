# Laravel Responder

A simple and flexible responder package for Laravel to standardize API responses.  
It helps you return consistent **JSON responses** with optional transformers, status codes, and messages.

---

## 📦 Installation

```bash
composer require agunbuhori/responder
```

---

## ⚙️ Setup

The package binds `ResponderInterface` into the container.  
You can use it via:

- The global helper `responder()`
- The `HasResponder` trait
- Directly resolving `ResponderInterface` from the service container

---

## 🚀 Usage

### 1. Using the `responder()` helper

```php
use App\Models\User;

public function show(User $user)
{
    return responder()
        ->data($user)
        ->status(200)
        ->message("User retrieved successfully")
        ->send();
}
```

---

### 2. With Transformer

```php
namespace App\Transformers;

use App\Models\User;
use Agunbuhori\Responder\Transformer;

class UserTransformer extends Transformer
{
    public function handle(User $user): array
    {
        return [
            'id'   => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}
```

```php
use App\Models\User;
use App\Transformers\UserTransformer;

public function show(User $user)
{
    return responder()
        ->data($user, UserTransformer::class)
        ->status(200)
        ->message("User retrieved successfully")
        ->send();
}
```

---

### 3. Using `HasResponder` trait

```php
use Agunbuhori\Responder\Traits\HasResponder;

class UserController extends Controller
{
    use HasResponder;

    public function index()
    {
        return $this->success(User::all());
    }

    public function show(User $user)
    {
        return $this->success($user);
    }

    public function store()
    {
        return $this->error(422, "Validation failed");
    }
}
```

---

### 4. Without Wrapper

```php
return responder()
    ->data($user)
    ->withoutWrapper()
    ->send();
```

---

## 📚 Response Format

**Default wrapped response:**

```json
{
  "data": { /* your transformed data */ },
  "status": 200,
  "message": "success"
}
```

**Without wrapper:**

```json
{
  "id": 1,
  "name": "John Doe"
}
```

---

## ⚡ Exception Response

```php
responder()
    ->data(['error' => 'Not Found'])
    ->status(404)
    ->message('Resource not found')
    ->exception();
```

---

## 🔨 Artisan Command

This package provides a command to **generate Transformer classes**.

### Usage

```bash
php artisan make:responder-transformer {name} {--model=}
```

- `{name}` → The name of the transformer class  
- `--model` (optional) → Bind the transformer to an Eloquent model  

### Examples

**Without model:**

```bash
php artisan make:responder-transformer UserTransformer
```

Generates:

```php
<?php

namespace App\Http\Transformers;

use Agunbuhori\Responder\Transformer;

class UserTransformer extends Transformer
{
    public function transform(mixed $user): array
    {
        return [
            'id' => $user['id'] ?? null,
        ];
    }
}
```

**With model:**

```bash
php artisan make:responder-transformer UserTransformer --model=User
```

Generates:

```php
<?php

namespace App\Http\Transformers;

use Agunbuhori\Responder\Transformer;
use App\Models\User;

class UserTransformer extends Transformer
{
    public function transform(User $user): array
    {
        return [
            'id' => $user->id,
        ];
    }
}
```

---

## ✅ Features

```txt
- Consistent API response structure
- Built-in support for Transformers
- Works with Collection, Model, or raw arrays
- HasResponder trait for quick success/error responses
- responder() helper for convenience
- Supports wrapped and unwrapped responses
- Artisan command to auto-generate Transformers
```

---

## 📝 License

```txt
This package is open-source software licensed under the MIT license.
```
