using debraApp.Models;

namespace debraApp.Interfaces
{
    public interface ISaleRepository
    {
        ICollection<Sale> GetSales();

        Sale GetSale(int id);

        bool SaleExists(int saleID);

        bool CreateSale(Sale sale);
        bool Save();
        bool UpdateSale(Sale sale);
        bool DeleteSale(Sale sale);

    }
}
