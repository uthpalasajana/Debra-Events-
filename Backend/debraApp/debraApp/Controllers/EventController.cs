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
    public class EventController : Controller
    {
        private readonly IEventRepository _eventRepository;
        private readonly IPartnerRepository _partnerRepository;
        private readonly IMapper _mapper;

        public EventController(IEventRepository eventRepository, IPartnerRepository partnerRepository, IMapper mapper)
        {
            _eventRepository = eventRepository;
            _partnerRepository = partnerRepository;
            _mapper = mapper;
        }

        [HttpGet]
        [ProducesResponseType(200, Type = typeof(IEnumerable<Event>))]
        public IActionResult GetEvents() 
        {
            var events = _mapper.Map<List<EventDto>>(_eventRepository.GetEvents());

            if(!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(events);
        }

        [HttpGet("{eventID}")]
        [ProducesResponseType(200, Type = typeof(Event))]
        [ProducesResponseType(400)]
        public IActionResult GetEvent(int eventID) 
        {
            if(!_eventRepository.EventExists(eventID))
                return NotFound();

            var _event = _mapper.Map<EventDto>(_eventRepository.GetEvent(eventID));

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(_event);
        }

        [HttpGet("partner/{partnerID}")]
        [ProducesResponseType(200, Type = typeof(IEnumerable<EventDto>))]
        [ProducesResponseType(404)]
        public IActionResult GetEventsByPartnerID(int partnerID)
        {
            // Fetch events associated with the specified partnerID
            var events = _mapper.Map<List<EventDto>>(
                _eventRepository.GetEvents().Where(e => e.PartnerID == partnerID));

            if (events == null || events.Count == 0)
            {
                return NotFound();
            }

            return Ok(events);
        }

        [HttpGet("eventID/{eventName}")]
        [ProducesResponseType(200, Type = typeof(Event))]
        [ProducesResponseType(400)]
        public IActionResult GetEventName(string eventName)
        {

            var _event = _mapper.Map<EventDto>(_eventRepository.GetEvent(eventName));

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(_event);
        }


        [HttpGet("ticket/{eventID}")]
        [ProducesResponseType(200, Type = typeof(IEnumerable<Ticket>))]
        [ProducesResponseType(400)]
        public IActionResult GetTicketsByEventID(int eventID)
        {
            var tickets = _mapper.Map<List<TicketDto>>(
                _eventRepository.GetTicketByEvent(eventID));

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(tickets);
        }

        [HttpPost("upload")]
        [ProducesResponseType(200)]
        [ProducesResponseType(400)]
        public async Task<IActionResult> UploadImage([FromForm] IFormFile file)
        {
            if (file == null || file.Length == 0)
                return BadRequest("Upload a valid file");

            var filePath = Path.Combine("wwwroot/images", file.FileName);

            // Create directory if it doesn't exist
            if (!Directory.Exists(Path.Combine("wwwroot/images")))
            {
                Directory.CreateDirectory(Path.Combine("wwwroot/images"));
            }

            using (var stream = new FileStream(filePath, FileMode.Create))
            {
                await file.CopyToAsync(stream);
            }

            var imageUrl = $"/images/{file.FileName}";
            return Ok(new { Url = imageUrl });
        }

        [HttpGet("lastEventID")]
        [ProducesResponseType(200, Type = typeof(int))]
        [ProducesResponseType(404)]
        public IActionResult GetLastEventID()
        {
            var lastEvent = _eventRepository.GetEvents().OrderByDescending(e => e.EventID).FirstOrDefault();

            if (lastEvent == null)
            {
                return NotFound();
            }

            return Ok(lastEvent.EventID);
        }


        [HttpPost]
        [ProducesResponseType(204)]
        [ProducesResponseType(400)]
        public IActionResult CreateEvent([FromForm] EventDto eventCreate)
        {
            if (eventCreate == null)
                return BadRequest(ModelState);

            var _event = _eventRepository.GetEvents()
                .FirstOrDefault(e => e.EventName.Trim().ToLower() == eventCreate.EventName.TrimEnd().ToLower());

            if (_event != null)
            {
                ModelState.AddModelError("", "Event already exists");
                return StatusCode(422, ModelState);
            }

            if (!ModelState.IsValid)
                return BadRequest(ModelState);

            var eventMap = _mapper.Map<Event>(eventCreate);

            eventMap.Partner = _partnerRepository.GetPartner(eventMap.PartnerID);

            

            if (!_eventRepository.CreateEvent(eventMap))
            {
                ModelState.AddModelError("", "Something went wrong while saving");
                return StatusCode(500, ModelState);
            }

            return Ok("Successfully Created!");
        }


        [HttpPut("{eventID}")]
        [ProducesResponseType(400)]
        [ProducesResponseType(204)]
        [ProducesResponseType(404)]
        public IActionResult UpdatePartner(int eventID, [FromBody] EventDto updatedEvent)
        {
            if (updatedEvent == null)
            {
                return BadRequest(ModelState);
            }

            if (eventID != updatedEvent.EventID)
            {
                return BadRequest(ModelState);
            }

            if (!_eventRepository.EventExists(eventID))
            {
                return NotFound();
            }

            if (!ModelState.IsValid)
            {
                return BadRequest();
            }

            var eventMap = _mapper.Map<Event>(updatedEvent);

            if (!_partnerRepository.PartnerExists(updatedEvent.PartnerID))
            {
                ModelState.AddModelError("", "Event Not Found!");
                return StatusCode(500, ModelState);
            }

            eventMap.Partner = _partnerRepository.GetPartner(eventMap.PartnerID);

            

            if (!_eventRepository.UpdateEvent(eventMap))
            {
                ModelState.AddModelError("", "Something went wrong updating");
                return StatusCode(500, ModelState);
            }

            return NoContent();
        }

        [HttpDelete("{eventID}")]
        [ProducesResponseType(400)]
        [ProducesResponseType(204)]
        [ProducesResponseType(404)]
        public IActionResult DeleteEvent(int eventID)
        {
            if (!_eventRepository.EventExists(eventID))
            {
                return NotFound();
            }


            var eventToDelete = _eventRepository.GetEvent(eventID);

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            if (!_eventRepository.DeleteEvent(eventToDelete))
            {
                ModelState.AddModelError("", "Something went wrong deleting");
                return StatusCode(500, ModelState);
            }

            return NoContent();
        }



    }

    
}
