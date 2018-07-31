# laravel_poll_api

For any issues contact @nitesh on slack

<h1>Api's for poll management system</h1>

<b>1. Add User</b>
http://dev.hr.excellencetechnologies.in:8000/add_user
Request Type - POST
Response User Created - 
{
  "error": 0,
  "data": 
  {
      "name": "Nitesh",
      "email": "nitesh@gmail.com",
      "role": "admin",
      "api_token": "kwKpKNoNjFL8RUnj1i11jkWUNJykntdby2rvsy25ZpkLO9u7hQUOaVdF7GWU",
      "updated_at": "2018-07-31 11:38:50",
      "created_at": "2018-07-31 11:38:50",
      "id": 1
  }
}

Response User already exists - { "error": 1, "message": "User already exist." }
       

