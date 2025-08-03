using AutoMapper;
using debraApp.Dto;
using debraApp.Interfaces;
using debraApp.Models;
using debraApp.Repository;
using Microsoft.AspNetCore.Mvc;

namespace debraApp.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class CommissionController : Controller
    {
        private readonly ICommissionRepository _commissionRepository;
        private readonly IEventRepository _eventRepository;
        private readonly IMapper _mapper;

        public CommissionController(ICommissionRepository commissionRepository, IEventRepository eventRepository, IMapper mapper)
        {
            _commissionRepository = commissionRepository;
            _eventRepository = eventRepository;
            _mapper = mapper;
        }


        [HttpGet]
        [ProducesResponseType(200, Type = typeof(IEnumerable<Commission>))]
        public IActionResult GetCommissions()
        {
            var commissions = _mapper.Map<List<CommissionDto>>(_commissionRepository.GetCommissions());

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(commissions);
        }

        [HttpGet("partner/{partnerID}")]
        [ProducesResponseType(200, Type = typeof(IEnumerable<CommissionDto>))]
        [ProducesResponseType(404)]
        public IActionResult GetCommissionsByPartnerID(int partnerID)
        {
            // Fetch events associated with the specified partnerID
            var events = _eventRepository.GetEvents().Where(e => e.PartnerID == partnerID);

            if (events == null || !events.Any())
            {
                return NotFound("No events found for the specified partner.");
            }

            // Collect event IDs for filtering commissions
            var eventIds = events.Select(e => e.EventID).ToList();

            // Fetch commissions associated with the collected event IDs
            var commissions = _mapper.Map<List<CommissionDto>>(
                _commissionRepository.GetCommissions().Where(c => eventIds.Contains(c.EventID)));

            if (commissions == null || !commissions.Any())
            {
                return NotFound("No commissions found for the specified partner.");
            }

            return Ok(commissions);
        }

        [HttpGet("{commissionID}")]
        [ProducesResponseType(200, Type = typeof(Commission))]
        [ProducesResponseType(400)]
        public IActionResult GetCommission(int commissionID)
        {
            if (!_commissionRepository.CommissionExists(commissionID))
                return NotFound();

            var commission = _mapper.Map<CommissionDto>(_commissionRepository.GetCommission(commissionID));

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(commission);
        }



        [HttpPost]
        [ProducesResponseType(204)]
        [ProducesResponseType(400)]
        public IActionResult CreateCommission([FromBody] CommissionDto commissionCreate)
        {
            if (commissionCreate == null)
                return BadRequest(ModelState);

            var commission = _commissionRepository.GetCommissions().Where(c => c.EventID == commissionCreate.EventID)
                .FirstOrDefault();

            if (commission != null)
            {
                ModelState.AddModelError("", " already exists");
                return StatusCode(422, ModelState);
            }

            if (!ModelState.IsValid)
                return BadRequest(ModelState);

            var commissionMap = _mapper.Map<Commission>(commissionCreate);

            commissionMap.Event = _eventRepository.GetEvent(commissionMap.EventID);

            if (!_commissionRepository.CreateCommission(commissionMap))
            {
                ModelState.AddModelError("", "Something went wrong while saving");
                return StatusCode(500, ModelState);
            }

            return Ok("Successfully Created!");
        }



        [HttpPut("{commissionID}")]
        [ProducesResponseType(400)]
        [ProducesResponseType(204)]
        [ProducesResponseType(404)]
        public IActionResult UpdateCommission(int commissionID, [FromBody] CommissionDto updatedCommission)
        {
            if (updatedCommission == null)
            {
                return BadRequest(ModelState);
            }

            if (commissionID != updatedCommission.CommissionID)
            {
                return BadRequest(ModelState);
            }

            if (!_commissionRepository.CommissionExists(commissionID))
            {
                return NotFound();
            }

            if (!ModelState.IsValid)
            {
                return BadRequest();
            }

            var commissionMap = _mapper.Map<Commission>(updatedCommission);

            if (!_eventRepository.EventExists(updatedCommission.EventID))
            {
                ModelState.AddModelError("", "Event Not Found!");
                return StatusCode(500, ModelState);
            }

            commissionMap.Event = _eventRepository.GetEvent(commissionMap.EventID);

            if (!_commissionRepository.UpdateCommission(commissionMap))
            {
                ModelState.AddModelError("", "Something went wrong updating");
                return StatusCode(500, ModelState);
            }

            return NoContent();
        }


        [HttpDelete("{commissionID}")]
        [ProducesResponseType(400)]
        [ProducesResponseType(204)]
        [ProducesResponseType(404)]
        public IActionResult DeleteTicket(int commissionID)
        {
            if (!_commissionRepository.CommissionExists(commissionID))
            {
                return NotFound();
            }


            var commissionToDelete = _commissionRepository.GetCommission(commissionID);

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            if (!_commissionRepository.DeleteCommission(commissionToDelete))
            {
                ModelState.AddModelError("", "Something went wrong deleting");
                return StatusCode(500, ModelState);
            }

            return NoContent();
        }



    }
}
