@include('nav')

<h3>Forget Password</h3>

<form action="{{ route('forget_password_submit') }}" method="post">
    @csrf

    <div>Email Address</div>
    <div>
        <input type="text" name="email">
    </div>
    
    <div style="margin-top: 10px;">
        <input type="submit" value="Submit">
        <br>
        <a href="{{ route('login') }}">Back to Login Page</a>
    </div>
</form>