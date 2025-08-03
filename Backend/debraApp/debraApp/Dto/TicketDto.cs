namespace debraApp.Dto
{
    public class TicketDto
    {
        public int TicketID { get; set; }
        public int EventID { get; set; } // Foreign key to Event
        public string TicketType { get; set; }
        public decimal Price { get; set; }
        public int Quantity { get; set; } // Total quantity of tickets available
        public int Sold { get; set; } // Number of tickets sold
    }
}
