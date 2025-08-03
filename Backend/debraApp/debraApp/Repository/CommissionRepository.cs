using debraApp.DAL;
using debraApp.Interfaces;
using debraApp.Models;

namespace debraApp.Repository
{
    public class CommissionRepository : ICommissionRepository
    {
        private readonly DataContext _context;

        public CommissionRepository(DataContext context)
        {
            _context = context;
        }
        public bool CommissionExists(int commissionID)
        {
            return _context.Commissions.Any(c => c.CommissionID == commissionID);
        }

        public ICollection<Commission> GetCommissions()
        {
            return _context.Commissions.OrderBy(c => c.CommissionID).ToList();
        }

        public Commission GetCommission(int id)
        {
            return _context.Commissions.Where(c => c.CommissionID == id).FirstOrDefault();
        }

        public bool CreateCommission(Commission commission)
        {
            _context.Add(commission);

            return Save();
        }

        public bool Save()
        {
            var saved = _context.SaveChanges();
            return saved > 0 ? true : false;
        }

        public bool UpdateCommission(Commission commission)
        {
            _context.Update(commission);

            return Save();
        }

        public bool DeleteCommission(Commission commission)
        {
            _context.Remove(commission);

            return Save();
        }
    }
}
