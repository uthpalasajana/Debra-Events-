using debraApp.Models;

namespace debraApp.Interfaces
{
    public interface ITicketRepository
    {
        ICollection<Ticket> GetTickets();
        Ticket GetTicket(int id);
        bool TicketExists(int ticketID);

        bool CreateTicket(Ticket ticket);
        bool Save();

        bool UpdateTicket(Ticket ticket);
        bool DeleteTicket(Ticket ticket);
    }
}
