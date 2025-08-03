using debraApp.DAL;
using debraApp.Interfaces;
using debraApp.Models;

namespace debraApp.Repository
{
    public class TicketRepository : ITicketRepository
    {
        private readonly DataContext _context;

        public TicketRepository(DataContext context)
        {
            _context = context;
        }

        public bool CreateTicket(Ticket ticket)
        {
            _context.Add(ticket);
            return Save();

        }

        public bool DeleteTicket(Ticket ticket)
        {
            _context.Remove(ticket);
            return Save();
        }

        public Ticket GetTicket(int id)
        {
            return _context.Tickets.Where(t => t.TicketID == id).FirstOrDefault();
        }

        public ICollection<Ticket> GetTickets()
        {
            return _context.Tickets.OrderBy(t => t.TicketID).ToList();
        }

        public bool Save()
        {
            var saved = _context.SaveChanges();
            return saved > 0 ? true : false;
        }

        public bool TicketExists(int ticketID)
        {
            return _context.Tickets.Any(t => t.TicketID == ticketID);
        }

        public bool UpdateTicket(Ticket ticket)
        {
            _context.Update(ticket);
            return Save();
        }
    }
}
