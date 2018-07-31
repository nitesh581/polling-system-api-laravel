# laravel_poll_api

For any issues contact @nitesh on slack

<h1>Api's for poll management system</h1>

<p><b>1. Add User</b></p>
<p>http://dev.hr.excellencetechnologies.in:8000/add_user</p>
<p>Request Type - POST</p>
<p>Response User Created - 
{
  "error": 0,
  "data": 
  {
      "name": "Nitesh",
      "email": "nitesh@gmail.com",
      "role": "admin",
      "api_token": "kwKpKNoNjFL8RUnj1i11jkWUNJykntdby2rvsy25ZpkLO9u7hQUOaVdF7GWU",
      "id": 1
  }
}
</p>

<p>Response User already exists - { "error": 1, "message": "User already exist." } </p>
<br>

<p><b>2. Login User</b></p>
<p>http://dev.hr.excellencetechnologies.in:8000/login</p>
<p>Request Type - POST</p>
<p>Response User Logged In - 
{
  "error": 0,
  "data": 
  {
      "id": 1,
      "name": "Nitesh",
      "email": "nitesh@gmail.com",
      "role": "admin",
      "api_token": "J9wPwYX31gH6LzF47E2GZR0adbINQRtbH10WEA8EPTCUofw6QNRPUdmfWEnz",
  }
}
</p>

<p>Response Login Failed - { "error": 1, "message": "Invalid Username or Password." } </p>
<br>

<p><b>3. List Users</b></p>
<p>http://dev.hr.excellencetechnologies.in:8000/list_users</p>
<p>Request Type - GET</p>
<p>Response List - 
{
  "error": 0,
  "data": 
  {
      "id": 1,
      "name": "Nitesh",
      "email": "nitesh@gmail.com",
      "role": "admin",
  }
}
</p>

<p>Response Login Failed - { "error": 1, "message": "You are not an admin." } </p>
<br>
       

