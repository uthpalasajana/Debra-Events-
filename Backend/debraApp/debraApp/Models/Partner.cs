namespace debraApp.Models
{
    public class Partner
    {
        public int PartnerID { get; set; }
        public string Name { get; set; }
        public string ContactInfo { get; set; }
        public string Address { get; set; }
        public string Email { get; set; }
        public string Password { get; set; }
        public DateTime RegisteredDate { get; set; }

        // Navigation property
        public ICollection<Event> Events { get; set; }
    }
}
