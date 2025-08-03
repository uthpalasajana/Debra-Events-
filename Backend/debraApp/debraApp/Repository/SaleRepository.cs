using debraApp.DAL;
using debraApp.Interfaces;
using debraApp.Models;

namespace debraApp.Repository
{
    public class SaleRepository : ISaleRepository
    {
        private readonly DataContext _context;

        public SaleRepository(DataContext context)
        {
            _context = context;
        }



        public Sale GetSale(int id)
        {
            return _context.Sales.Where(s => s.SaleID == id).FirstOrDefault();
        }

        public ICollection<Sale> GetSales()
        {
            return _context.Sales.OrderBy(s => s.SaleID).ToList();
        }

        public bool SaleExists(int saleID)
        {
            return _context.Sales.Any(s => s.SaleID == saleID);
        }

        public bool CreateSale(Sale sale)
        {
            _context.Add(sale);

            return Save();
        }

        public bool Save()
        {
            var saved = _context.SaveChanges();
            return saved > 0 ? true : false;
        }

        public bool UpdateSale(Sale sale)
        {
            _context.Update(sale);

            return Save();
        }

        public bool DeleteSale(Sale sale)
        {
            _context.Remove(sale);

            return Save();
        }
    }
}
