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
    public class SaleController : Controller
    {
        private readonly ISaleRepository _saleRepository;
        private readonly ITicketRepository _ticketRepository;
        private readonly ICustomerRepository _customerRepository;
        private readonly IMapper _mapper;

        public SaleController(ISaleRepository saleRepository, ITicketRepository ticketRepository, ICustomerRepository customerRepository, IMapper mapper)
        {
            _saleRepository = saleRepository;
            _ticketRepository = ticketRepository;
            _customerRepository = customerRepository;
            _mapper = mapper;
        }

        [HttpGet]
        [ProducesResponseType(200, Type = typeof(IEnumerable<Sale>))]
        public IActionResult GetEvents()
        {
            var sales = _mapper.Map<List<SaleDto>>(_saleRepository.GetSales());

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(sales);
        }


        [HttpGet("{saleID}")]
        [ProducesResponseType(200, Type = typeof(Sale))]
        [ProducesResponseType(400)]
        public IActionResult GetEvent(int saleID)
        {
            if (!_saleRepository.SaleExists(saleID))
                return NotFound();

            var sale = _mapper.Map<SaleDto>(_saleRepository.GetSale(saleID));

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(sale);
        }


        [HttpGet("customer/{customerID}")]
        [ProducesResponseType(200, Type = typeof(IEnumerable<SaleDto>))]
        [ProducesResponseType(400)]
        public IActionResult GetSalesByCustomer(int customerID)
        {
            var sales = _mapper.Map<List<SaleDto>>(_saleRepository.GetSales().Where(s => s.CustomerID == customerID));

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(sales);
        }


        [HttpPost]
        [ProducesResponseType(204)]
        [ProducesResponseType(400)]
        public IActionResult CreateSale([FromBody] SaleDto saleCreate)
        {
            if (saleCreate == null)
                return BadRequest(ModelState);

            var sale = _saleRepository.GetSales()
             .Where(t => t.TicketID == saleCreate.TicketID && t.TicketNumber == saleCreate.TicketNumber)
             .FirstOrDefault();

            if (sale != null)
            {
                ModelState.AddModelError("", "Sale already exists");
                return StatusCode(422, ModelState);
            }

            if (!ModelState.IsValid)
                return BadRequest(ModelState);

            var saleMap = _mapper.Map<Sale>(saleCreate);

            saleMap.Ticket = _ticketRepository.GetTicket(saleMap.TicketID);
            saleMap.Customer = _customerRepository.GetCustomer(saleMap.CustomerID);

            if (!_saleRepository.CreateSale(saleMap))
            {
                ModelState.AddModelError("", "Something went wrong while saving");
                return StatusCode(500, ModelState);
            }

            return Ok("Successfully Created!");
        }




        [HttpPut("{saleID}")]
        [ProducesResponseType(400)]
        [ProducesResponseType(204)]
        [ProducesResponseType(404)]
        public IActionResult UpdateSale(int saleID, [FromBody] SaleDto updatedSale)
        {
            if (updatedSale == null)
            {
                return BadRequest(ModelState);
            }

            if (saleID != updatedSale.SaleID)
            {
                return BadRequest(ModelState);
            }

            if (!_saleRepository.SaleExists(saleID))
            {
                return NotFound();
            }

            if (!ModelState.IsValid)
            {
                return BadRequest();
            }

            var saleMap = _mapper.Map<Sale>(updatedSale);

            if (!_ticketRepository.TicketExists(updatedSale.TicketID))
            {
                ModelState.AddModelError("", "Ticket Not Found!");
                return StatusCode(500, ModelState);
            }

            saleMap.Ticket = _ticketRepository.GetTicket(saleMap.TicketID);

            if (!_ticketRepository.TicketExists(updatedSale.CustomerID))
            {
                ModelState.AddModelError("", "Customer Not Found!");
                return StatusCode(500, ModelState);
            }

            saleMap.Customer = _customerRepository.GetCustomer(saleMap.CustomerID);

            if (!_saleRepository.UpdateSale(saleMap))
            {
                ModelState.AddModelError("", "Something went wrong updating");
                return StatusCode(500, ModelState);
            }

            return NoContent();
        }


        [HttpDelete("{saleID}")]
        [ProducesResponseType(400)]
        [ProducesResponseType(204)]
        [ProducesResponseType(404)]
        public IActionResult DeleteSale(int saleID)
        {
            if (!_saleRepository.SaleExists(saleID))
            {
                return NotFound();
            }


            var saleToDelete = _saleRepository.GetSale(saleID);

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            if (!_saleRepository.DeleteSale(saleToDelete))
            {
                ModelState.AddModelError("", "Something went wrong deleting");
                return StatusCode(500, ModelState);
            }

            return NoContent();
        }

    }
}
