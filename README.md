# Laravel Verification Email

Application built in Laravel which implements verification email upon new user registration. The application uses:

 - `SendVerificationCode` Notifications via email
 - `Registered` event to send emails
 
 
## Routes
```
Route::get('/email/verify/resend/{email}', 'Auth\RegisterController@resend'); // TODO rethink about this
Route::get('/email/verify/{code}', 'Auth\RegisterController@verify');
```
