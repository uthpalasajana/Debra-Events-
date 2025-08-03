namespace debraApp.Dto
{
    public class CommissionDto
    {
        public int CommissionID { get; set; }
        public int EventID { get; set; } // Foreign key to Event
        public decimal CommissionRate { get; set; }
        public decimal TotalSales { get; set; }

    }
}
