using debraApp.Models;

namespace debraApp.Interfaces
{
    public interface ICommissionRepository
    {
        ICollection<Commission> GetCommissions();
        Commission GetCommission(int id);
        bool CommissionExists(int commissionID);
        bool CreateCommission(Commission commission);
        bool Save();
        bool UpdateCommission(Commission commission);
        bool DeleteCommission(Commission commission);
    }
}
