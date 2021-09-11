# Pasos para habilitar el JWT en Laravel 8

## 1. Habilitar JWT en el proyecto de laravel 8
```bash
composer require tymon/jwt-auth:dev-develop --prefer-source
```

## 2. Ejecute el siguiente comando para publicar el archivo de configuración del paquete:
```bash
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```
## 3. Generar clave secreta
```
php artisan jwt:secret
```

## 4. Actualizar el model usuario para que logre generar el token

```php
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject{
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
```

## 5. Configurar Auth guard
- Dentro del archivo (config/auth.php), deberá realizar algunos cambios para configurar Laravel para que use la protección jwt para potenciar la autenticación de su aplicación.

```php
'defaults' => [
    'guard' => 'api',
    'passwords' => 'users',
],

...

'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],
```
## 6. Agregue algunas rutas de autenticación básicas
- Primero agreguemos algunas rutas de la routes/api.php siguiente manera:

```php
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, "login"]);
    Route::post('logout', [AuthController::class, "logout"]);
    Route::post('refresh', [AuthController::class, "refresh"]);
    Route::post('me', [AuthController::class, "me"]);

});
```

## 7. Crea el AuthController

```
php artisan make:controller AuthController
```
- Luego agregue lo siguiente:

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }
}

```

## 8. Configurar la Base de datos (.env)

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=backend_proyecto_laravel
DB_USERNAME=root
DB_PASSWORD=
```
## 9. Migrar las tablas por defecto de Laravel

```
php artisan migrate
```

## 10. Levantar el Servidor

```
php artisan serve
```