namespace debraApp.Models
{
    public class Commission
    {
        public int CommissionID { get; set; }
        public int EventID { get; set; } // Foreign key to Event
        public decimal CommissionRate { get; set; }
        public decimal TotalSales { get; set; }
        

       
        // Navigation property
        public Event Event { get; set; }
        public decimal CommissionAmount => CommissionRate * TotalSales;
    }
}
