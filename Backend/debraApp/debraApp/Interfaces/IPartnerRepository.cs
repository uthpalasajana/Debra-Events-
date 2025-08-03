using debraApp.Models;

namespace debraApp.Interfaces
{
    public interface IPartnerRepository
    {
        ICollection<Partner> GetPartners();
        Partner GetPartner(int id);
        bool PartnerExists(int partnerID);
        bool CreatePartner(Partner partner);
        bool Save();
        bool UpdatePartner(Partner partner);
        bool DeletePartner(Partner partner);

        // New methods
        Partner GetPartnerByEmail(string email);
        bool CheckPassword(Partner partner, string password);
    }
}
