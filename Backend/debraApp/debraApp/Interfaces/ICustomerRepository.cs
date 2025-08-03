using debraApp.Models;

namespace debraApp.Interfaces
{
    public interface ICustomerRepository
    {
        ICollection<Customer> GetCustomers();
        Customer GetCustomer(int id);
        bool CustomerExists(int customerID);

        bool CreateCustomer(Customer customer);
        bool Save();

        bool UpdateCustomer(Customer customer);
        bool DeleteCustomer(Customer customer);

        Customer GetLastInsertedCustomer();
    }
}
