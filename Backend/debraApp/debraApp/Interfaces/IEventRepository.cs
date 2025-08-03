using debraApp.Models;

namespace debraApp.Interfaces
{
    public interface IEventRepository
    {
        ICollection<Event> GetEvents();

        Event GetEvent(int id);
        Event GetEvent(string name);

        ICollection<Ticket> GetTicketByEvent(int eventID);

        bool EventExists(int eventID);

        bool CreateEvent(Event _event);
        bool Save();
        bool UpdateEvent(Event _event);
        bool DeleteEvent(Event _event);

    }
}
