@include('nav')

<h3>Reset Password</h3>

<form action="{{ route('reset_password_submit') }}" method="post">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">

    <div>New Password</div>
    <div>
        <input type="password" name="new_password">
    </div>

    <div>Retype Password</div>
    <div>
        <input type="password" name="retype_password">
    </div>
    
    <div style="margin-top: 10px;">
        <input type="submit" value="Update">
        <br>
    </div>
</form>