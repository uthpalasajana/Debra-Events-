using debraApp.Interfaces;
using debraApp.Models;
using Microsoft.AspNetCore.Mvc;

namespace debraApp.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class UserController : ControllerBase
    {
        private readonly IUserRepository _userRepository;

        public UserController(IUserRepository userRepository)
        {
            _userRepository = userRepository;
        }

        [HttpGet("{email}")]
        public IActionResult GetUserByEmail(string email)
        {
            var user = _userRepository.GetUserByEmail(email);
            if (user == null)
                return NotFound("User not found");

            return Ok(user);
        }

        [HttpPost("checkpassword")]
        [ProducesResponseType(200)]
        [ProducesResponseType(400)]
        [ProducesResponseType(401)]
        [ProducesResponseType(404)]
        public IActionResult CheckPassword([FromBody] CheckPasswordRequestUser request)
        {
            if (request == null)
                return BadRequest("Request is null");

            if (string.IsNullOrWhiteSpace(request.Email))
                return BadRequest("Email is required");

            if (string.IsNullOrWhiteSpace(request.Password))
                return BadRequest("Password is required");

            var user = _userRepository.GetUserByEmail(request.Email);
            if (user == null)
                return NotFound("User not found");

            var isPasswordValid = _userRepository.CheckPassword(user, request.Password);
            if (!isPasswordValid)
                return Unauthorized("Invalid password");

            return Ok("Password is correct");
        }
    }

    public class CheckPasswordRequestUser
    {
        public string Email { get; set; }
        public string Password { get; set; }
    }
}