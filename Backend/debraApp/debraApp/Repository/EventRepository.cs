using debraApp.DAL;
using debraApp.Interfaces;
using debraApp.Models;
using Microsoft.EntityFrameworkCore;

namespace debraApp.Repository
{
    public class EventRepository : IEventRepository
    {
        private readonly DataContext _context;

        public EventRepository(DataContext context)
        {
            _context = context;
        }

      
        public bool EventExists(int eventID)
        {
            return _context.Events.Any(e => e.EventID == eventID);
        }

        public Event GetEvent(int id)
        {
            return _context.Events.Where(e => e.EventID == id).FirstOrDefault();
        }

        public Event GetEvent(string name)
        {
            return _context.Events.Where(e => e.EventName == name).FirstOrDefault();
        }

        public ICollection<Event> GetEvents()
        {
            return _context.Events.OrderBy(e => e.EventID).ToList();
        }

        public ICollection<Ticket> GetTicketByEvent(int eventID)
        {
            return _context.Tickets.Where(e => e.EventID == eventID).ToList();
        }


        public bool CreateEvent(Event _event)
        {
            _context.Add(_event);

            return Save();
        }


        public bool Save()
        {
            var saved = _context.SaveChanges();
            return saved > 0 ? true : false;
        }

        public bool UpdateEvent(Event _event)
        {
            _context.Update(_event);

            return Save();
        }

        public bool DeleteEvent(Event _event)
        {
            _context.Remove(_event);
            return Save();
        }
    }
}
