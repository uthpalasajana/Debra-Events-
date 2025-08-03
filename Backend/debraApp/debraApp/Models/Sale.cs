namespace debraApp.Models
{
    public class Sale
    {
        public int SaleID { get; set; }
        public int TicketID { get; set; } // Foreign key to Ticket
        public int CustomerID { get; set; } // Foreign key to Customer
        public DateTime SaleDate { get; set; }
        public int TicketNumber { get; set; } // Seat number for the sold ticket

        // Navigation properties
        public Ticket Ticket { get; set; } // Reference to the related Ticket
        public Customer Customer { get; set; } // Reference to the related Customer
    }
}
