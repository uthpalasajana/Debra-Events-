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
    public class TicketController : Controller
    {
        private readonly ITicketRepository _ticketRepository;
        private readonly IEventRepository _eventRepository;
        private readonly IMapper _mapper;

        public TicketController(ITicketRepository ticketRepository, IEventRepository eventRepository, IMapper mapper)
        {
            _ticketRepository = ticketRepository;
            _eventRepository = eventRepository;
            _mapper = mapper;
        }

        [HttpGet]
        [ProducesResponseType(200, Type = typeof(IEnumerable<Ticket>))]
        public IActionResult GetEvents()
        {
            var tickets = _mapper.Map<List<TicketDto>>(_ticketRepository.GetTickets());

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(tickets);
        }


        [HttpGet("{ticketID}")]
        [ProducesResponseType(200, Type = typeof(Ticket))]
        [ProducesResponseType(400)]
        public IActionResult GetTicket(int ticketID)
        {
            if (!_ticketRepository.TicketExists(ticketID))
                return NotFound();

            var ticket = _mapper.Map<TicketDto>(_ticketRepository.GetTicket(ticketID));

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(ticket);
        }


        [HttpPost]
        [ProducesResponseType(204)]
        [ProducesResponseType(400)]
        public IActionResult CreateTicket([FromBody] TicketDto ticketCreate)
        {
            if (ticketCreate == null)
                return BadRequest(ModelState);

            

            var ticketMap = _mapper.Map<Ticket>(ticketCreate);

            ticketMap.Event = _eventRepository.GetEvent(ticketMap.EventID);

            if (!_ticketRepository.CreateTicket(ticketMap))
            {
                ModelState.AddModelError("", "Something went wrong while saving");
                return StatusCode(500, ModelState);
            }

            return Ok("Successfully Created!");
        }

        [HttpPut("{ticketID}")]
        [ProducesResponseType(400)]
        [ProducesResponseType(204)]
        [ProducesResponseType(404)]
        public IActionResult UpdatePartner(int ticketID, [FromBody] TicketDto updatedTicket)
        {
            if (updatedTicket == null)
            {
                return BadRequest(ModelState);
            }

            if (ticketID != updatedTicket.TicketID)
            {
                return BadRequest(ModelState);
            }

            if (!_ticketRepository.TicketExists(ticketID))
            {
                return NotFound();
            }

            if (!ModelState.IsValid)
            {
                return BadRequest();
            }

            var ticketMap = _mapper.Map<Ticket>(updatedTicket);

            if (!_eventRepository.EventExists(updatedTicket.EventID))
            {
                ModelState.AddModelError("", "Event Not Found!");
                return StatusCode(500, ModelState);
            }

            ticketMap.Event = _eventRepository.GetEvent(ticketMap.EventID);

            if (!_ticketRepository.UpdateTicket(ticketMap))
            {
                ModelState.AddModelError("", "Something went wrong updating");
                return StatusCode(500, ModelState);
            }

            return NoContent();
        }


        [HttpDelete("{ticketID}")]
        [ProducesResponseType(400)]
        [ProducesResponseType(204)]
        [ProducesResponseType(404)]
        public IActionResult DeleteTicket(int ticketID)
        {
            if (!_ticketRepository.TicketExists(ticketID))
            {
                return NotFound();
            }


            var ticketToDelete = _ticketRepository.GetTicket(ticketID);

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            if (!_ticketRepository.DeleteTicket(ticketToDelete))
            {
                ModelState.AddModelError("", "Something went wrong deleting");
                return StatusCode(500, ModelState);
            }

            return NoContent();
        }

    }


}
