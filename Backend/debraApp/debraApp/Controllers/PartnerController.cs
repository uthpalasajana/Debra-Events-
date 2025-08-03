using AutoMapper;
using debraApp.Dto;
using debraApp.Interfaces;
using debraApp.Models;
using Microsoft.AspNetCore.Mvc;

namespace debraApp.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class PartnerController : Controller
    {
        private readonly IPartnerRepository _partnerRepository;
        private readonly IMapper _mapper;

        public PartnerController(IPartnerRepository partnerRepository, IMapper mapper)
        {
            _partnerRepository = partnerRepository;
            _mapper = mapper;
        }

        [HttpGet]
        [ProducesResponseType(200, Type = typeof(IEnumerable<Partner>))]
        public IActionResult GetPartners()
        {
            var partners = _mapper.Map<List<PartnerDto>>(_partnerRepository.GetPartners());

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(partners);
        }

        [HttpGet("{partnerID}")]
        [ProducesResponseType(200, Type = typeof(Partner))]
        [ProducesResponseType(400)]
        public IActionResult GetPartner(int partnerID)
        {
            if (!_partnerRepository.PartnerExists(partnerID))
                return NotFound();

            var partner = _mapper.Map<PartnerDto>(_partnerRepository.GetPartner(partnerID));

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(partner);
        }

        [HttpGet("email/{email}")]
        [ProducesResponseType(200, Type = typeof(Partner))]
        [ProducesResponseType(404)]
        public IActionResult GetPartnerByEmail(string email)
        {
            var partner = _partnerRepository.GetPartnerByEmail(email);
            if (partner == null)
                return NotFound("Partner not found");

            return Ok(_mapper.Map<PartnerDto>(partner));
        }

        [HttpPost("checkpassword")]
        [ProducesResponseType(200)]
        [ProducesResponseType(404)]
        [ProducesResponseType(401)]
        public IActionResult CheckPassword([FromBody] CheckPasswordRequest request)
        {
            var partner = _partnerRepository.GetPartnerByEmail(request.Email);
            if (partner == null)
                return NotFound("Partner not found");

            var isPasswordValid = _partnerRepository.CheckPassword(partner, request.Password);
            if (!isPasswordValid)
                return Unauthorized("Invalid password");

            return Ok("Password is correct");
        }

        [HttpPost]
        [ProducesResponseType(204)]
        [ProducesResponseType(400)]
        public IActionResult CreatePartner([FromBody] PartnerDto partnerCreate)
        {
            if (partnerCreate == null)
                return BadRequest(ModelState);

            var partner = _partnerRepository.GetPartners().Where(p => p.Email.Trim().ToLower() == partnerCreate.Email.TrimEnd().ToLower())
                .FirstOrDefault();

            if (partner != null)
            {
                ModelState.AddModelError("", "Partner already exists");
                return StatusCode(422, ModelState);
            }

            if (!ModelState.IsValid)
                return BadRequest(ModelState);

            var partnerMap = _mapper.Map<Partner>(partnerCreate);

            if (!_partnerRepository.CreatePartner(partnerMap))
            {
                ModelState.AddModelError("", "Something went wrong while saving");
                return StatusCode(500, ModelState);
            }

            return Ok("Successfully Created!");
        }

        [HttpPut("{partnerID}")]
        [ProducesResponseType(400)]
        [ProducesResponseType(204)]
        [ProducesResponseType(404)]
        public IActionResult UpdatePartner(int partnerID, [FromBody] PartnerDto updatedPartner)
        {
            if (updatedPartner == null)
            {
                return BadRequest(ModelState);
            }

            if (partnerID != updatedPartner.PartnerID)
            {
                return BadRequest(ModelState);
            }

            if (!_partnerRepository.PartnerExists(partnerID))
            {
                return NotFound();
            }

            if (!ModelState.IsValid)
            {
                return BadRequest();
            }

            var partnerMap = _mapper.Map<Partner>(updatedPartner);

            if (!_partnerRepository.UpdatePartner(partnerMap))
            {
                ModelState.AddModelError("", "Something went wrong updating paymany");
                return StatusCode(500, ModelState);
            }

            return NoContent();
        }

        [HttpDelete("{partnerID}")]
        [ProducesResponseType(400)]
        [ProducesResponseType(204)]
        [ProducesResponseType(404)]
        public IActionResult DeletePartner(int partnerID)
        {
            if (!_partnerRepository.PartnerExists(partnerID))
            {
                return NotFound();
            }

            var partnerToDelete = _partnerRepository.GetPartner(partnerID);

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            if (!_partnerRepository.DeletePartner(partnerToDelete))
            {
                ModelState.AddModelError("", "Something went wrong deleting");
                return StatusCode(500, ModelState);
            }

            return NoContent();
        }
    }

    public class CheckPasswordRequest
    {
        public string Email { get; set; }
        public string Password { get; set; }
    }
}
