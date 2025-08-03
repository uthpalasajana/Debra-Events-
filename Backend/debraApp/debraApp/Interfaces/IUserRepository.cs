using debraApp.Models;

namespace debraApp.Interfaces
{
    public interface IUserRepository
    {
        User GetUserByEmail(string email);
        bool CheckPassword(User user, string password);
    }
}