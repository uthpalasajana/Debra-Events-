using debraApp.DAL;
using debraApp.Interfaces;
using debraApp.Models;
using Microsoft.Extensions.Logging;

namespace debraApp.Repository
{
    public class CustomerRepository : ICustomerRepository
    {
        private readonly DataContext _context;

        public CustomerRepository(DataContext context)
        {
            _context = context;
        }

        public bool CreateCustomer(Customer customer)
        {
            _context.Add(customer);

            return Save();
        }

        public bool CustomerExists(int customerID)
        {
            return _context.Customers.Any(c => c.CustomerID == customerID);
        }

        public bool DeleteCustomer(Customer customer)
        {
            _context.Remove(customer);

            return Save();
        }

        public Customer GetCustomer(int id)
        {
            return _context.Customers.Where(c => c.CustomerID == id).FirstOrDefault();
        }

        public ICollection<Customer> GetCustomers()
        {
            return _context.Customers.OrderBy(c => c.CustomerID).ToList();
        }

        public bool Save()
        {
            var saved = _context.SaveChanges();
            return saved > 0 ? true : false;
        }

        public bool UpdateCustomer(Customer customer)
        {
            _context.Update(customer);

            return Save();
        }
        public Customer GetLastInsertedCustomer()
        {
            return _context.Customers.OrderByDescending(c => c.CustomerID).FirstOrDefault();
        }
    }
}
