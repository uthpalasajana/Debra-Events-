using debraApp.DAL;
using debraApp.Interfaces;
using debraApp.Models;
using System.Linq;

namespace debraApp.Repository
{
    public class UserRepository : IUserRepository
    {
        private readonly DataContext _context;

        public UserRepository(DataContext context)
        {
            _context = context;
        }

        public User GetUserByEmail(string email)
        {
            return _context.Users.FirstOrDefault(u => u.Email == email);
        }

        public bool CheckPassword(User user, string password)
        {
            return user.Password == password;
        }
    }
}
