# laravel_poll_api

For any issues contact @nitesh on slack

<h1>Api's for poll management system</h1>

<p><b>1. Add User</b></p>
<p>http://dev.hr.excellencetechnologies.in:8000/add_user</p>
<p>Request Type - POST</p>
<p>Body - </p>
{
      "name": "Nitesh",
      "email": "nitesh@gmail.com",
      "password": "nitesh",
      "role": "admin",
}
<br>
<p>Response User Created - 
{
  "error": 0,
  "data": 
  {
      "name": "Nitesh",
      "email": "nitesh@gmail.com",
      "password": "nitesh",
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
      "password": "nitesh",
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
      "password": "nitesh",
      "role": "admin",
  }
}
</p>

<p>Response Failed - { "error": 1, "message": "You are not an admin." } </p>
<br>

<p><b>4. Add Poll</b></p>
<p>http://dev.hr.excellencetechnologies.in:8000/add_poll</p>
<p>Request Type - POST</p>
<p>Response List - 
{
  "error": 0,
  "data": 
  {
      "id": 3,
      "title": "Dummy poll",
      "options": [
            {
                "options": "demo1",
                "vote": 0
            },
            {
                "options": "demo2",
                "vote": 0
            },
            {
                "options": "demo3",
                "vote": 0
            },
            {
                "options": "demo4",
                "vote": 0
            }
        ]
  }
}
</p>
<br>

<p><b>5. List Polls</b></p>
<p>http://dev.hr.excellencetechnologies.in:8000/list_polls</p>
<p>Request Type - GET</p>
<p>Response List - 
{
    "error": 0,
    "data": [
        {
            "id": 1,
            "title": "Default Poll",
            "options": [
                {
                    "options": "opt1",
                    "vote": 5
                },
                {
                    "options": "opt2",
                    "vote": 0
                },
                {
                    "options": "opt3",
                    "vote": 0
                },
                {
                    "options": "opt4",
                    "vote": 0
                }
            ]
        },
        {
            "id": 2,
            "title": "Default Poll",
            "options": [
                {
                    "options": "opt1",
                    "vote": 0
                },
                {
                    "options": "opt2",
                    "vote": 0
                },
                {
                    "options": "opt3",
                    "vote": 0
                },
                {
                    "options": "opt4",
                    "vote": 0
                }
            ]
        },
        {
            "id": 3,
            "title": "Dummy poll",
            "options": [
                {
                    "options": "demo2",
                    "vote": 0
                },
                {
                    "options": "demo2",
                    "vote": 0
                },
                {
                    "options": "demo3",
                    "vote": 0
                },
                {
                    "options": "demo4",
                    "vote": 0
                }
            ]
        }
    ]
}
</p>

<p>Response Failed - { "error": 1, "message": "You are not an admin." } </p>
<br>

<p><b>6. List Single Poll</b></p>
<p>http://dev.hr.excellencetechnologies.in:8000/list_poll/{poll_id}</p>
<p>Request Type - GET</p>
<p>Response List - 
{
    "error": 0,
    "data": {
        "id": 1,
        "title": "Default Poll",
        "options": [
            {
                "options": "opt1",
                "vote": 5
            },
            {
                "options": "opt2",
                "vote": 0
            },
            {
                "options": "opt3",
                "vote": 0
            },
            {
                "options": "opt4",
                "vote": 0
            }
        ]
    }
}
</p>

<p>Response Failed - { "error": 1, "message": "You are not an admin." } </p>
<br>

<p><b>7. Vote Api</b></p>
<p>http://dev.hr.excellencetechnologies.in:8000/vote/{poll_id}/{opt_id}</p>
<p>Request Type - PUT</p>
<p>Response - 
{
    "error": 0,
    "data": {
        "id": "1",
        "title": "Default Poll",
        "option_id": "1",
        "option": "opt1",
        "vote": 6
    }
}
</p>
<br>

<p><b>8. Add Poll Option</b></p>
<p>http://dev.hr.excellencetechnologies.in:8000/add_poll_option/{poll_id}</p>
<p>Request Type - POST</p>
<p>Response - 
{
    "error": 0,
    "data": {
        "poll_id": "1",
        "options": "optdemo",
        "id": 13
    }
}
</p>

<p>Response Failed - { "error": 1, "message": "No polls found to add option." } </p>
<br>

<p><b>9. Delete Poll Option</b></p>
<p>http://dev.hr.excellencetechnologies.in:8000/delete_poll_option/{poll_id}/{opt_id}</p>
<p>Request Type - DELETE</p>
<p>Response - 
{
    "error": 0,
    "data": "Poll Option Deleted Successfully."
}
</p>

<p>Response Failed - { "error": 1, "message": "No options found to delete." } </p>
<br>

<p><b>10. Update Poll Title</b></p>
<p>http://dev.hr.excellencetechnologies.in:8000/update_poll_title/{poll_id}</p>
<p>Request Type - PUT</p>
<p>Response - 
{
    "error": 0,
    "data": {
        "id": 5,
        "user_id": 1,
        "title": "Nitesh Poll Demo",
        "created_at": "2018-08-01 06:28:55",
        "updated_at": "2018-08-01 11:54:56"
    }
}
</p>

<p>Response Failed - { "error": 1, "message": "No polls found to update." } </p>
<br>

<p><b>11. Delete Poll</b></p>
<p>http://dev.hr.excellencetechnologies.in:8000/delete_poll/{poll_id}</p>
<p>Request Type - DELETE</p>
<p>Response - 
{
    "error": 0,
    "data": "Poll Deleted Successfully"
}
</p>

<p>Response Failed - { "error": 1, "message": "No polls found to delete." } </p>
<br>
       
