namespace debraApp.Dto
{
    public class SaleDto
    {
        public int SaleID { get; set; }
        public int TicketID { get; set; } // Foreign key to Ticket
        public int CustomerID { get; set; } // Foreign key to Customer
        public DateTime SaleDate { get; set; }
        public int TicketNumber { get; set; } // Seat number for the sold ticket
    }
}
