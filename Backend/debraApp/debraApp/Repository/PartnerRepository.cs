using debraApp.DAL;
using debraApp.Interfaces;
using debraApp.Models;
using System.Linq;

namespace debraApp.Repository
{
    public class PartnerRepository : IPartnerRepository
    {
        private readonly DataContext _context;

        public PartnerRepository(DataContext context)
        {
            _context = context;
        }

        public Partner GetPartner(int id)
        {
            return _context.Partners.Where(p => p.PartnerID == id).FirstOrDefault();
        }

        public ICollection<Partner> GetPartners()
        {
            return _context.Partners.ToList();
        }

        public bool PartnerExists(int partnerID)
        {
            return _context.Partners.Any(p => p.PartnerID == partnerID);
        }

        public bool CreatePartner(Partner partner)
        {
            _context.Add(partner);
            return Save();
        }

        public bool Save()
        {
            var saved = _context.SaveChanges();
            return saved > 0 ? true : false;
        }

        public bool UpdatePartner(Partner partner)
        {
            _context.Update(partner);
            return Save();
        }

        public bool DeletePartner(Partner partner)
        {
            _context.Remove(partner);
            return Save();
        }

        // New methods
        public Partner GetPartnerByEmail(string email)
        {
            return _context.Partners.FirstOrDefault(p => p.Email == email);
        }

        public bool CheckPassword(Partner partner, string password)
        {
            return partner.Password == password;
        }
    }
}
