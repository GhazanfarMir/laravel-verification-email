# Laravel Verification Email

Application built in Laravel which implements verification email upon new user registration. The application uses:

 - `SendVerificationCode` Notifications via email
 - `Registered` event to send emails
 
 
## Routes
```
Route::get('/email/verify/resend/{email}', 'Auth\RegisterController@resend'); // TODO rethink about this
```
```
Route::get('/email/verify/{code}', 'Auth\RegisterController@verify');
```

## RegisterController

<a href='https://github.com/ghazanfarmir/laravel-verification-email/blob/master/app/Http/Controllers/Auth/RegisterController.php'>RegisterController.php</a> is overriding `Register` trait method with couple of other methods
