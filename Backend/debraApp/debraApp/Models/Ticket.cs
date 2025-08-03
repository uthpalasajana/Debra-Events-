namespace debraApp.Models
{
    public class Ticket
    {
        public int TicketID { get; set; }
        public int EventID { get; set; } // Foreign key to Event
        public string TicketType { get; set; }
        public decimal Price { get; set; }
        public int Quantity { get; set; } // Total quantity of tickets available
        public int Sold { get; set; } // Number of tickets sold
        // Navigation properties
        public Event Event { get; set; } // Reference to the related Event
        public ICollection<Sale> Sales { get; set; }

        // Calculated property for remaining tickets
        public int Remaining => Quantity - Sold;
    }
}
