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
    public class CustomerController : Controller
    {
        private readonly ICustomerRepository _customerRepository;
        private readonly IMapper _mapper;

        public CustomerController(ICustomerRepository customerRepository, IMapper mapper)
        {
            _customerRepository = customerRepository;
            _mapper = mapper;
        }

        [HttpGet]
        [ProducesResponseType(200, Type = typeof(IEnumerable<Customer>))]
        public IActionResult GetEvents()
        {
            var customers = _mapper.Map<List<CustomerDto>>(_customerRepository.GetCustomers());

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(customers);
        }

        [HttpGet("{customerID}")]
        [ProducesResponseType(200, Type = typeof(Customer))]
        [ProducesResponseType(400)]
        public IActionResult GetEvent(int customerID)
        {
            if (!_customerRepository.CustomerExists(customerID))
                return NotFound();

            var customer = _mapper.Map<CustomerDto>(_customerRepository.GetCustomer(customerID));

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(customer);
        }

        [HttpGet("lastInserted")]
        [ProducesResponseType(200, Type = typeof(Customer))]
        [ProducesResponseType(400)]
        public IActionResult GetLastInsertedCustomer()
        {
            var customer = _mapper.Map<CustomerDto>(_customerRepository.GetLastInsertedCustomer());

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            return Ok(customer);
        }


        [HttpPost]
        [ProducesResponseType(204)]
        [ProducesResponseType(400)]
        public IActionResult CreateCustomer([FromBody] CustomerDto customerCreate)
        {
            if (customerCreate == null)
                return BadRequest(ModelState);

           

            var customerMap = _mapper.Map<Customer>(customerCreate);

            if (!_customerRepository.CreateCustomer(customerMap))
            {
                ModelState.AddModelError("", "Something went wrong while saving");
                return StatusCode(500, ModelState);
            }

            return Ok("Successfully Created!");
        }


        [HttpPut("{customerID}")]
        [ProducesResponseType(400)]
        [ProducesResponseType(204)]
        [ProducesResponseType(404)]
        public IActionResult UpdatePartner(int customerID, [FromBody] CustomerDto updatedCustomer)
        {
            if (updatedCustomer == null)
            {
                return BadRequest(ModelState);
            }

            if (customerID != updatedCustomer.CustomerID)
            {
                return BadRequest(ModelState);
            }

            if (!_customerRepository.CustomerExists(customerID))
            {
                return NotFound();
            }

            if (!ModelState.IsValid)
            {
                return BadRequest();
            }

            var customerMap = _mapper.Map<Customer>(updatedCustomer);

            if (!_customerRepository.UpdateCustomer(customerMap))
            {
                ModelState.AddModelError("", "Something went wrong updating");
                return StatusCode(500, ModelState);
            }

            return NoContent();
        }


        [HttpDelete("{customerID}")]
        [ProducesResponseType(400)]
        [ProducesResponseType(204)]
        [ProducesResponseType(404)]
        public IActionResult DeleteCustomer(int customerID)
        {
            if (!_customerRepository.CustomerExists(customerID))
            {
                return NotFound();
            }


            var customerToDelete = _customerRepository.GetCustomer(customerID);

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            if (!_customerRepository.DeleteCustomer(customerToDelete))
            {
                ModelState.AddModelError("", "Something went wrong deleting");
                return StatusCode(500, ModelState);
            }

            return NoContent();
        }

    }
}
