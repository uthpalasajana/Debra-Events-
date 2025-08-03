namespace debraApp.Dto
{
    public class EventDto
    {
        public int EventID { get; set; }
        public int PartnerID { get; set; } // Foreign key to Partner
        public string EventName { get; set; }
        public string Description { get; set; }
        public string Date { get; set; }
        public string Time { get; set; }
        public string Location { get; set; }
        
        public DateTime CreatedDate { get; set; }
        public string EventImage { get; set; } // New attribute for event image
    }
}
